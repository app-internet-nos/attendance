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
  const title = isEditing ? "Editar clase" : "Crear clase";
  const confirmButtonText = isEditing ? "Actualizar" : "Guardar";

  let data = { id_asignatura: '', id_docente: '', id_seccion: '' };
  if (isEditing) {
    try {
      const response = await fetch(`php/editar.php?id=${id}`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const responseData = await response.json();
      console.log('Datos recibidos para edición:', responseData);
      if (responseData.success) {
        data = responseData.data || { id_asignatura: '', id_docente: '', id_seccion: '' };
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
      <select id="id_asignatura" class="swal2-select">
        Cargando asignaturas...
      </select>

      <select id="id_docente" class="swal2-select">
        Cargando docentes...
      </select>

      <select id="id_seccion" class="swal2-select">
        Cargando secciones...
      </select>
            
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText: "Cancelar",
    didOpen: async () => {
      // Cargar los datos para los selects cuando se abre el modal
      const asignaturas = await fetchDataForSelect('php/get_data.php?type=asignaturas');
      const docentes = await fetchDataForSelect('php/get_data.php?type=docentes');
      const secciones = await fetchDataForSelect('php/get_data.php?type=secciones');

      console.log('Asignaturas:', asignaturas); // Depuración: Verifica la carga de programas de estudio
      console.log('Docentes:', docentes); // Depuración: Verifica la carga de ciclos
      console.log('Secciones:', secciones); // Depuración: Verifica la carga de ciclos

      populateSelectOptions("id_asignatura", asignaturas, "Seleccione una asignatura");
      populateSelectOptions("id_docente", docentes, "Seleccione un docente");
      populateSelectOptions("id_seccion", secciones, "Seleccione una sección");

      // Establecer valores predeterminados si está editando
      if (isEditing) {
        document.getElementById("id_asignatura").value = data.id_asignatura;
        document.getElementById("id_docente").value = data.id_docente;
        document.getElementById("id_seccion").value = data.id_seccion;
      }
    },
    preConfirm: () => {
      const id_asignatura = Swal.getPopup().querySelector("#id_asignatura").value;
      const id_docente = Swal.getPopup().querySelector("#id_docente").value;
      const id_seccion = Swal.getPopup().querySelector("#id_seccion").value;
      
      if (!id_asignatura || !id_docente || !id_seccion) {
        Swal.showValidationMessage("Por favor, complete todos los campos obligatorios");
        return false;
      }
      return { id, id_asignatura, id_docente, id_seccion };
    }
  });

  if (result.isConfirmed) {
    const url = isEditing ? "php/actualizar.php" : "php/crear.php";
    const successMessage = {
      title: isEditing ? "¡Editado!" : "¡Creado!",
      text: `Clase ${isEditing ? 'actualizada' : 'creada'} correctamente`,
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
      text: "La clase ha sido eliminada",
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
        title: "Detalles de la clase",
        html: `
          <strong>Asignatura:</strong> ${data.id_asignatura || 'No disponible'}<br>
          <strong>Docente:</strong> ${data.id_docente || 'No disponible'}<br>
          <strong>Sección:</strong> ${data.id_seccion || 'No disponible'}<br>
        `,
        confirmButtonText: "Cerrar",
        focusConfirm: false
      });
    } else {
      throw new Error(responseData.message || 'Error al obtener datos');
    }
  } catch (error) {
    console.error('Error al obtener detalles de la clase:', error);
    Swal.fire("Error", "No se pudo obtener los detalles de la clase", "error");
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