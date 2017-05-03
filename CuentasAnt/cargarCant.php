<?php
include("conexion2.php");
$idP=$_POST['idPro'];
$idF=$_POST['idFact'];
if($idP>0){
	$consultaProd="SELECT * FROM  comprobante_detalle WHERE Comprobante_id = {$idF} AND Producto_id={$idP}";
	$resProd=$conex->query($consultaProd);?>
	<?php while($prod=$resProd->fetch_assoc()){?>
    	<input type="text" id="cantExi" name="cantExi" class="form-control"  value="<?php echo $prod['Cantidad']; ?>" />
	<?php }
}

mysqli_close($conex);
?>