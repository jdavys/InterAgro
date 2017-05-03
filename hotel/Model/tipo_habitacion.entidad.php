<?php
class Tipo_habitacion
{
	private $id_tipo_hab;
	private $descripcion;
	
	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}