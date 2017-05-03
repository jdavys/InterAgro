<?php
$servidor="localhost";
$dbuser="root";
$dbpassword="";
$dbnombre="facturador";

$conex = new mysqli($servidor,$dbuser,$dbpassword,$dbnombre);
if($conex->connect_errno>0){
		die("No se pudo conectar con la base de datos ".$conex->connect_error."");
	}
mysql_query("SET NAMES 'utf8'");

$idCli=$_POST['idCli'];
$consultaFact="SELECT * FROM person WHERE id = {$idCli}";
//"SELECT * from person where name = (SELECT nombre FROM cliente WHERE id = {$idCli}) ";
$resFact=$conex->query($consultaFact);
while($facturas=$resFact->fetch_assoc()){
	if($facturas['tipo_cliente']=='COLONES'){
		echo "<option value=\"COLONES\" selected>COLONES</option>";
		echo "<option value=\"DOLARES\">DOLARES</option>";
	}else{
		echo "<option value=\"DOLARES\" selected>DOLARES</option>";
		echo "<option value=\"COLONES\">COLONES</option>";
	}?>
	
	
<?php
}
mysqli_close($conex);

//
	/*if($tp1==="Credito"){ 

		<option value="<?php echo $facturas['tipo_cliente'];?>"><?php echo $facturas['tipo_cliente'];?></option>
	<option value="COLONES">COLONES</option>
    
		$tp2="Colones";
	}else{
		$tp2="Credito";  http://localhost:81/facturadorV1-2017/facturador/?c=Comprobante&a=crud
	}*/
?>
