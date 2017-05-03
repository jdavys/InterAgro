<?php
include("conexion2.php");
$idFa=$_POST['idF'];
$consultaProd="SELECT producto_id,name FROM comprobante_detalle, product WHERE comprobante_id = {$idFa} and producto_id=id";
$resProd=$conex->query($consultaProd);?>
<option value="0">PRODUCTOS</option>
<?php while($prod=$resProd->fetch_assoc()){?>
    <option value="<?php echo $prod['producto_id']; ?>"><?php echo $prod['name']; ?></option>
<?php }
mysqli_close($conex);
?>