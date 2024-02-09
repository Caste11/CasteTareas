<?php 

class ControladorTareas{
    public function ver(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $tareasDAO = new TareasDAO($conn);
        $tareas = $tareasDAO->obtenerTareaPorIdUsuario(Sesion::getUsuario()->getId());

        require 'app/vistas/verTareas.php';
    }


    public function borrar(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Creamos el objeto TareasDAO para acceder a BBDD a través de este objeto
        $tareasDAO = new TareasDAO($conn);

        //Obtener el mensaje
        $idTarea = htmlspecialchars($_GET['id']);
        $tarea = $tareasDAO->obtenerTareaPorID($idTarea);

        //Comprobamos que mensaje pertenece al usuario conectado
        if($tarea = $tareasDAO->borrarTarea($idTarea)){
            print json_encode(['respuesta'=>'ok']);
        }else{
            print json_encode(['respuesta'=>'error', 'mensaje'=>'Tarea no encontrada']);
        }
        sleep(1);

    }

    public function editar(){
        $error ='';

        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $tareasDAO = new TareasDAO($conn);

        if ($_SERVER['REQUEST_METHOD']=='POST') {

            //Limpiamos los datos que vienen del usuario
            $idTarea = htmlspecialchars($_POST['id']);
            $texto = htmlspecialchars($_POST['texto']);

            //Validamos los datos
            if(empty($texto)){
                print json_encode(['resultado' => 'El campo de texto es obligatorio']);
            } else {
                //Creamos el objeto MensajesDAO para acceder a BBDD a través de este objeto
                $tareasDAO->update($texto, $idTarea);
                print json_encode(['resultado' => 'Modificación correcta']);
            }
        }
    }

    public function ver_editar(){
        if ($_SERVER['REQUEST_METHOD']=='GET') {
            require 'app/vistas/editar_tarea.php';
        } else {
            $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
            $conn = $connexionDB->getConnexion();
    
            $tareasDAO = new TareasDAO($conn);
            $fotosDAO = new FotoDAO($conn);
    
            $id = htmlentities($_POST['id']);
            $tarea = $tareasDAO->obtenerTareaPorID($id);

            $fotos = $fotosDAO->getAllByIdTarea($id);

            $data = array();

            $tareaArray = array('id' => $tarea->getId(), 'texto' => $tarea->getTexto(), 'fecha' => $tarea->getFecha(), 'realizado' => $tarea->getRealizado(), 'idusuario' => $tarea->getIdUsuario());
            $data['tarea'] = $tareaArray;

            $arrayFotos = array();

            foreach ($fotos as $i => $foto) {
                $arrayFotos[] = array('nombrefoto' => $foto->getNombreFoto(), 'idtarea' => $foto->getIdTarea());
            }
            $data['fotos'] = $arrayFotos;

            print json_encode($data);
        }
    }

    public function insertar(){
        
        $error ='';

        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $tareasDAO = new TareasDAO($conn);
        $tareas = $tareasDAO->obtenerTareaPorID(Sesion::getUsuario()->getId());


        if($_SERVER['REQUEST_METHOD']=='POST'){

            //Limpiamos los datos que vienen del usuario
            $texto = htmlspecialchars($_POST['texto']);

            //Validamos los datos
            if(empty($texto)){
                $error = "Los dos campos son obligatorios";
            }
            else{
                //Creamos el objeto MensajesDAO para acceder a BBDD a través de este objeto
                $tareas = new Tareas();
                $tareas->setTexto($texto);
                $tareas->setRealizado(false);
                $tareas->setIdUsuario(Sesion::getUsuario()->getId()); //El id del usuario conectado (en la sesión)
                
                $tarea = $tareasDAO->obtenerTareaPorID($tareasDAO->insertarTarea($tareas));
                print $tarea->toJSON();
                sleep(1);
            }
        }
    }

    function aniadirFoto() {
        $idTarea = htmlentities($_POST['idTarea']);
        $nombreFoto = htmlentities($_FILES['foto']['name']);
        $informacionPath = pathinfo($nombreFoto);
        $extension = $informacionPath['extension'];
        $nombreFoto = md5(time()+rand()) . '.' . $extension;
        move_uploaded_file($_FILES['foto']['tmp_name'],"web/fotos/$nombreFoto");

        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $fotosDAO = new FotoDAO($conn);
        $foto = new Foto();
        $foto->setNombreFoto($nombreFoto);
        $foto->setIdTarea($idTarea);
        $fotosDAO->insert($foto);

        print json_encode(['respuesta' => 'ok', 'nombrefoto' => $nombreFoto]);
    }

    function deleteImageMensaje(){
        $idFoto = htmlentities($_GET['idFoto']);
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $fotosDAO = new FotosDAO($conn);
        $foto = new Foto();
        $foto->setId($idFoto);
        if($fotosDAO->delete($foto))
        {
            print json_encode(['respuesta'=>'ok']);
        }
        else{
            print json_encode(['respuesta'=>'error', 'mensaje'=>'No se ha encontrado la foto']);
        }
    }
}