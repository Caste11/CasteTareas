const idTarea = new URLSearchParams(window.location.search).get('id');

const id = new FormData();
id.append('id', idTarea);

const options = {
    method: 'POST',
    body: id
}

fetch('index.php?accion=ver_editar_tarea', options)
    .then(respuesta => respuesta.json())
    .then(tareaYFotos => {
        console.log(tareaYFotos);
        var tareaDiv = document.createElement("div");
        tareaDiv.classList.add("container");

        // Crear el formulario
        var formulario = document.createElement("form");

        // Crear el input de tipo texto
        var inputTexto = document.createElement("input");
        inputTexto.type = "text";
        inputTexto.name = "tarea";
        inputTexto.id = "texto";
        inputTexto.value = tareaYFotos.tarea.texto;
        inputTexto.classList.add("form-control", "mb-3");

        // Crear el div con id "addImage"
        var fotosdiv = document.createElement("div");
        fotosdiv.id = "fotos";
        fotosdiv.classList.add("mb-3");

        tareaYFotos.fotos.forEach(foto => {
            let imgfoto = document.createElement("img");
            imgfoto.style.width="250px";
            imgfoto.style.height="250px";
            imgfoto.setAttribute("src", 'web/fotos/' + foto.nombrefoto);
            imgfoto.classList.add("img-thumbnail");
            fotosdiv.append(imgfoto);
        });

        // Crear el div con id "addImage"
        var addImageDiv = document.createElement("div");
        addImageDiv.style.width = "50px";
        addImageDiv.style.height = "50px";
        addImageDiv.style.textAlign = "center";
        addImageDiv.id = "addImage";
        addImageDiv.classList.add("btn", "btn-primary", "mb-3");
        addImageDiv.textContent = "+";

        // Crear el input de tipo file
        var inputFileImage = document.createElement("input");
        inputFileImage.type = "file";
        inputFileImage.style.display = "none";
        inputFileImage.id = "inputFileImage";

        addImageDiv.addEventListener('click', function () {
            inputFileImage.click();
        });

        inputFileImage.addEventListener('change', function () {
            aniadirImagen(inputFileImage.files[0], tareaYFotos.tarea.id);
        });

        // Crear el botón de submit
        var submitButton = document.createElement("button");
        submitButton.textContent = "Enviar";
        submitButton.classList.add("btn", "btn-primary");

        submitButton.addEventListener('click', function () {
            editarTarea(tareaYFotos.tarea.id);
        });

        // Agregar los elementos al formulario
        formulario.appendChild(inputTexto);
        formulario.appendChild(fotosdiv)
        formulario.appendChild(addImageDiv);
        formulario.appendChild(inputFileImage);
        formulario.appendChild(submitButton);

        // Agregar el formulario al div con clase "tarea"
        tareaDiv.appendChild(formulario);

        // Agregar el div con clase "tarea" al body
        document.body.appendChild(tareaDiv);
    })
    .catch(error => console.log(error))

function editarTarea(id) {
    var texto = document.getElementById('texto').value;

    const datos = new FormData();
    datos.append('id', id);
    datos.append('texto', texto);

    const options = {
        method: 'POST',
        body: datos
    }

    fetch('index.php?accion=editar_tarea', options)
        .then(respuesta => respuesta.json())
        .then(datos => {
            console.log(datos)
        })
        .catch(error => console.log(error))
}

function aniadirImagen(foto, idTarea) {
    const datos = new FormData();
    datos.append('idTarea', idTarea);
    datos.append('foto', foto);

    const options = {
        method: 'POST',
        body: datos
    }

    fetch('index.php?accion=aniadir_foto_carpeta', options)
        .then(respuesta => respuesta.json())
        .then(res => {
            console.log(datos);
            if (res.respuesta == 'ok') {
                let nuevaFoto = document.createElement("img");
                nuevaFoto.setAttribute("src", 'web/fotos/' + res.nombrefoto);
                nuevaFoto.classList.add("img-thumbnail");
                document.getElementById('fotos').append(nuevaFoto);
            }
        })
        .catch(error => console.log(error))
}