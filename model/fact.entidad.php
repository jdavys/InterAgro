<?php
class Factura
{
	private $cliente;
	private $factura;
	private $tipoPago;
	private $moneda;
	private $fecha;
	private $fechaVence;
	private $subTotal;
	private $descuento;
	private $total;

	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}