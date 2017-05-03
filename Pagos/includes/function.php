<?php 
 function verificarConsulta($consulta){
 	
 	if(!$consulta){
		die("Error en la consulta: ".mysql_error());
	}
 }

 function preparar_consulta($consulta){

 	$mq_activado=get_magic_quotes_gpc();
 	if(function_exists("mysql_real_escape_string")){
 		if($mq_activado){
 			$consulta=stripslashes($consulta);
 		}
 		$consulta=mysql_real_escape_string($consulta);
 	}else{
 		if(!$mq_activado){
 			$consulta=addslashes($consulta);
 		}
 	}
 	return $consulta;
 }

function obtenerClientes(){
	global $conexion;
	$consulta = "SELECT c.id,c.name 
				from person c, cuenta_cobrar f 
				where c.id=f.id_Cliente and saldo_factura > 0 /*and f.estado='PENDIENTE'*/
				group by c.name
				order by c.name asc";

	$cli=mysql_query($consulta,$conexion);
	verificarConsulta($cli);
	return $cli;
}

function obtenerCuentas(){
	global $conexion;
	$consulta = "SELECT * 
				from cuenta_cobrar";

	$cue=mysql_query($consulta,$conexion);
	verificarConsulta($cue);
	return $cue;
}


function obtenerAbonos($cliente_id){
	global $conexion;
	global $abo;
	$consulta = "SELECT id_abono,person.id,comprobante_id,name,monto,fecha,detalle
				from abono, person
				where abono.cliente_id={$cliente_id} and person.id=abono.cliente_id and estado=1";

	$abo=mysql_query($consulta,$conexion);
	verificarConsulta($abo);
	return $abo;
}

function obtenerCredito($cliente){
	global $conexion;
	$consulta = "SELECT * 
				FROM cuenta_cobrar 
				WHERE id_Cliente={$cliente} and saldo_factura > 0 AND estado='PENDIENTE' 
				ORDER BY id_Comprobante asc";

	$cuenta=mysql_query($consulta,$conexion);
	verificarConsulta($cuenta);
	return $cuenta;

}

function obtenerClientePorId($cliente_id){
	global $conexion;
	$consulta = "SELECT id,name,saldo_Global,moneda,saldo_global_dolares
				FROM person c , cuenta_cobrar f
				WHERE id={$cliente_id} and id=id_cliente Limit 1";

	$respuesta=mysql_query($consulta,$conexion);
	verificarConsulta($respuesta);
	
	if($cliente = mysql_fetch_array($respuesta)){//si no encuentra registro devuelve FALSE;
		return $cliente;
	}else{
		return $cliente;
	}
	
}



function obtenetAbonoPorId($id_abo){
	global $conexion;
	$consulta = "SELECT id_abono,id,comprobante_id,name,monto,detalle
				FROM abono, person
				WHERE id_abono={$id_abo} and abono.cliente_id=person.id Limit 1";

	$respuesta=mysql_query($consulta,$conexion);
	verificarConsulta($respuesta);
	if($abono = mysql_fetch_array($respuesta)){//si no encuentra registro devuelve FALSE;
		return $abono;
	}else{
		return $abono;
	}

}


function validarCamposObli($campos_obli,$errores){
	foreach ($campos_obli as $campo) {
		if(!isset($_POST[$campo]) || empty($_POST[$campo]) && !is_numeric($_POST[$campo])){
			$errores[]=$campo;//CORCHETES VACIOS AGREGANDO AL FINAL DE LA MATRIZ;
		}
	}
}

function obtenerFacturaPorId($fact_id){
	global $conexion;
	$consulta = "SELECT * 
				FROM cuenta_cobrar 
				WHERE id_Comprobante={$fact_id}  and saldo_factura>0 Limit 1";

	$respuesta=mysql_query($consulta,$conexion);
	verificarConsulta($respuesta);
	if($factura = mysql_fetch_array($respuesta)){//si no encuentra registro devuelve FALSE;
		return $factura;
	}else{
		return $factura;
	}
	
}

function obtenerPagina(){
	global $reg_abono;
	global $reg_cliente;
	global $reg_fact;
	
	if(isset($_GET["clie"])){
		$reg_cliente = obtenerClientePorId($_GET["clie"]); //array ASOCIATIVO;
		$reg_abono=obtenerAbonos($_GET["clie"]);
		$reg_fact=NULL;
		
	}elseif(isset($_GET["fact"])){
		$reg_fact=obtenerFacturaPorId($_GET["fact"]);
		$reg_cliente=NULL;
		$reg_abono=NULL;
	}else{
		$reg_fact=NULL;
		$reg_cliente=NULL;
		$reg_abono=NULL;
	}
}

function menu($reg_clienteC,$reg_fact){
	$salida= "";
	if($reg_clienteC!=NULL){
		$salida= "<ul class=\"clientes\">";
		$cli=obtenerClientePorId($reg_clienteC[0]);

			while($rowCli=mysql_fetch_array($cli)){
				$salida.= "<li";
				if($rowCli[0]==$reg_clienteC[0]){
					$salida.= " class=\"selected\"";
				}
				$salida.= "><a href=\"edit_abono.php?clie=".urlencode($rowCli[0])."\" >".$rowCli[1]."</a></li><ul class='creditos'>";
							
				$cuenta=obtenerCredito($rowCli[0]);
				while($rowCuen=mysql_fetch_array($cuenta)){
					$salida.= "<li";
					if($rowCuen[1]==$reg_fact[0]){
						$salida.= " class=\"selected\"";
					}
					$salida.= "><a href=\"content.php?fact=".urlencode($rowCuen[1])."\" >"."FV-".$rowCuen[1]."</a></li>";
					}
					$salida.= "</ul>";
				}				
						
		$salida.= "</ul>";
	}
	
				
	
	return $salida;
}


?>