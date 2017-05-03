<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php
	obtenerPagina();
?>
<?php include("includes/header.php");?>
		<table id="estructura">
			<tr>
				<td id="menu">
					<?php echo menu($reg_cliente,$reg_fact);?>
				<br />
				</td>
				<td id="pagina">
					<?php if (!is_null($reg_cliente)){ ?>
						<h2> <?php echo $reg_cliente['nombre']; ?></h2></br></br>
						<?php echo "SALDO GLOBAL: <strong>".$reg_cliente['saldo_Global']." </strong>"; ?></br></br>
					<?php }elseif (!is_null($reg_fact)) { ?>
						<?php $cli=obtenerClientePorId($reg_fact[0]); ?>  
						<h2> <?php echo "FACTURA #: FV-".$reg_fact['id_Comprobante']." - ".$reg_fact['estado']." - ".$reg_fact['moneda']." - ".$reg_fact['tipo_pago']; ?></h2>
						<div id="pagina-contenido">
							<?php echo "CLIENTE: <strong>".$cli[1]." </strong> ";?></br></br>
							<?php echo "FECHA DE INICIO DE CREDITO: <strong>".$reg_fact['fecha_Inicio']." </strong> "; ?></br></br>
							<?php echo "FECHA DE VENCIMIENTO DE CREDITO: <strong>".$reg_fact['fecha_Vence']." </strong>"; ?></br></br>
							<?php echo "MONTO INICIAL: <strong>".$reg_fact['monto_Inicial']." </strong>"; ?></br></br>
							<?php echo "MONTO INTERES/MENSUAL: <strong>".$reg_fact['interes']." </strong>"; ?></br></br>
							<?php echo "SALDO FACTURA: <strong>".$reg_fact['saldo_Factura']." </strong>"; ?></br></br>
							</br></br>
							<?php

								$fechaV= date("m-d-y",strtotime($reg_fact['fecha_Vence']));
								$fecha=date("m-d-y");

								$fechaVt=$reg_fact['fecha_Vence'];
								$dias = (strtotime($fechaVt) - strtotime("now")) /86400;;
								$dias 	= abs($dias); 
								$dias = floor($dias);		


									/*$interval = $datetime1->diff($datetime2); $interval->format('%R%a días');*/

								if($reg_fact['tipo_pago']=='Credito'){
									if($fecha > $fechaV){ ?>		
										<h2><center> <?php echo " ALERTA !!! FACTURA VENCIDA...." ?></br> <?php echo $dias. " DÍAS VENCIDOS"; ?> </center></h2>
								<?php } 
								}	?>
								
								
							
						</div>
					<?php }else{ ?>
						<h2>Selecciona algun CLIENTE o FACTURA</h2>
					<?php }?>
					

				</td>
			</tr>
		</table>
<?php include("includes/footer.php");?>
