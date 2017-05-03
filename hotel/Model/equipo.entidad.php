<?php
class Equipo
{
	private $id_equipo;
	private $descripcion;
	private $cantidad;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}