
let botonInsertar= document.getElementById('botonNuevaTarea');

//document.querySelector('.papelera')

botonInsertar.addEventListener('click',function (){
   //Muestro el preloader
   document.getElementById('preloaderInsertar').style.visibility='visible';

    //Envío datos mediante POST a insertar.php construyendo un FormData
    const datos = new FormData();
    datos.append('texto',document.getElementById('nuevaTarea').value);
    
    const options = {
        method: "POST",
        body: datos
      };
    
    fetch("index.php?accion=insertar_tarea", options)
    .then(respuesta => {
        return respuesta.json();
    })
    .then(tarea => {
        //Añado la la tarea al div "tareas" modificando el DOM
        var capaTarea = document.createElement('div');
        var capaTexto = document.createElement('div');
        var papelera = document.createElement('i');
        var editar  = document.createElement('i');
        var preloader = document.createElement('img');
        capaTarea.classList.add('tarea');
        capaTexto.classList.add('texto');
        capaTexto.innerHTML=tarea.texto;
        papelera.classList.add('fa-solid', 'fa-trash', 'papelera');
        papelera.setAttribute("data-idTarea", tarea.id);
        editar.classList.add('fa-regular', 'fa-pen-to-square','editar');
        editar.setAttribute("data-idTarea", tarea.id);
        preloader.setAttribute('src','web/imagenes/preloader.gif');
        preloader.classList.add('preloaderBorrar');
        capaTarea.appendChild(capaTexto);
        capaTarea.appendChild(papelera);
        capaTarea.appendChild(editar);
        capaTarea.appendChild(preloader);
        document.getElementById('tareas').appendChild(capaTarea);

        //Añadir manejador de evento Borrar a la nueva papelera
        papelera.addEventListener('click',manejadorBorrar);

        //Añadir manejador de evento Editar al nuevo icono de editar
        editar.addEventListener('click',manejadorEditar);

        //Borro el contenido del input
        document.getElementById('nuevaTarea').value='';
    })
    .finally(function(){
        //Ocultamos el preloader
        document.getElementById('preloaderInsertar').style.visibility='hidden';
    });
    
    
});


let papeleras = document.querySelectorAll('.papelera');
papeleras.forEach(papelera => {
    papelera.addEventListener('click',manejadorBorrar);
});

let  ediciones = document.querySelectorAll('.editar');
ediciones.forEach(editar =>{
    editar.addEventListener('click',manejadorEditar);
});

    
function manejadorBorrar(){
    //this referencia al elementos del DOM sobre el que hemos hecho click
    var idTarea = this.getAttribute('data-idTarea');
    //Mostramos preloader
    let preloader = this.parentElement.querySelector('img');
    preloader.style.visibility="visible";
    this.style.visibility='hidden';
    //Llamamos al script del servidor que borra la tarea pasándole el idTarea como parámetro
    fetch('index.php?accion=borrar_tarea&id='+idTarea)
    .then(datos => datos.json())
    .then(respuesta =>{
        if(respuesta.respuesta=='ok'){
            this.parentElement.remove();
        }
        else{
            alert("No se ha encontrado la tarea en el servidor");
            this.style.visibility='visible';
        }
    })
    .finally(function(){
        //Ocultamos preloader
        preloader.style.visibility="hidden";
    });
}

function manejadorEditar(){
    var idTarea = this.getAttribute('data-idTarea');
    fetch('index.php?accion=ver_editar_tarea&id=' + idTarea)
    .then(respuesta => {
        if (!respuesta.redirected) {
            window.location.href = respuesta.url;
        }
    })
}