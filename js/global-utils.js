// Archivo: js/global-utils.js

/**
 * Configura las opciones de toastr.js
 * @param {Object} customOptions - Opciones personalizadas para sobrescribir las predeterminadas
 */
function configureToastr(customOptions = {}) {
  const defaultOptions = {
    closeButton: true,
    progressBar: true,
    positionClass: "toast-top-right",
    showDuration: "300",
    hideDuration: "1000",
    timeOut: "2000",
    extendedTimeOut: "1000",
    showEasing: "swing",
    hideEasing: "linear",
    showMethod: "fadeIn",
    hideMethod: "fadeOut"
  };

  // Combinar las opciones predeterminadas con las personalizadas
  const finalOptions = { ...defaultOptions, ...customOptions };

  // Aplicar la configuración a toastr
  toastr.options = finalOptions;
}

/**
* Muestra un mensaje toastr
* @param {string} type - Tipo de mensaje ('success', 'info', 'warning', 'error')
* @param {string} message - Mensaje a mostrar
* @param {string} [title] - Título opcional del mensaje
* @param {Object} [options] - Opciones adicionales para este mensaje específico
*/
function showToastrMessage(type, message, title = '', options = {}) {
  toastr[type](message, title, options);
}

// Exportar las funciones si estás usando módulos ES6
// export { configureToastr, showToastrMessage };