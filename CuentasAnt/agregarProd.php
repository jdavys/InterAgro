<?php
include("conexion2.php");  //$hile.=$prod['id']."  -  ".$prod['nombre']; 
$idP=$_POST['idProd'];
$consultaProd="SELECT id,name FROM  product WHERE  id={$idP}";
$resProd=$conex->query($consultaProd);

while($prod=$resProd->fetch_assoc()){?>
  
<textarea id="detalleP" name="detalleP" rows="5" cols="55" value="<?php echo $prod['id'];?>"><?php echo $prod['name'];?></textarea>
<?php
}
mysqli_close($conex);

