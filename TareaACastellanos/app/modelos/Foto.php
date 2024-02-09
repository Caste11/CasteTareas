<?php

class Foto{
    private $nombreFoto;
    private $idTarea;

    /**
     * Get the value of nombreFoto
     */
    public function getNombreFoto() {
        return $this->nombreFoto;
    }

    /**
     * Set the value of nombreFoto
     */
    public function setNombreFoto($nombreFoto): self {
        $this->nombreFoto = $nombreFoto;
        return $this;
    }

    /**
     * Get the value of idTarea
     */
    public function getIdTarea() {
        return $this->idTarea;
    }

    /**
     * Set the value of idTarea
     */
    public function setIdTarea($idTarea): self {
        $this->idTarea = $idTarea;
        return $this;
    }
}