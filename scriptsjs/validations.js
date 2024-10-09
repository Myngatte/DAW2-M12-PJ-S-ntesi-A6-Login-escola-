function validarFormulario() {

    var nombre = document.getElementById("nombre").value;
    var contrasena = document.getElementById("contrasena").value;
    
    // Expresiones regulares para validaciones
    var validarNombre = /^[A-Za-z]+$/; // Solo letras
    var validarContrasena = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/; // Letras y numeros

    // Nombre no vacio
    if (nombre === "") {
        alert("Por favor, introduce tu nombre de usuario.");
        return false;
    }

    // Nombre solo letras
    if (!validarNombre.test(nombre)) {
        alert("El nombre de usuario solo puede contener letras (A-Z, a-z).");
        return false;
    }

    //Contra no vacia
    if (contrasena === "") {
        alert("Por favor, introduce tu password.");
        return false;
    }

    // Contra debe tener 8 caracteres
    if (contrasena.length < 8) {
        alert("La password debe tener al menos 8 caracteres.");
        return false;
    }

    // Debe tener por lo menos una letra y un numero
    if (!validarContrasena.test(contrasena)) {
        alert("La password debe contener al menos una letra y un numero.");
        return false;
    }

    return true; // Si todo esta bien, permitir el envio del formulario
}