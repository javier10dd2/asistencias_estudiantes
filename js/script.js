// js/script.js

// Cambia la imagen según el rol seleccionado
function cambiarImagen() {
    const tipo = document.querySelector("select[name='tipo']").value;
    const imagenRol = document.getElementById("imagenRol");

    if (tipo === "Profesor") {
        imagenRol.src = "images/user_profesor.png";  
        imagenRol.alt = "Imagen de Profesor";
    } else if (tipo === "Alumno") {
        imagenRol.src = "images/user_estudiante.png";  
        imagenRol.alt = "Imagen de Alumno";
    } else {
        imagenRol.src = "images/user.png";  
        imagenRol.alt = "Imagen por Defecto";
    }
}

// Validación adicional para el formulario
function validarFormulario(event) {
    const nombre = document.forms["registroForm"]["nombre"].value;
    const correo = document.forms["registroForm"]["correo"].value;
    const telefono = document.forms["registroForm"]["telefono"].value;
    const contrasena = document.forms["registroForm"]["contrasena"].value;
    const tipo = document.forms["registroForm"]["tipo"].value;

    if (nombre.length < 3) {
        alert("El nombre debe tener al menos 3 caracteres.");
        event.preventDefault();
        return false;
    }
    if (!correo.includes("@")) {
        alert("El correo debe tener un formato válido.");
        event.preventDefault();
        return false;
    }
    if (telefono.length !== 9 || !/^[0-9]+$/.test(telefono)) {
        alert("El teléfono debe tener 9 dígitos y solo contener números.");
        event.preventDefault();
        return false;
    }
    if (contrasena.length < 6) {
        alert("La contraseña debe tener al menos 6 caracteres.");
        event.preventDefault();
        return false;
    }
    if (tipo === "") {
        alert("Por favor, seleccione un rol.");
        event.preventDefault();
        return false;
    }
    return true;
}
