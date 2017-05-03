<?php
class Habitacion
{
	private $id_habitacion;
	private $id_tipo_hab;
	private $descripcion;
	private $precio;
	/*private $estado;
	private $estado_res;*/

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}