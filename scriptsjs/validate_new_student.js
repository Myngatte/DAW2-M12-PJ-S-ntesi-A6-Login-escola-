document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector('form');
    const usuarioEscuela = document.getElementById('usuario_escuela');
    const nomUsuario = document.getElementById('nom_usuario');
    const apeUsuario = document.getElementById('ape_usuario');
    const telefonoUsuario = document.getElementById('telefono_usuario');
    const fechaNacimiUsuario = document.getElementById('fecha_nacimi_usuario');
    const sexoUsuario = document.getElementById('sexo_usuario');
    
    // Agregar errores por campo
    const agregarError = (campo, mensaje) => {
        // Crear el elemento de error
        let error = document.createElement('span');
        error.classList.add('error');
        error.textContent = mensaje;

        // Añadir el mensaje de error debajo del campo correspondiente
        if (!campo.nextElementSibling || !campo.nextElementSibling.classList.contains('error')) {
            campo.parentNode.appendChild(error);
        }
    };

    // Eliminar errores acumulados
    const quitarErrores = (campo) => {
        const errores = campo.parentNode.querySelectorAll('.error');
        errores.forEach(error => error.remove());
    };

    // Validación de 'usuario_escuela'
    usuarioEscuela.addEventListener('blur', function () {
        const valor = usuarioEscuela.value.trim();
        quitarErrores(usuarioEscuela);  // Limpiar errores previos

        // Array para almacenar errores
        let errores = [];

        // Validar que el campo tenga al menos 4 caracteres
        if (valor.length < 4) {
            errores.push("El usuario debe tener al menos 4 caracteres.");
        }

        // Validar que no contenga números
        if (/\d/.test(valor)) {
            errores.push("El usuario no puede contener números.");
        }

        // Validar que esté en minúsculas
        if (valor !== valor.toLowerCase()) {
            errores.push("El usuario debe estar en minúsculas.");
        }

        // Si hay errores, mostrarlos todos
        errores.forEach(error => agregarError(usuarioEscuela, error));
    });

    // Validación de 'nom_usuario'
    nomUsuario.addEventListener('blur', function () {
        const valor = nomUsuario.value.trim();
        quitarErrores(nomUsuario);  // Limpiar errores previos

        // Array para almacenar errores
        let errores = [];

        // Validar que el nombre tenga al menos 4 caracteres
        if (valor.length < 4) {
            errores.push("El nombre debe tener al menos 4 caracteres.");
        }

        // Validar que no contenga números
        if (/\d/.test(valor)) {
            errores.push("El nombre no puede contener números.");
        }

        // Mostrar todos los errores acumulados
        errores.forEach(error => agregarError(nomUsuario, error));
    });

    // Validación de 'ape_usuario'
    apeUsuario.addEventListener('blur', function () {
        const valor = apeUsuario.value.trim();
        quitarErrores(apeUsuario);  // Limpiar errores previos

        // Array para almacenar errores
        let errores = [];

        // Validar que el apellido tenga al menos 4 caracteres
        if (valor.length < 4) {
            errores.push("El apellido debe tener al menos 4 caracteres.");
        }

        // Validar que no contenga números
        if (/\d/.test(valor)) {
            errores.push("El apellido no puede contener números.");
        }

        // Mostrar todos los errores acumulados
        errores.forEach(error => agregarError(apeUsuario, error));
    });

    // Validación de 'telefono_usuario'
    telefonoUsuario.addEventListener('blur', function () {
        const valor = telefonoUsuario.value.trim();
        quitarErrores(telefonoUsuario);  // Limpiar errores previos

        // Array para almacenar errores
        let errores = [];

        // Validar que el teléfono tenga exactamente 9 dígitos
        if (!/^\d{9}$/.test(valor)) {
            errores.push("El teléfono debe tener exactamente 9 dígitos.");
        }

        // Mostrar todos los errores acumulados
        errores.forEach(error => agregarError(telefonoUsuario, error));
    });

    // Validación de 'fecha_nacimi_usuario'
    fechaNacimiUsuario.addEventListener('blur', function () {
        const valor = fechaNacimiUsuario.value.trim();
        const fechaHoy = new Date();
        const fechaNac = new Date(valor);
        quitarErrores(fechaNacimiUsuario);  // Limpiar errores previos

        // Array para almacenar errores
        let errores = [];

        // Validar que la fecha no sea futura
        if (fechaNac > fechaHoy) {
            errores.push("La fecha de nacimiento no puede ser una fecha futura.");
        }

        // Mostrar todos los errores acumulados
        errores.forEach(error => agregarError(fechaNacimiUsuario, error));
    });

    // Validación de 'sexo_usuario'
    sexoUsuario.addEventListener('blur', function () {
        quitarErrores(sexoUsuario);  // Limpiar errores previos

        // Array para almacenar errores
        let errores = [];

        // Validar que se seleccione un sexo
        if (!sexoUsuario.value) {
            errores.push("Por favor selecciona un sexo.");
        }

        // Mostrar todos los errores acumulados
        errores.forEach(error => agregarError(sexoUsuario, error));
    });

    // Validación final al enviar el formulario
    form.addEventListener('submit', function (e) {
        e.preventDefault();  // Prevenir el envío del formulario hasta que se validen los campos
        
        let esValido = true;

        // Limpiar todos los errores previos
        const erroresPrevios = document.querySelectorAll('.error');
        erroresPrevios.forEach(error => error.remove());

        // Validación de 'usuario_escuela'
        const valorUsuario = usuarioEscuela.value.trim();
        let erroresUsuario = [];
        if (valorUsuario.length < 4) erroresUsuario.push("El usuario debe tener al menos 4 caracteres.");
        if (/\d/.test(valorUsuario)) erroresUsuario.push("El usuario no puede contener números.");
        if (valorUsuario !== valorUsuario.toLowerCase()) erroresUsuario.push("El usuario debe estar en minúsculas.");
        if (erroresUsuario.length > 0) {
            erroresUsuario.forEach(error => agregarError(usuarioEscuela, error));
            esValido = false;
        }

        // Validación de 'nom_usuario'
        const valorNom = nomUsuario.value.trim();
        let erroresNom = [];
        if (valorNom.length < 4) erroresNom.push("El nombre debe tener al menos 4 caracteres.");
        if (/\d/.test(valorNom)) erroresNom.push("El nombre no puede contener números.");
        if (erroresNom.length > 0) {
            erroresNom.forEach(error => agregarError(nomUsuario, error));
            esValido = false;
        }

        // Validación de 'ape_usuario'
        const valorApe = apeUsuario.value.trim();
        let erroresApe = [];
        if (valorApe.length < 4) erroresApe.push("El apellido debe tener al menos 4 caracteres.");
        if (/\d/.test(valorApe)) erroresApe.push("El apellido no puede contener números.");
        if (erroresApe.length > 0) {
            erroresApe.forEach(error => agregarError(apeUsuario, error));
            esValido = false;
        }

        // Validación de 'telefono_usuario'
        const valorTel = telefonoUsuario.value.trim();
        let erroresTel = [];
        if (!/^\d{9}$/.test(valorTel)) erroresTel.push("El teléfono debe tener exactamente 9 dígitos.");
        if (erroresTel.length > 0) {
            erroresTel.forEach(error => agregarError(telefonoUsuario, error));
            esValido = false;
        }

        // Validación de 'fecha_nacimi_usuario'
        const valorFecha = fechaNacimiUsuario.value.trim();
        let erroresFecha = [];
        const fechaHoy = new Date();
        const fechaNac = new Date(valorFecha);
        if (fechaNac > fechaHoy) erroresFecha.push("La fecha de nacimiento no puede ser una fecha futura.");
        if (erroresFecha.length > 0) {
            erroresFecha.forEach(error => agregarError(fechaNacimiUsuario, error));
            esValido = false;
        }

        // Validación de 'sexo_usuario'
        let erroresSexo = [];
        if (!sexoUsuario.value) erroresSexo.push("Por favor selecciona un sexo.");
        if (erroresSexo.length > 0) {
            erroresSexo.forEach(error => agregarError(sexoUsuario, error));
            esValido = false;
        }

        // Si no hay errores, enviamos el formulario
        if (esValido) {
            form.submit();
        }
    });
});
