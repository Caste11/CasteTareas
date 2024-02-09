<?php

class TareasDAO {
    private $conexion;

    public function __construct() {
        $this->conexion = new mysqli("localhost", "root", "", "tareas");

        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function obtenerTodasLasTareas() {
        $query = "SELECT * FROM tareas";
        $resultados = $this->conexion->query($query);
        $tareas = array();

        if ($resultados->num_rows > 0) {
            while ($tarea = $resultados->fetch_object(Tarea::class)) {
                $tareas[] = $tarea;
            }
        }

        return $tareas;
    }

    public function obtenerTareaPorID($id) {
        $query = "SELECT * FROM tareas WHERE id = ?";
        $stmt = $this->conexion->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        
        $resultado = $stmt->get_result()->fetch_object(Tareas::class);
        return $resultado;
    }

    public function insertarTarea(Tareas $tarea): int|bool{
        if(!$stmt = $this->conexion->prepare("INSERT INTO tareas (texto, realizado, idUsuario) VALUES (?,?,?)")){
            die("Error al preparar la consulta insert: " . $this->conexion->error );
        }
        $texto = $tarea->getTexto();
        $realizada = $tarea->getRealizado();
        $idUsuario = $tarea->getIdUsuario();
        $stmt->bind_param('sii', $texto, $realizada, $idUsuario);
        if($stmt->execute()){
            return $stmt->insert_id;
        }
        else{
            return false;
        }
    }

    public function obtenerTareaPorIdUsuario($idUsuario) {
        if (!$stmt = $this->conexion->prepare("SELECT * FROM tareas WHERE idUsuario = ?")) {
            echo "Error en la SQL: " . $this->conexion->error;
           }
           //Asociar las variables a las interrogaciones (parámetros)
           $stmt->bind_param('i',$idUsuario);
           //Ejecutamos la SQL
           $stmt->execute();
           //Obtener el objeto mysql_result
           $result = $stmt->get_result();
      
          $array_tareas = array();
      
          while($tarea = $result->fetch_object(Tareas::class)){
               $array_tareas[] = $tarea;
          }
          return $array_tareas;
    }

    public function cerrarConexion() {
        $this->conexion->close();
    }


    public function borrarTarea($id) {
        if(!$stmt = $this->conexion->prepare("DELETE FROM tareas WHERE id = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i',$id);
        //Ejecutamos la SQL
        $stmt->execute();
        //Comprobamos si ha borrado algún registro o no
        if($stmt->affected_rows==1){
            return true;
        }
        else{
            return false;
        }
    }

    public function update($texto, $id):bool {
        if (!$stmt = $this->conexion->prepare("UPDATE tareas SET texto = ? WHERE id = ?")) {
            die("Error al preparar la consulta insert: " . $this->conexion->error);
        }

        $stmt->bind_param('si', $texto, $id);

        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>
