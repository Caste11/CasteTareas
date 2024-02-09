<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="web/css/estilos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Página donde se ven todas las tareas</title>
</head>
<body id="verTareas">
    <h1 class="text-center text-white mt-5">Bienvenido <?= Sesion::getUsuario()->getNombre() ?> a la página de tus tareas <a href="index.php?accion=logout" class="btn btn-danger">cerrar sesión</a>
</h1>
    
    <div id="tareas">
        <?php foreach ($tareas as $tarea): ?>
            <div class="tarea">
                <div class="texto"><?= $tarea->getTexto() ?></div>
                <i class="fa-solid fa-trash papelera" data-idTarea="<?= $tarea->getId()?>"></i>
                <i class="fa-regular fa-pen-to-square color_gris editar" data-idTarea="<?= $tarea->getId()?>"></i>
                <img src="web/imagenes/preloader.gif" class="preloaderBorrar">
            </div>
        <?php endforeach; ?>
    </div>

    <input type="text" id="nuevaTarea">
    <button id="botonNuevaTarea">Enviar</button><img src="web/imagenes/preloader.gif" id="preloaderInsertar">

    <script src="web/js.js" type="text/javascript"></script>

</body>
</html>