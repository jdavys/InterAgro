<?php
include("conexion2.php");
$idFact=$_POST['idFact'];
$consultaFact="SELECT saldo_Factura FROM cuenta_cobrar WHERE id_comprobante ={$idFact} and saldo_factura > 0";
$resFact=$conex->query($consultaFact);

while($facturas=$resFact->fetch_assoc()){
?>
	<input type="text" name="saldoFact" class="form-control" readonly value="<?php echo $facturas['saldo_Factura']; ?>" />
<?php
}
mysqli_close($conex);
?>