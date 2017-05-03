<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php

$errores = array();
$campos_obli = array('cliente', 'factura','montoAbono');

validarCamposObli($campos_obli,$errores);

if(!empty($errores)){
	header("Location: new_credito.php");
	exit;
}

?>

<?php
$cliente   = preparar_consulta(htmlentities($_POST['cliente'],ENT_QUOTES,"UTF-8"));
$factura   = preparar_consulta(htmlentities($_POST['factura'],ENT_QUOTES,"UTF-8"));
$monto = preparar_consulta(htmlentities($_POST['montoAbono'],ENT_QUOTES,"UTF-8"));
$tipo = preparar_consulta(htmlentities($_POST['tipoAbono'],ENT_QUOTES,"UTF-8"));//1	DESCUENTO,2	DEVOLUCIÓN,3 ANULACIÓN,4	PAGO,5	OTRO
$deta = preparar_consulta(htmlentities($_POST['detalle'],ENT_QUOTES,"UTF-8"));
$fecha = preparar_consulta(htmlentities($_POST['fecha'],ENT_QUOTES,"UTF-8"));
if(isset($_POST['producto'])){
	$producto = preparar_consulta(htmlentities($_POST['producto'],ENT_QUOTES,"UTF-8"));	
}

if(isset($_POST['cantExi'])){
	$canti=preparar_consulta(htmlentities($_POST['cantExi'],ENT_QUOTES,"UTF-8"));
}else{
	$canti=0;	
}


date_default_timezone_set('America/Costa_Rica');

$fec=date('d-m-Y');

if($tipo==2){
	 
	$consultaF= "SELECT * from cuenta_cobrar where id_comprobante={$factura}";
		
	if(mysql_query($consultaF,$conexion)){
		$respF=mysql_query($consultaF,$conexion);
		$cuentaF=mysql_fetch_array($respF);
		$saldoF=$cuentaF['saldo_Factura']-$monto;
		$modiF="UPDATE cuenta_cobrar set saldo_Factura={$saldoF} where id_comprobante={$factura}";
		
		//var_dump($cuentaF);
		if(mysql_query($modiF)){
			if($cuentaF['moneda']=='COLONES'){
				$saldoGL=$cuentaF['saldo_Global']-$monto;
				$modiG="UPDATE cuenta_cobrar set saldo_Global={$saldoGL} where id_cliente={$cliente}";
				mysql_query($modiG);
			}else{
				$saldoGL=$cuentaF['saldo_global_dolares']-$monto;
				$modiG="UPDATE cuenta_cobrar set saldo_global_dolares={$saldoGL} where id_cliente={$cliente}";
				mysql_query($modiG);
			}
		}
		//var_dump($producto);
		if($producto>0){
			$consulP="SELECT * from product where id={$producto}";
			$consulDetP="SELECT * from comprobante_detalle where Producto_id={$producto} AND comprobante_id={$factura}";
			if(mysql_query($consulP)){
				$respP=mysql_query($consulP);
				$pro=mysql_fetch_array($respP);
				$cantE=$pro['cantidadExiste'];
				$cantS=$pro['cantidadSobre'];
				$deta=mysql_query($consulDetP);
				$detP=mysql_fetch_array($deta);
				$cantDP=$detP['Cantidad']-$canti;
				if($cantE>=0 ){  //&& $cantS==0
					$cantN=$cantE+$canti;
					
					$modiEstaC="UPDATE product set cantidadExiste={$cantN} where id={$producto}";
					mysql_query($modiEstaC,$conexion);
					$modiEstaD="UPDATE comprobante_detalle set Cantidad={$cantDP} where Producto_id={$producto} AND comprobante_id={$factura}";
					mysql_query($modiEstaD,$conexion);
				
				}else{
					
					$cantSo=$cantE-$canti;
					$modiEstaC="UPDATE producto set cantidadExiste={$cantSo} where id={$producto}";
					mysql_query($modiEstaC,$conexion);
					mysql_query($modiEstaC,$conexion);
					$modiEstaD="UPDATE comprobante_detalle set Cantidad={$cantDP} where Producto_id={$producto} AND comprobante_id={$factura}";
					mysql_query($modiEstaD,$conexion);
				}
			}
		}
	}
	
}


if($tipo==3){

	$saldoFact = preparar_consulta(htmlentities($_POST['saldoFact'],ENT_QUOTES,"UTF-8"));

	$consulta="INSERT INTO abono (comprobante_id,cliente_id,monto,fecha,id_tipo_credito,detalle)
	values({$factura},{$cliente},{$saldoFact},'{$fecha}',{$tipo},'{$deta}')";
	mysql_query($consulta,$conexion);


	$modiEstaP="UPDATE prefactura set estado='ANULADA' where id={$factura}";
	mysql_query($modiEstaP,$conexion);
	$modiEstaC="UPDATE Comprobante set estado='ANULADO' where id={$factura}";
	mysql_query($modiEstaC,$conexion);

	$consulM="SELECT * from cuenta_cobrar where id_comprobante={$factura}";
	$respM=mysql_query($consulM,$conexion);
	$cuenta = mysql_fetch_array($respM);
	$tipM=$cuenta['moneda'];
	$tipP=$cuenta['tipo_pago'];
	$sf=$cuenta['saldo_Factura'];
	$sgt=0;

	if($tipP=='Credito'){
		if($tipM=='COLONES'){
			$sg=$cuenta['saldo_global'];
			$sgt = $sg - $sf;
			$modiEstaCr="UPDATE cuenta_cobrar SET saldo_global={$sgt} where id_cliente={$cliente}" ;	
			mysql_query($modiEstaCr,$conexion);
		}else{
			$sg=$cuenta['saldo_global_dolares'];
			$sgt=$sg-$saldoFact;
			$modiEstaCr="UPDATE cuenta_cobrar SET saldo_global_dolares={$sgt} where id_cliente={$cliente}" ;	
			mysql_query($modiEstaCr,$conexion);
		}

		$modiEstaCr="UPDATE cuenta_cobrar SET estado='ANULADO',saldo_Factura=0 where id_comprobante={$factura}" ;	
		mysql_query($modiEstaCr,$conexion);

		$consultaI= "SELECT * from comprobante_detalle where comprobante_id={$factura}";
		if(mysql_query($consultaI,$conexion)){
			
			$respI=mysql_query($consultaI,$conexion);
			while($cuentaI = mysql_fetch_array($respI)){
				$cantDet=$cuentaI['Cantidad'];
				$prodId=$cuentaI['Producto_id'];
				
				$consulP="SELECT * from Producto where id={$prodId}";
				if(mysql_query($consulP,$conexion)){

					$respP=mysql_query($consulP,$conexion);
					$prod=mysql_fetch_array($respP);
					$cantS=$prod['cantidadSobre'];
					$cantE=$prod['cantidadExiste'];
					if($cantE <= 0){
						$modiP="UPDATE producto set cantidadSobre = {$cantS}-{$cantDet}  where id={$prodId} ";
						if(mysql_query($modiP,$conexion)){
							echo "<strong>LA ANULACION SE REALIZO EXITOSAMENTE </strong></br></br>";
							echo "<a class='btn btn-primary pull-left btn-lg' href='content.php'><h3>REGRESAR</h3></a>";
						}
					}else{
						$modiP="UPDATE producto set cantidadExiste = {$cantE}+{$cantDet}  where id={$prodId} ";
						if(mysql_query($modiP,$conexion)){
							echo "<strong>LA ANULACION SE REALIZO EXITOSAMENTE </strong></br></br>";
							echo "<a class='btn btn-primary pull-left btn-lg' href='content.php'><h3>REGRESAR</h3></a>";
						}
					}
				}
			}
		}
	}else{
		$modiEstaCr="UPDATE cuenta_cobrar SET estado='ANULADO',saldo_Factura=0 where id_comprobante={$factura}" ;	
		mysql_query($modiEstaCr,$conexion);

		$consultaI= "SELECT * from comprobante_detalle where comprobante_id={$factura}";
		if(mysql_query($consultaI,$conexion)){
			
			$respI=mysql_query($consultaI,$conexion);
			while($cuentaI = mysql_fetch_array($respI)){
				$cantDet=$cuentaI['Cantidad'];
				$prodId=$cuentaI['Producto_id'];
				
				$consulP="SELECT * from Product where id={$prodId}";
				if(mysql_query($consulP,$conexion)){

					$respP=mysql_query($consulP,$conexion);
					$prod=mysql_fetch_array($respP);
					$cantS=$prod['cantidadSobre'];
					$cantE=$prod['cantidadExiste'];
					if($cantE <= 0){
						$modiP="UPDATE producto set cantidadSobre = {$cantS}-{$cantDet}  where id={$prodId} ";
						if(mysql_query($modiP,$conexion)){
							echo "<strong>LA ANULACION SE REALIZO EXITOSAMENTE </strong></br></br>";
							echo "<a class='btn btn-primary pull-left btn-lg' href='content.php'><h3>REGRESAR</h3></a>";
						}
					}else{
						$modiP="UPDATE producto set cantidadExiste = {$cantE}+{$cantDet}  where id={$prodId} ";
						if(mysql_query($modiP,$conexion)){
							echo "<strong>LA ANULACION SE REALIZO EXITOSAMENTE </strong></br></br>";
							echo "<a class='btn btn-primary pull-left btn-lg' href='content.php'><h3>REGRESAR</h3></a>";
						}
					}
				}
			}
		}

		echo "<strong>LA ANULACION SE REALIZO EXITOSAMENTE </strong></br></br>";
		echo "<a class='btn btn-primary pull-left btn-lg' href='content.php'><h3>REGRESAR</h3></a>";
	}



}

if($tipo==4){

	$saldoFact = preparar_consulta(htmlentities($_POST['saldoFact'],ENT_QUOTES,"UTF-8"));	
	
	$recibo= "SELECT * from cuenta_cobrar where id_comprobante={$factura}";

	if (mysql_query($recibo,$conexion)){
		$respuesta=mysql_query($recibo,$conexion);
		$cuenta = mysql_fetch_array($respuesta);
		//$fechaM=date_format($fecha,'%d-%m-%Y');
		if($monto <= $saldoFact && $monto>0){
			$consulta="INSERT INTO abono (comprobante_id,cliente_id,monto,fecha,id_tipo_credito,detalle)
			values({$factura},{$cliente},{$monto},'{$fecha}',{$tipo},'{$deta}')";

			if (mysql_query($consulta,$conexion)){

					$saldoF=$saldoFact - $monto;
					$tPago=$cuenta['tipo_pago'];
					$mone=$cuenta['moneda'];


					if($saldoF==0){


						$modiEstado="UPDATE cuenta_cobrar SET estado='CANCELADO',saldo_Factura=0 where id_comprobante={$factura}" ;	
						mysql_query($modiEstado,$conexion);
		              
						
					}else{

						$iSQL2="UPDATE cuenta_cobrar SET saldo_Factura = {$saldoF}
						            WHERE  id_comprobante={$factura}";
						            	
						mysql_query($iSQL2,$conexion);
					}

					if($tPago=='Credito'){
							if($mone=='DOLARES'){
									$saldoG=0;
									$global= "SELECT * from cuenta_cobrar where id_cliente= {$cliente} and tipo_pago='Credito' and estado='PENDIENTE'and moneda='DOLARES'";
									if (mysql_query($global,$conexion)){
										$respuesta=mysql_query($global,$conexion);
							           	while($cuentaD = mysql_fetch_array($respuesta)){
							           		$saldoG+=$cuentaD['saldo_Factura'];
							           	}

										$iSQL2="UPDATE cuenta_cobrar SET saldo_global_dolares = {$saldoG}
							                		WHERE  id_cliente= {$cliente}";
							            	
							            if(mysql_query($iSQL2,$conexion)){
							        		header("location: edit_abono.php?clie={$cliente}");
											exit();
							       		}else{
											echo "ABONO NO realizado D! ".mysql_error();
										}
						            }
							}

							if($mone=='COLONES'){
									$saldoG=0;
									$global= "SELECT * from cuenta_cobrar where id_cliente= {$cliente} and tipo_pago='Credito' and estado='PENDIENTE'and moneda='COLONES'";
									if (mysql_query($global,$conexion)){
										$respuesta=mysql_query($global,$conexion);
							           	while($cuenta = mysql_fetch_array($respuesta)){
							           		$saldoG+=$cuenta['saldo_Factura'];
							           	}
										$iSQL2="UPDATE cuenta_cobrar SET saldo_global= {$saldoG}
							               		WHERE  id_cliente= {$cliente}";
							            if(mysql_query($iSQL2,$conexion)){
							        		header("location: edit_abono.php?clie={$cliente}");
											exit();
							       		}else{
											echo "ABONO NO realizado C! ".mysql_error();
										}	            	
							        }	            
							}
					}else{

						$iSQL2="UPDATE cuenta_cobrar SET saldo_factura = {$saldoF}
							WHERE  id_comprobante={$factura}";
						if(mysql_query($iSQL2,$conexion)){
							//echo "<script> alert('ABONO NO realizado MONTO INCORRECTO!'); </script>";
			        		header("location: edit_abono.php?clie={$cliente}");
							exit();
			       		}else{
							echo "ABONO NO realizado S! ".mysql_error();
						}	 						
									
					}
					
			}else{
				echo "ABONO NO realizado I! ".mysql_error();
			}
		}else{
			echo "<script> alert('ABONO NO realizado MONTO INCORRECTO!'); </script>";
			
		}
	}else{
		echo "<script> alert('ABONO NO REALIZADO'); </script> ";
	}
}	

mysql_close($conexion);

?>
