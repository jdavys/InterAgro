<?php
class CuentaC
{
	private $cuenta_id;
	private $id_Cliente;
	private $id_Comprobante;
	private $fecha_Inicio;
	private $monto_Inicial;
	
	public function __GET($k){ return $this->$k; }
	public function __SET($k, $v){ return $this->$k = $v; }
}