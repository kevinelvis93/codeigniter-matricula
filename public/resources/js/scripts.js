/**
 * Maneja los correos dinámicamente, asegurando que solo haya un campo inicial.
 */
function manejarCorreos() {
    const emailContainer = document.querySelector('#emails-container');
    if (emailContainer) {
        let existingEmails = emailContainer.querySelectorAll('input[name="emails[]"]');

        // Mantener solo un campo inicial si no hay valores
        if (existingEmails.length === 0) {
            agregarCorreo(emailContainer, '');
        }

        emailContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-email')) {
                agregarCorreo(emailContainer, '');
            } else if (e.target.classList.contains('remove-email')) {
                e.target.closest('.input-group').remove();
            }
        });
    }
}

/**
 * Maneja los teléfonos dinámicamente, asegurando que solo haya un campo inicial.
 */
function manejarTelefonos() {
    const phoneContainer = document.querySelector('#telefonos-container');
    if (phoneContainer) {
        let existingPhones = phoneContainer.querySelectorAll('input[name="telefonos[]"]');

        // Mantener solo un campo inicial si no hay valores
        if (existingPhones.length === 0) {
            agregarTelefono(phoneContainer, '');
        }

        phoneContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-phone')) {
                agregarTelefono(phoneContainer, '');
            } else if (e.target.classList.contains('remove-phone')) {
                e.target.closest('.input-group').remove();
            }
        });
    }
}

/**
 * Agrega un nuevo campo de correo electrónico.
 */
function agregarCorreo(container, emailValue = '') {
    const newInput = document.createElement('div');
    newInput.classList.add('input-group', 'mb-2');
    newInput.innerHTML = `
        <input type="email" class="form-control" name="emails[]" value="${emailValue}" placeholder="Correo">
        <button type="button" class="btn btn-danger remove-email">x</button>
    `;
    container.appendChild(newInput);
}

/**
 * Agrega un nuevo campo de teléfono.
 */
function agregarTelefono(container, phoneValue = '') {
    const newInput = document.createElement('div');
    newInput.classList.add('input-group', 'mb-2');
    newInput.innerHTML = `
        <input type="text" class="form-control" name="telefonos[]" value="${phoneValue}" placeholder="Teléfono">
        <button type="button" class="btn btn-danger remove-phone">x</button>
    `;
    container.appendChild(newInput);
}

/**
 * Generar una contraseña aleatoria de máximo 8 caracteres, solo letras minúsculas.
 */
function generarPassword() {
    const caracteres = 'abcdefghijklmnopqrstuvwxyz';
    let password = '';
    for (let i = 0; i < 8; i++) {
        const randomIndex = Math.floor(Math.random() * caracteres.length);
        password += caracteres[randomIndex];
    }
    document.getElementById('password').value = password;
}

/**
 * Mostrar u ocultar la contraseña.
 */
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.getElementById('toggle-password');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.textContent = 'Ocultar';
    } else {
        passwordInput.type = 'password';
        toggleButton.textContent = 'Mostrar';
    }
}

/**
 * Mostrar u ocultar el campo de Número de Documento basado en la selección.
 */
function mostrarNumeroDocumento() {
    const selectTipoIdentificacion = document.getElementById('id_tipo_identificacion');
    const campoNumeroDocumento = document.getElementById('campo-numero-documento');
    const inputNumeroDocumento = document.getElementById('identificacion_descripcion');

    // Mostrar el campo solo si se selecciona un valor válido
    if (selectTipoIdentificacion && selectTipoIdentificacion.value) {
        campoNumeroDocumento.style.display = 'block';
        inputNumeroDocumento.setAttribute('required', 'required');
    } else {
        campoNumeroDocumento.style.display = 'none';
        inputNumeroDocumento.removeAttribute('required');
    }
}

/**
 * Habilitar mayúsculas automáticas en ciertos campos.
 */
function habilitarMayusculas() {
    const campos = document.querySelectorAll('.texto-mayuscula');

    campos.forEach((campo) => {
        // Convertir el contenido actual del campo a mayúsculas (para editar.php)
        campo.value = campo.value.toUpperCase();

        // Evento para convertir mientras el usuario escribe
        campo.addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });

        // Evento para convertir al pegar texto
        campo.addEventListener('paste', function () {
            setTimeout(() => {
                this.value = this.value.toUpperCase();
            }, 0);
        });
    });
}

/**
 * Validar que al menos un rol esté seleccionado.
 */
function validarRoles() {
    const roles = document.querySelectorAll('input[name="roles[]"]');
    const errorDiv = document.querySelector('#roles-error');
    let alMenosUnoSeleccionado = false;

    roles.forEach(role => {
        if (role.checked) {
            alMenosUnoSeleccionado = true;
        }
    });

    if (!alMenosUnoSeleccionado) {
        errorDiv.style.display = 'block';
        return false;
    } else {
        errorDiv.style.display = 'none';
        return true;
    }
}

/**
 * Maneja el botón de eliminar colaborador con SweetAlert2.
 */
function inicializarEliminarColaborador() {
    document.querySelectorAll('.btn-eliminar').forEach(button => {
        button.addEventListener('click', function () {
            const colaboradorId = this.getAttribute('data-id');

            Swal.fire({
                title: '¿Estás seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                input: 'password',
                inputLabel: 'Ingrese su contraseña para confirmar',
                inputPlaceholder: 'Contraseña',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Confirmar eliminación',
                cancelButtonText: 'Cancelar',
                preConfirm: (password) => {
                    if (!password) {
                        Swal.showValidationMessage('Debe ingresar una contraseña');
                    }
                    return fetch(`colaborador/validar-clave`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ clave: password })
                    }).then(response => response.json())
                      .then(data => {
                          if (!data.valido) {
                              throw new Error('Contraseña incorrecta');
                          }
                          return true;
                      }).catch(error => {
                          Swal.showValidationMessage(error.message);
                      });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Confirmar eliminación',
                        text: '¿Desea eliminar este colaborador?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((confirmResult) => {
                        if (confirmResult.isConfirmed) {
                            fetch(`colaborador/eliminar/${colaboradorId}`, {
                                method: 'DELETE'
                            }).then(response => response.json())
                              .then(data => {
                                  if (data.success) {
                                      Swal.fire('Eliminado', 'El colaborador ha sido eliminado.', 'success')
                                          .then(() => location.reload());
                                  } else {
                                      Swal.fire('Error', data.error, 'error');
                                  }
                              }).catch(error => {
                                  Swal.fire('Error', 'Hubo un problema en la eliminación.', 'error');
                              });
                        }
                    });
                }
            });
        });
    });
}

/**
 * Inicializa los tooltips de Bootstrap 5.
 */
function inicializarTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Inicializa las funciones dinámicas en la carga de la página.
 */
function inicializarFunciones() {
    if (document.querySelector('#emails-container')) {
        manejarCorreos();
    }

    if (document.querySelector('#telefonos-container')) {
        manejarTelefonos();
    }

    if (document.querySelectorAll('.texto-mayuscula').length > 0) {
        habilitarMayusculas();
    }

    if (document.getElementById('id_tipo_identificacion')) {
        mostrarNumeroDocumento();
    }

    inicializarEliminarColaborador();
    inicializarTooltips();
}

// Invocar la inicialización automáticamente al cargar la página.
document.addEventListener('DOMContentLoaded', inicializarFunciones);

