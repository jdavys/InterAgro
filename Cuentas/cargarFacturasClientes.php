<?php
include("conexion.php");
$idCli=$_POST['idCli'];
$consultaFact="SELECT * FROM cuenta_cobrar WHERE id_cliente ={$idCli} and saldo_factura > 0";
$resFact=$conex->query($consultaFact);?>
<option value=" " selected>Facturas</option>
<?php while($facturas=$resFact->fetch_assoc()){
?>
    
	<option value="<?php echo $facturas['id_Comprobante']; ?>"><?php echo $facturas['id_Comprobante']; ?></option>
<?php
}
mysqli_close($conex);
?>
