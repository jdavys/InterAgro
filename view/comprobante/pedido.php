<?php
$fecha1 = new DateTime($comprobante->fechaVence);
$fecha2 = new DateTime($comprobante->fecha);
/*$intervalo = $fecha1 ->diff($fecha2); 10081930josefa
echo $intervalo ->format('%R%a días')."\n\r";*/
$intervalo = $fecha1 ->diff($fecha2, true);
//var_dump($comprobante);
?>
<script type="text/javascript">
    

</script>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Modificar Pedidos</title>
	<meta name="author" content="Obed Alvarado">
   <!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
	<link rel=icon href='http://obedalvarado.pw/img/logo-icon.png' sizes="32x32" type="image/png">
  </head>
  <body>
	<?php if ($comprobante->estado == 'FACTURADO'){?>
				
				    <div class="container">
						 <div class="row-fluid">
						  
							<div class="col-md-12">
							<h2><span class="glyphicon glyphicon-edit"></span> Editar Pedido</h2>
							<hr>
							  	<ol class="breadcrumb">
							  		<li><a href="?c=Comprobante&a=index">Inicio</a></li>
							  		<li class="active">Comprobante #<?php echo str_pad($comprobante->id, 5, '0', STR_PAD_LEFT); ?></li>
								</ol>
							<hr>
						</div>
					</div>

	<?php echo "LA FACTURA YA ESTA REGISTRADA";
}else{ ?>	  	


    <div class="container">
		 <div class="row-fluid">
		  
			<div class="col-md-12">
			<h2><span class="glyphicon glyphicon-edit"></span> Editar Pedido</h2>
			<hr>
			  	<ol class="breadcrumb">
			  		<li><a href="?c=Comprobante&a=index">Inicio</a></li>
			  		<li class="active">Comprobante #<?php echo str_pad($comprobante->id, 5, '0', STR_PAD_LEFT); ?></li>
				</ol>
			<hr>
			
			<form  role="form" id="datos_pedido">
				<div class="row">
				        <div class="col-xs-12">
				            
				            <fieldset>
				                <legend>Datos de nuestro cliente</legend>
				                <div class="row">
				                    <input name="formato" type="hidden" value="1" />
				                    <div class="col-xs-4">
				                        <div class="form-group">
				                            <label>Cliente</label>
				                            <input name="cliente_id" type="hidden" value="<?php echo $comprobante->Cliente->id; ?>" />
				                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->name; ?>" />
				                        </div>
				                    </div>
				                    <div class="col-xs-2">
				                        <div class="form-group">
				                            <label>Telefono</label>
				                            <input name="comprob_id" id="comprob_id" type="hidden" value="<?php echo $comprobante->id; ?>" />
				                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->phone1; ?>"  />                    
				                        </div>
				                    </div>
				                    <div class="col-xs-6">
				                        <div class="form-group">
				                           <label>Dirección</label>
				                           <input name="estadoC" type="hidden" value="<?php echo $comprobante->estado; ?>" />
				                           <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->address1; ?>" />                    
				                        </div>
				                    </div>
				                </div>
				                <div class="row">
				                        <!--<div class="col-xs-2">
				                            <div class="form-group">
				                                <label>Forma de Pago </label></br>
				                                <select id="tipo_pago" name ="tipo_pago" class="form-control" type="text"/>
				                                    <option value='Contado' selected>Contado</option>"; 
				                                    <option value='Credito'>Credito</option>";
				                                </select>
				                            </div>
				                        </div>-->
				                        <div class="col-xs-2">
				                            <div class="form-group">
				                                <label>Moneda</label></br>
				                                <select id="moneda" disabled name ="moneda" class="form-control" type="text"/>
				                                   	<option <?php if($comprobante->Moneda=="COLONES")?> selected value="COLONES">COLONES</option>
				                                	<option <?php if($comprobante->Moneda=="DOLARES")?> selected value="DOLARES">DOLARES</option>
				                                </select>
				                                       
				                            </div>
				                        </div>
				                        <div class="col-xs-2">
				                                <div class="form-group">
				                                        <label>Plazo </label>
				                                        <input type="text" id="plazo" disabled name="plazo" class="form-control" value="<?php echo $intervalo->format('%R%a');?>" />
				                                </div>
				                        </div>
				                        
				                        <div class="col-xs-2">
				                            <div class="form-group">
				                                <label>Impuesto de Ventas </label>
				                                <select  name="impuestoV"  disabled class="form-control">
				                                    <option selected value='0'>Exento%</option>
				                                    <option  value='13'>13%</option>
				                                    <option  value='15'>15%</option>
				                                </select>
				                            </div>
				                        </div>
				                        <div class="col-xs-2">
				                            <div class="form-group">
				                                </br>
				                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
				                                 <span class="glyphicon glyphicon-plus"></span> Agregar productos
				                                </button>
				                               
				                            </div>  
				                        </div>
				                </div>
				            </fieldset>
				    	</div>
						
				</div>
				<hr>
				
			</form> 
			<br><br>
			<div id="resultados" class='col-md-12'></div><!-- Carga los datos ajax -->
			<table class="table">
			<tr>
				<th>CODIGO</th>
				<th>CANT.</th>
				<th>DESCRIPCION</th>
				<th><span class="pull-right">PRECIO UNIT.</span></th>
				<th><span class="pull-right">PRECIO TOTAL</span></th>
				<th></th>
			</tr>
			<?php
			
				/* Connect To Database*/
				require_once ("db.php");//Contiene las variables de configuracion para conectar a la base de datos
				require_once ("conexion.php");//Contiene funcion que conecta a la base de datos
				$sumador_total=0;
				$sql=mysqli_query($con, "select df.id,comprobante_id,producto_id,name,presenta,cantidad,PrecioUnitario,Descuento,Total 
										from detalle_prefactura df,product p where producto_id=p.id and comprobante_id={$comprobante->id}");
				
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
				//var_dump($precio_total,$precio_venta,$precio_venta_f,$cantidad);
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
						<td ><span class="pull-right"><a href="?c=Comprobante&a=ModiDetaPre&idC=<?php echo $id_tmp?>" onclick="eliminar('<?php echo $idF ?>','<?php echo $id_tmp ?>')"><i class="glyphicon glyphicon-trash"></i></a></span></td>
					</tr>		
					<?php
				}

			?>
			<tr>
				<td colspan=4><span class="pull-right">TOTAL </span></td>
				<td><span class="pull-right"><?php echo number_format($sumador_total,2);?></span></td>
				<td></td>
			</tr>
			</table>

			<!-- Modal -->
			<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Buscar productos</h4>
				  </div>
				  <div class="modal-body">
					<form class="form-horizontal">
					  <div class="form-group">
						<div class="col-sm-6">
						  <input type="text" class="form-control" id="q" placeholder="Buscar productos" onkeyup="load(1)">
						</div>
						<button type="button" class="btn btn-default" onclick="load(1)"><span class='glyphicon glyphicon-search'></span> Buscar</button>
					  </div>
					</form>
					<div id="loader" style="position: absolute;	text-align: center;	top: 55px;	width: 100%;display:none;"></div><!-- Carga gif animado -->
					<div class="outer_div" ></div><!-- Datos ajax Final -->
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					
				  </div>
				</div>
			  </div>
			</div>
			
			</div>	
		 </div>
	</div>

   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/VentanaCentrada.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
	<script>
		$(document).ready(function(){
			load(1);
		});

		function load(page){
			var q= $("#q").val();
			var idC=$("#comprob_id").val();
			var parametros={"action":"ajax","page":page,"q":q,"idC":idC};
			$("#loader").fadeIn('slow');
			$.ajax({
				url:'productos_pedido.php',
				data: parametros,
				 beforeSend: function(objeto){
				 $('#loader').html('<img src="ajax-loader.gif"> Cargando...');
			  },
				success:function(data){
					$(".outer_div").html(data).fadeIn('slow');
					$('#loader').html('');
					
				}
			})
		}
	</script>
	<script>
	function agregar (id,idC)
		{
			var precio_venta=$('#precio_venta_'+id).val();
			var cantidad=$('#cantidad_'+id).val();
			var presenta=$('#presenta_'+id).val();
			var desc=$('#desc_'+id).val();
			//Inicia validacion
			if (isNaN(cantidad))
			{
			alert('Esto no es un numero');
			document.getElementById('cantidad_'+id).focus();
			return false;
			}
			if (isNaN(precio_venta))
			{
			alert('Esto no es un numero');
			document.getElementById('precio_venta_'+id).focus();
			return false;
			}
			//Fin validacion
		var parametros={"id":id,"precio_venta":precio_venta,"cantidad":cantidad,"idC":idC,"presenta":presenta,"desc":desc};	
		$.ajax({
        type: "POST",
        url: "agregar_pedido.php",
        data: parametros,
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		}
			});
		}
		
			function eliminar (id,idC)
		{
			
			$.ajax({
        type: "GET",
        url: "agregar_pedido.php",
        data: {"idFD":id,"idC":idC},
		 beforeSend: function(objeto){
			$("#resultados").html("Mensaje: Cargando...");
		  },
        success: function(datos){
		$("#resultados").html(datos);
		
		}
			});

		}
		
		$("#datos_pedido").submit(function(){
		  var proveedor = $("#proveedor").val();
		  var transporte = $("#transporte").val();
		  var condiciones = $("#condiciones").val();
		  var comentarios = $("#comentarios").val();
		  if (proveedor>0)
		 {
			VentanaCentrada('./pdf/documentos/pedido_pdf.php?proveedor='+proveedor+'&transporte='+transporte+'&condiciones='+condiciones+'&comentarios='+comentarios,'Pedido','','1024','768','true');	
		 } else {
			 alert("Selecciona el proveedor");
			 return false;
		 }
		 
	 	});
	</script>
	
	
<script type="text/javascript">
$(document).ready(function() {
    $( ".proveedor" ).select2({        
    ajax: {
        url: "ajax/load_proveedores.php",
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                q: params.term // search term
            };
        },
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    },
    minimumInputLength: 2
});
});
</script>
<?php }?>
  </body>
</html>