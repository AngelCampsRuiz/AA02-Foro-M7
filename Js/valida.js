document.getElementById("titulo").onblur = function validaTitulo() {
    var titulo = document.getElementById("titulo").value;
    var errorTitulo = document.getElementById("error-titulo");
   
    if (titulo === "") {
        errorTitulo.innerHTML = "Debes ingresar un título";
        return false;
    } else if (titulo.length < 3) {
        errorTitulo.innerHTML = "El título debe tener al menos 3 caracteres";
        return false;
    } else {
        errorTitulo.innerHTML = "";
        return true;
    }
};

document.getElementById("pregunta").onblur = function validaPregunta() {
    var pregunta = document.getElementById("pregunta").value.trim();
    var errorPregunta = document.getElementById("error-pregunta");

    if (pregunta === "") {
        errorPregunta.innerHTML = "Debes ingresar una pregunta";
        return false;
    } else if (pregunta.length < 5) {
        errorPregunta.innerHTML = "La pregunta debe tener al menos 5 caracteres";
        return false;
    } else {
        errorPregunta.innerHTML = "";
        return true;
    }
};
