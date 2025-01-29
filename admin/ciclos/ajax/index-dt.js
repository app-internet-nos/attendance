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

const createOrEdit = async (id = null) => {
  const isEditing = id !== null;
  const title = isEditing ? "Editar ciclo" : "Crear ciclo";
  const confirmButtonText = isEditing ? "Actualizar" : "Guardar";

  let data = { nombre: '', descripcion: '' };
  if (isEditing) {
    try {
      const response = await fetch(`php/editar.php?id=${id}`);
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
      }
      const responseData = await response.json();
      console.log('Datos recibidos:', responseData);  // Log para depuración
      if (responseData.success) {
        data = responseData.data || { nombre: '', descripcion: '' };
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
      <input type="text" id="nombre" class="swal2-input" value="${data.nombre || ''}" placeholder="Nombre de ciclo">
      <input type="text" id="descripcion" class="swal2-input" value="${data.descripcion || ''}" placeholder="Descripción de ciclo">
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText,
    cancelButtonText: "Cancelar",
    preConfirm: () => {
      const nombre = Swal.getPopup().querySelector("#nombre").value;
      const descripcion = Swal.getPopup().querySelector("#descripcion").value;
      if (!nombre || !descripcion) {
        Swal.showValidationMessage("Por favor, complete todos los campos obligatorios");
        return false;
      }
      return { id, nombre, descripcion };
    }
  });

  if (result.isConfirmed) {
    const url = isEditing ? "php/actualizar.php" : "php/crear.php";
    const successMessage = {
      title: isEditing ? "¡Editado!" : "¡Creado!",
      text: `Ciclo ${isEditing ? 'actualizado' : 'creado'}`,
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
      text: "El ciclo ha sido eliminado",
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
        title: "Detalles de ciclo",
        html: `
          <strong>Nombre:</strong> ${data.nombre || 'No disponible'}<br>
          <strong>Descripción:</strong> ${data.descripcion || 'No disponible'}<br>
        `,
        confirmButtonText: "Cerrar",
        focusConfirm: false
      });
    } else {
      throw new Error(responseData.message || 'Error al obtener datos');
    }
  } catch (error) {
    console.error('Error al obtener detalles de ciclo:', error);
    Swal.fire("Error", "No se pudo obtener los detalles de ciclo", "error");
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