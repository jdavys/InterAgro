<?php 
require_once("includes/conection_db.php");
require_once("includes/function.php");


$cliente_id=$_POST['nom'];

function obtenerClientePorNom($cliente_id){
	global $conexion;
	$consulta = "SELECT id FROM person c WHERE name='{$cliente_id}'";

	$respuesta=mysql_query($consulta,$conexion);
	verificarConsulta($respuesta);
	
	if($cliente = mysql_fetch_array($respuesta)){//si no encuentra registro devuelve FALSE;
		return $cliente['id'];
	}else{
		return $cliente['id'];
	}
	
}

$clienteC = obtenerClientePorNom($cliente_id);
$fact=0;
//var_dump($clienteC);


function menu1($reg_clienteC,$reg_fact){

	$salida= "<ul class=\"clientes\">";

	$cli=obtenerClientePorId($reg_clienteC);
	
	if($cli){
		
			$salida.= "<li";
			if($cli[0]==$reg_clienteC){
				$salida.= " class=\"selected\"";
			}
			$salida.= "><a href=\"edit_abono.php?clie=".urlencode($cli[0])."\" >".$cli[1]."</a></li><ul class='creditos'>";
						
			$cuenta=obtenerCredito($reg_clienteC);
			
			if($cuenta){//si no encuentra registro devuelve FALSE;
				while ( $c = mysql_fetch_array($cuenta)) {
					
					
					$salida.= "<li";
					if($c[1]==$reg_fact){
						$salida.= " class=\"selected\"";
					}
					$salida.= "><a href=\"content.php?fact=".urlencode($c[1])."\" >"."FV-".$c[1]."</a></li>";
				}					
				$salida.= "</ul>";
								
					
			}else {
				$salida.= "<li>SIN FACTURAS PENDIENTES</li></ul>";
			}
		
	}else{
		$salida.= "<li>Cliente sin cuentas pendientes</li>";
	}			
						
	$salida.= "</ul>";
				
	
	return $salida;
}

echo menu1($clienteC,$fact);
?>
