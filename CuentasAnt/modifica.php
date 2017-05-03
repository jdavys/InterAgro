<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php

//action="modifica.php?idab=<?php echo urlencode($abono['id_abono'])

$fec=date('d-m-y');
$orden1="SELECT id,name FROM person, cuenta_cobrar
            where id=id_cliente and saldo_global > 0 group by name ";
$paquete1=mysql_query($orden1);
?>
<?php
	$idAb=$_GET['idab'];
    $abono=obtenetAbonoPorId($idAb);
    obtenerPagina();
    
    /* if(intval($_POST['idab'])==0){  //transformar valor a entero (si no puede devuelbe 0)
        header("Location: content.php");
        exit;
    }*/
    if(isset($_POST['NomCliente'])){
        $errores=array();
        validarCamposObli(array('NomCliente','fact','montoAbono'),$errores);

        if(empty($errores)){
                
                $cliente   = preparar_consulta(htmlentities($_POST['idclie'],ENT_QUOTES,"UTF-8"));
                $factura   = preparar_consulta(htmlentities($_POST['fact'],ENT_QUOTES,"UTF-8"));
                $monto = preparar_consulta(htmlentities($_POST['montoAbono'],ENT_QUOTES,"UTF-8"));

                $consulta="UPDATE abono set comprobante_id={$factura}, cliente_id={$cliente},monto={$monto}
                            WHERE id_abono={$idAb}";

                $result=mysql_query($consulta,$conexion);
                if(mysql_affected_rows()==1){//cuantas filas fueron actualizadas  165750.00
                    //funciono
                }else{
                    //error
                }
                header("Location: edit_abono.php?clie={$cliente}");
        }


    }
    
   
?>

<?php include("includes/header.php");?>
                
		<table id="estructura">
			<tr>
				<td id="menu">
					<?php echo menu($reg_cliente,$reg_fact);?>
				<br />

				</td>
				<td id="pagina">
					<h2><?php echo $abono['name']; ?> </h2></br>
                                        <form action="#" method="post">
                                            <table style="width:500px;">
                                            <tr>
                                                <th style="text-align:left;">Cliente</th>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="hidden" name="idab" id="idab"  value = "<?php echo $abono['id_abono']; ?>">
                                                        <input type = "hidden" name="idclie" id="idclie"  value = "<?php echo $abono['id']; ?>">
                                                        <input type="text" readonly="true" name="NomCliente" id="NomCliente"  value = "<?php echo $abono['name']; ?>" style="width:80%" 
                                                        class="form-control">
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">Factura</th>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" readonly="true" name="fact" id="fact" value = "<?php echo $abono['comprobante_id']; ?>" style="width:30%" class="form-control">
                                                    </div>
                                                </td>
                                            </tr>
                                            
                                            <tr>
                                                <th style="text-align:left;">Monto a abonar</th>
                                                <td>
                                                    <div class="form-group">
                                                        <input type="text" readonly="true" name="montoAbono" value = "<?php echo $abono['monto']; ?>" style="width:50%"  class="form-control"/>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="text-align:left;">DETALLE: </th>
                                                <td>
                                                    <div class="form-group">
                                                        <textarea readonly="true" name="deta" style="width:50%"  class="form-control"><?php echo $abono['detalle']; ?></textarea>
                                                        
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="2">
                                                   <div class="form-group"><button type="submit" class="btn btn-success">Atras</button></div>

                                                </td>
                                            </tr>
                                        </table>

                                        </form>


					
				</td>
			</tr>
		</table>
<?php include("includes/footer.php");?>