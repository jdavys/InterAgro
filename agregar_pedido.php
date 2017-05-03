<?php
	/*-------------------------
	Autor: Obed Alvarado
	Web: obedalvarado.pw
	Mail: info@obedalvarado.pw
	---------------------------*/
if(isset($comprobante)){
	$session_id=$comprobante->id;
}else{
	$session_id=intval($_REQUEST['idC']);
}

if (isset($_POST['id'])){$id=$_POST['id'];}
if (isset($_POST['cantidad'])){$cantidad=$_POST['cantidad'];}
if (isset($_POST['precio_venta'])){$precio_venta=$_POST['precio_venta'];}
if (isset($_POST['presenta'])){$presenta=$_POST['presenta'];}
if (isset($_POST['desc'])){$desc=$_POST['desc'];}

	/* Connect To Database*/
	require_once ("db.php");//Contiene las variables de configuracion para conectar a la base de datos
	require_once ("conexion.php");//Contiene funcion que conecta a la base de datos
	
if (!empty($id) and !empty($cantidad) and !empty($precio_venta))
{
	$tot=$cantidad*$precio_venta;
	var_dump($_POST);
	var_dump($tot);;
	var_dump("INSERT INTO detalle_prefactura (comprobante_id,producto_id,presenta,cantidad,precioUnitario,descuento,total) 
		VALUES ({$session_id},{$id},{$presenta},{$cantidad},{$precio_venta},{$desc},{$tot})");
	$insert_tmp=mysqli_query($con, "INSERT INTO detalle_prefactura (comprobante_id,producto_id,presenta,cantidad,precioUnitario,descuento,total) 
		VALUES ({$session_id},{$id},{$presenta},{$cantidad},{$precio_venta},{$desc},{$tot})");
	var_dump($insert_tmp);

	$sql=mysqli_query($con,"SELECT sum(total) as total FROM detalle_prefactura where comprobante_id={$session_id}");
	$row=mysqli_fetch_array($sql);
	$subT=$row['total'];
	
		$upda_detaP=mysqli_query($con, "UPDATE  prefactura set subtotal={$subT},total={$subT} WHERE id={$session_id}");
	

}
if (isset($_GET['idFD']))//codigo elimina un elemento del array and isset($_GET['pr'])
{
	$id=intval($_GET['idFD']);
	
	$delete=mysqli_query($con, "DELETE FROM detalle_prefactura WHERE id={$id}");
	$sql=mysqli_query($con,"SELECT sum(total) as total FROM detalle_prefactura where comprobante_id={$session_id}");
	$row=mysqli_fetch_array($sql);
	$subT=$row['total'];
	if(!is_null($subT)){
		$upda_detaP=mysqli_query($con, "UPDATE  prefactura set subtotal={$subT},total={$subT} WHERE id={$session_id}");
	}else{
		$deleteP=mysqli_query($con, "DELETE FROM prefactura WHERE id={$session_id}");	
	}
	

	

}

?>
<!-- <table class="table">
<tr>
	<th>CODIGO</th>
	<th>CANT.</th>
	<th>DESCRIPCION</th>
	<th><span class="pull-right">PRECIO UNIT.</span></th>
	<th><span class="pull-right">PRECIO TOTAL</span></th>
	<th></th>
</tr>
<?php
	$sumador_total=0;
		$sql=mysqli_query($con, "select df.id,comprobante_id,producto_id,name,presenta,cantidad,PrecioUnitario,Descuento,Total 
								from detalle_prefactura df,product p where producto_id=p.id and comprobante_id={$session_id}");
		while ($row=mysqli_fetch_array($sql))
		{
		$idF=$row["id"];
		$id_tmp=$row["comprobante_id"];
		$codigo_producto=$row['producto_id'];
		$cantidad=$row['cantidad'];
		$nombre_producto=$row['name'];
		//$id_marca_producto=$row['presenta'];
		/*if (!empty($id_marca_producto))
		{
		$sql_marca=mysqli_query($con, "select descripcion from presentacion where des='$id_marca_producto'");
		$rw_marca=mysqli_fetch_array($sql_marca);*/
		$nombre_marca=$row['presenta'];
		//$marca_producto=" ".strtoupper($nombre_marca);
		//}
		//else {$marca_producto='';}
		$precio_venta=$row['PrecioUnitario'];
		$precio_venta_f=number_format($precio_venta,2);//Formateo variables
		$precio_venta_r=str_replace(",","",$precio_venta_f);//Reemplazo las comas
		$precio_total=$precio_venta_r*$cantidad;
		$precio_total_f=number_format($precio_total,2);//Precio total formateado
		$precio_total_r=str_replace(",","",$precio_total_f);//Reemplazo las comas
		$sumador_total+=$precio_total_r;//Sumador
			?>
			<tr>
				<td><?php echo $codigo_producto;?></td>
				<td><?php echo $cantidad;?></td>
				<td><?php echo $nombre_producto.$nombre_producto;?></td>
				<td><span class="pull-right"><?php echo $precio_venta_f;?></span></td>
				<td><span class="pull-right"><?php echo $precio_total_f;?></span></td>
				<td ><span class="pull-right"><a href="#" onclick="eliminar('<?php echo $idF ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
			</tr>		
			<?php
		}

	?>
	<tr>
		<td colspan=4><span class="pull-right">TOTAL $</span></td>
		<td><span class="pull-right"><?php echo number_format($sumador_total,2);?></span></td>
		<td></td>
	</tr>
	</table>  -->