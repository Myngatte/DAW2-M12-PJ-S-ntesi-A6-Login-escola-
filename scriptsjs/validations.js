function validarFormulario() {
    var nombre = document.getElementById("nombre").value;
    var contrasena = document.getElementById("contrasena").value;

    // Elementos para mostrar errores
    var errorNombre = document.getElementById("error-nombre");
    var errorContrasena = document.getElementById("error-contrasena");

    // Limpiar mensajes previos
    errorNombre.innerHTML = "";
    errorContrasena.innerHTML = "";

    // Expresiones regulares para validaciones
    var validarNombre = /^[A-Za-z]+$/; // Solo letras
    var validarContrasena = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/; // Letras y numeros

    // Variable para rastrear si hay errores
    var isValid = true;

    // Validación del nombre no vacío
    if (nombre === "") {
        errorNombre.innerHTML = "Por favor, introduce tu nombre de usuario.";
        isValid = false;
    } else if (!validarNombre.test(nombre)) {
        // Validación de nombre solo con letras
        errorNombre.innerHTML = "El nombre de usuario solo puede contener letras (A-Z, a-z).";
        isValid = false;
    }

    // Validación de la contraseña no vacía
    if (contrasena === "") {
        errorContrasena.innerHTML = "Por favor, introduce tu contraseña.";
        isValid = false;
    } else if (contrasena.length < 8) {
        // Validación de longitud de la contraseña
        errorContrasena.innerHTML = "La contraseña debe tener al menos 8 caracteres.";
        isValid = false;
    } else if (!validarContrasena.test(contrasena)) {
        // Validación de que la contraseña contenga al menos una letra y un número
        errorContrasena.innerHTML = "La contraseña debe contener al menos una letra y un número.";
        isValid = false;
    }

    return isValid; // Retorna true si todo está bien, de lo contrario false
}
