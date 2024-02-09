<?php 

class FotoDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert($foto){
        if(!$stmt = $this->conn->prepare("INSERT INTO foto (nombreFoto, idTarea) VALUES (?,?)")){
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }

        $nombrefoto = $foto->getNombreFoto();
        $idTarea = $foto->getIdTarea();

        $stmt->bind_param('si', $nombrefoto, $idTarea);
        
        if($stmt->execute()){
            return $stmt->insert_id;
        }
        else{
            return false;
        }
    }

    /**
     * 
     */
    public function delete($foto){
        if(!$stmt = $this->conn->prepare("DELETE FROM fotos WHERE id = ?")){
            die("Error al preparar la consulta delete: " . $this->conn->error );
        }
        $id = $foto->getId();
        $stmt->bind_param('i',$id);
        $stmt->execute();
        if($stmt->affected_rows >=1 ){
            return true;
        }
        else{
            return false;
        }
    }

    public function getAllByIdTarea($idTarea):array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM foto WHERE idTarea = ?")){
            die("Error al preparar la consulta delete: " . $this->conn->error );
        }
        $stmt->bind_param('i', $idTarea);
        $stmt->execute();
        $result = $stmt->get_result();
        $fotos = array();
        while($foto = $result->fetch_object(Foto::class)){
            $fotos[] = $foto;
        }
        return $fotos;
    }

}

