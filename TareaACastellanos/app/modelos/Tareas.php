<?php 
class Tareas {
    private $id;
    private $texto;
    private $fecha;
    private $realizado;
    private $idUsuario;
    

    /**
     * Get the value of id
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set the value of id
     */
    public function setId($id): self {
        $this->id = $id;
        return $this;
    }

    /**
     * Get the value of texto
     */
    public function getTexto() {
        return $this->texto;
    }

    /**
     * Set the value of texto
     */
    public function setTexto($texto): self {
        $this->texto = $texto;
        return $this;
    }

    /**
     * Get the value of fecha
     */
    public function getFecha() {
        return $this->fecha;
    }

    /**
     * Set the value of fecha
     */
    public function setFecha($fecha): self {
        $this->fecha = $fecha;
        return $this;
    }

    /**
     * Get the value of realizado
     */
    public function getRealizado() {
        return $this->realizado;
    }

    /**
     * Set the value of realizado
     */
    public function setRealizado($realizado): self {
        $this->realizado = $realizado;
        return $this;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario() {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     */
    public function setIdUsuario($idUsuario): self {
        $this->idUsuario = $idUsuario;
        return $this;
    }

    public function toJSON(){
        return json_encode(
                ['id' => $this->getId(),
                'texto' => $this->getTexto(),
                'fecha' => $this->getFecha(),
                'idUsuario' => $this->getIdUsuario()]
        );
    }
}
?>