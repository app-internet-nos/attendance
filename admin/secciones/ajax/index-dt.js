$(document).ready(() => {
  const table = initializeDataTable("#dt", "php/datos-dt.php", languageConfig);
  disableButtons(table, [1, 2, 3]);
  toggleRowSelection(table, "#dt tbody", [1, 2, 3]);
});

const handleSwalFormSubmit = async (url, data, successMessage) => {
  try {
    const response = await fetch(url, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    });
    
    const responseText = await response.text();
    console.log('Respuesta del servidor (texto):', responseText);  // Log para depuración
    
    let responseData;
    try {
      responseData = JSON.parse(responseText);
    } catch (parseError) {
      console.error('Error al parsear JSON:', parseError);
      throw new Error(`Respuesta no válida del servidor: ${responseText}`);
    }
    
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    
    console.log('Respuesta del servidor (JSON):', responseData);  // Log para depuración
    
    if (responseData.success) {
      await Swal.fire(successMessage.title, successMessage.text, 'success');
      if (successMessage.continueAction) {
        crear();
      }
    } else {
      throw new Error(responseData.message || 'Error desconocido');
    }
  } catch (error) {
    console.error('Error en la solicitud:', error);
    Swal.fire('Error', `Hubo un problema al procesar la solicitud: ${error.message}`, 'error');
  }
};

const fetchDataForSelect = async (endpoint) => {
  try {
    const response = await fetch(endpoint);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const data = await response.json();
    console.log(`Datos obtenidos de ${endpoint}:`, data); // Depuración: Verifica los datos
    return data;
  } catch (error) {
    console.error(`Error fetching data for select: ${error.message}`);
    return [];
  }
};

const populateSelectOptions = (selectId, data, placeholder) => {
  const selectElement = document.getElementById(selectId);
  selectElement.innerHTML = `<option value="">${placeholder}</option>`;
  data.forEach(item => {
    const option = document.createElement("option");
    option.value = item.id; // Asegúrate de que 'id' es el campo correcto
    option.textContent = item.nombre; // Asegúrate de que 'nombre' es el campo correcto
    selectElement.appendChild(option);
  });
};

const createOrEdit = async (id = null) => {
  const isEditing = id !== null;
  const title = isEditing ? "Editar sección" : "Crear sección";
  const confirmButtonText = isEditing ? "Actualizar" : "Guardar";

  let data = { id_programa_estudio: '', id_ciclo: '', año: '' };
  if (isEditing) {
    try {
      const response = await fetch(`php/editar.php?id=${id}`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const responseData = await response.json();
      console.log('Datos recibidos para edición:', responseData);
      if (responseData.success) {
        data = responseData.data || { id_programa_estudio: '', id_ciclo: '', año: '' };
      } else {
        throw new Error(responseData.message || 'Error al obtener datos');
      }
    } catch (error) {
      console.error('Error al obtener datos para edición:', error);
      Swal.fire('Error', 'No se pudieron cargar los datos para edición', 'error');
      return;
    }
  }

  const result = await Swal.fire({
    title,
    customClass: { container: 'custom-modal' },
    html: `
      <select id="id_programa_estudio" class="swal2-select">
        Cargando programas de estudio...
      </select>

      <select id="id_ciclo" class="swal2-select">
        Cargando ciclos...
      </select>
      
      <input type="text" id="año" class="swal2-input" value="${data.año || ''}" placeholder="Año de estudio">
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText: "Cancelar",
    didOpen: async () => {
      // Cargar los datos para los selects cuando se abre el modal
      const programasEstudio = await fetchDataForSelect('php/get_data.php?type=programas_estudio');
      const ciclos = await fetchDataForSelect('php/get_data.php?type=ciclos');

      console.log('Programas de Estudio:', programasEstudio); // Depuración: Verifica la carga de programas de estudio
      console.log('Ciclos:', ciclos); // Depuración: Verifica la carga de ciclos

      populateSelectOptions("id_programa_estudio", programasEstudio, "Seleccione un programa de estudio");
      populateSelectOptions("id_ciclo", ciclos, "Seleccione un ciclo");

      // Establecer valores predeterminados si está editando
      if (isEditing) {
        document.getElementById("id_programa_estudio").value = data.id_programa_estudio;
        document.getElementById("id_ciclo").value = data.id_ciclo;
      }
    },
    preConfirm: () => {
      const id_programa_estudio = Swal.getPopup().querySelector("#id_programa_estudio").value;
      const id_ciclo = Swal.getPopup().querySelector("#id_ciclo").value;
      const año = Swal.getPopup().querySelector("#año").value;
      if (!id_programa_estudio || !id_ciclo || !año) {
        Swal.showValidationMessage("Por favor, complete todos los campos obligatorios");
        return false;
      }
      return { id, id_programa_estudio, id_ciclo, año };
    }
  });

  if (result.isConfirmed) {
    const url = isEditing ? "php/actualizar.php" : "php/crear.php";
    const successMessage = {
      title: isEditing ? "¡Editado!" : "¡Creado!",
      text: `Sección ${isEditing ? 'actualizada' : 'creada'} correctamente`,
      continueAction: !isEditing
    };
    await handleSwalFormSubmit(url, result.value, successMessage);
    $('#dt').DataTable().ajax.reload(null, false);
  }
};

const crear = () => createOrEdit();
const editar = (id) => createOrEdit(id);



const eliminar = async (id) => {
  const result = await Swal.fire({
    title: "¿Estás seguro?",
    text: "No podrás deshacer esta acción.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar"
  });

  if (result.isConfirmed) {
    await handleSwalFormSubmit("php/eliminar.php", { id }, {
      title: "¡Eliminado!",
      text: "La sección ha sido eliminada",
      continueAction: false
    });
    $('#dt').DataTable().ajax.reload(null, false);
  }
};

const ver = async (id) => {
  try {
    const response = await fetch(`php/ver.php?id=${id}`);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }
    const responseData = await response.json();
    console.log('Datos recibidos:', responseData);  // Log para depuración

    if (responseData.success) {
      const data = responseData.data || {};
      await Swal.fire({
        title: "Detalles de la sección",
        html: `
          <strong>Nombre:</strong> ${data.id_programa_estudio || 'No disponible'}<br>
          <strong>Descripción:</strong> ${data.id_ciclo || 'No disponible'}<br>
          <strong>Descripción:</strong> ${data.año || 'No disponible'}<br>
        `,
        confirmButtonText: "Cerrar",
        focusConfirm: false
      });
    } else {
      throw new Error(responseData.message || 'Error al obtener datos');
    }
  } catch (error) {
    console.error('Error al obtener detalles de la sección:', error);
    Swal.fire("Error", "No se pudo obtener los detalles de la sección", "error");
  }
};

const handleResponse = async (response, titleMessage, successMessage, continueAction = false) => {
  const data = await response.json();
  if (data.success) {
    await Swal.fire(titleMessage, successMessage, 'success');
    if (continueAction) {
      crear();
    }
  } else {
    Swal.fire('Error', data.message, 'error');
  }
};