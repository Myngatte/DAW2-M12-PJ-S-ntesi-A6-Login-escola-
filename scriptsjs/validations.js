// Login
// Validación del formulario antes de enviarlo
function validarFormulario() {
    const nombre = document.getElementById("nombre").value.trim();
    const contrasena = document.getElementById("contrasena").value.trim();

    if (!nombre || !contrasena) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Por favor, completa todos los campos."
        });
        return false; // Evita que se envíe el formulario
    }

    return true; // Permite que se envíe el formulario
}

// Mostrar alertas según el parámetro de error en la URL
document.addEventListener("DOMContentLoaded", () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("error")) {
        const error = urlParams.get("error");
        let mensaje = "";
        switch (error) {
            case "campos_vacios":
                mensaje = "Por favor, completa todos los campos.";
                break;
            case "usr_mal":
                mensaje = "Usuario o contraseña incorrecta";
                break;
            default:
                mensaje = "No sabemos que ha pasado.";
        }

        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: mensaje,
        });
    }
});
