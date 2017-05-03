<?php
class Extra
{
	private $id_extra;
	private $descripcion;
	private $precio;
	private $estado;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}