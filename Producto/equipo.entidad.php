<?php
class Producto
{
	private $id;
	private $Nombre;
	private $Precio;
	private $Costo;
	private $presentacion;
	private $existencia;
	private $bodega;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}