/**
 * Maneja los correos dinámicamente.
 */
function manejarCorreos() {
    const emailContainer = document.querySelector('#emails-container');
    if (emailContainer) {
        emailContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-email')) {
                const newInput = document.createElement('div');
                newInput.classList.add('input-group', 'mb-2');
                newInput.innerHTML = `
                    <input type="email" class="form-control" name="emails[]" placeholder="Correo (Opcional)">
                    <button type="button" class="btn btn-danger remove-email">x</button>
                `;
                emailContainer.appendChild(newInput);
            } else if (e.target.classList.contains('remove-email')) {
                e.target.closest('.input-group').remove();
            }
        });
    }
}

/**
 * Maneja los teléfonos dinámicamente.
 */
function manejarTelefonos() {
    const phoneContainer = document.querySelector('#telefonos-container');
    if (phoneContainer) {
        phoneContainer.addEventListener('click', function (e) {
            if (e.target.classList.contains('add-phone')) {
                const newInput = document.createElement('div');
                newInput.classList.add('input-group', 'mb-2');
                newInput.innerHTML = `
                    <input type="text" class="form-control" name="telefonos[]" placeholder="Teléfono (Opcional)">
                    <button type="button" class="btn btn-danger remove-phone">x</button>
                `;
                phoneContainer.appendChild(newInput);
            } else if (e.target.classList.contains('remove-phone')) {
                e.target.closest('.input-group').remove();
            }
        });
    }
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
        // Marcar el campo como obligatorio
        inputNumeroDocumento.setAttribute('required', 'required');
    } else {
        campoNumeroDocumento.style.display = 'none';
        // Eliminar el atributo obligatorio si no está visible
        inputNumeroDocumento.removeAttribute('required');
    }
}

function habilitarMayusculas() {
    const campos = document.querySelectorAll('.texto-mayuscula');

    campos.forEach((campo) => {
        // Evento para convertir mientras el usuario escribe
        campo.addEventListener('input', function () {
            this.value = this.value.toUpperCase();
        });

        // Evento para convertir al pegar texto
        campo.addEventListener('paste', function (event) {
            // Esperar a que el texto se pegue antes de convertirlo
            setTimeout(() => {
                this.value = this.value.toUpperCase();
            }, 0);
        });
    });
}

function validarRoles() {
    const roles = document.querySelectorAll('input[name="roles[]"]');
    const errorDiv = document.querySelector('#roles-error');
    let alMenosUnoSeleccionado = false;

    // Verificar si al menos uno está seleccionado
    roles.forEach(role => {
        if (role.checked) {
            alMenosUnoSeleccionado = true;
        }
    });

    if (!alMenosUnoSeleccionado) {
        errorDiv.style.display = 'block';
        return false; // Detiene el envío del formulario
    } else {
        errorDiv.style.display = 'none';
        return true; // Permite el envío del formulario
    }
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
 * Función para inicializar funciones específicas según se necesiten.
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

    // Verificar el estado inicial del campo "Número de Documento"
    if (document.getElementById('id_tipo_identificacion')) {
        mostrarNumeroDocumento(); // Llama a la función al cargar la página
    }

    inicializarTooltips();
}

// Invocar la inicialización automáticamente al cargar la página.
document.addEventListener('DOMContentLoaded', inicializarFunciones);
