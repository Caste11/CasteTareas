<?php 

Class ControladorUsuarios{

    public function inicio(){
        if (Sesion::existeSesion()) {
            header('location: index.php?accion=ver_tareas');
            die();
        }

        require 'app/vistas/inicio.php';
    }

    public function registrar(){
        $error='';

        if($_SERVER['REQUEST_METHOD']=='POST'){

            //Limpiamos los datos
            $email = htmlentities($_POST['email']);
            $password = htmlentities($_POST['password']);
            $nombre = htmlspecialchars($_POST['nombre']);

            //Validación 

            //Conectamos con la BD
            $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
            $conn = $connexionDB->getConnexion();

            //Compruebo que no haya un usuario registrado con el mismo email
            $usuariosDAO = new UsuariosDAO($conn);
            if($usuariosDAO->getByEmail($email) != null){
                $error = "Ya hay un usuario con ese email";
            }else{
                if($error == ''){    //Si no hay error
                    //Insertamos en la BD

                    $usuario = new Usuario();
                    $usuario->setEmail($email);
                    //encriptamos el password
                    $passwordCifrado = password_hash($password,PASSWORD_DEFAULT);
                    $usuario->setPassword($passwordCifrado);
                    $usuario->setSid(sha1(rand()+time()), true);
                    $usuario->setNombre($nombre);

                    if($usuariosDAO->insert($usuario)){
                        header("location: index.php");
                        die();
                    }else{
                        $error = "No se ha podido insertar el usuario";
                    }
                }
            }
    
        }   //Acaba if($_SERVER['REQUEST_METHOD']=='POST'){...}

        require 'app/vistas/registrar.php';

    }   // Acaba function registrar()

    public function login(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //limpiamos los datos que vienen del usuario
        $email = htmlspecialchars($_POST['email']);
        $password = htmlspecialchars($_POST['password']);

        //Validamos el usuario
        $usuariosDAO = new UsuariosDAO($conn);
        if($usuario = $usuariosDAO->getByEmail($email)){
            if(password_verify($password, $usuario->getPassword()))
            {
                //email y password correctos. Inciamos sesión
                Sesion::iniciarSesion($usuario);
        
                //Creamos la cookie para que nos recuerde 1 semana
                setcookie('sid',$usuario->getSid(),time()+24*60*60,'/');
                
                header("location: index.php?accion=ver_tareas");
                die();
            }
        }
        //email o password incorrectos, redirigir a index.php
        guardarMensaje("Email o password incorrectos");
        header('location: index.php');

       
    }

    public function logout(){
        Sesion::cerrarSesion();
        setcookie('sid','',0,'/');
        header('location: index.php');
    }

}