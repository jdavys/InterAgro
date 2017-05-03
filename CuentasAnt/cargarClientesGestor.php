<?php
include("conexion.php");
$idGest=$_POST['idGest'];

if($idGest=='t'){
	$consulta="SELECT * FROM person";
}else{
	$consulta="SELECT * FROM person where agente in (select id from vendedor where name like '%{$idGest}%')";
}

$res=$conex->query($consulta); ?>
 <option value="t" selected>TODO</option>
<?php while($clientes=$res->fetch_assoc()){ ?>
	<option value="<?php echo $clientes['id']; ?>"><?php echo $clientes['name']; ?></option>
<?php }

mysqli_close($conex);
?>