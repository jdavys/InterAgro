<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php
$orden1="SELECT id,nombre FROM cliente, cuenta_cobrar
            where id=id_cliente and saldo_global>0 and fecha_Vence group by nombre ";
$paquete1=mysql_query($orden1);
?>
<?php
	obtenerPagina();
?>0
<?php include("includes/header.php");?>
        <link rel="stylesheet" href="css/font-awesome.min.css">
            
        <link href="css/bootstrap-responsive.css" rel="stylesheet">


        <link href="style.css" media="screen" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css">
        <link rel="stylesheet" type="text/css" href="tcal.css" />
        <script type="text/javascript" src="tcal.js"></script>
        <script src="jquery.min.js"></script>
        <script>
            function mostrarFacturas(){
                    var cli=$("#cliente").val();
                    $.ajax({
                            url: "cargarciudades.php",
                            data:{idCli:cli},
                            type: "POST",
                            success:function(data){
                                    $("#factura").html(data);
                                }               
                        })      
                }
        </script>
		<table id="estructura">
			<tr>
				<td id="menu">
					<?php echo menu($reg_cliente,$reg_fact);?>
				<br />

				</td>
				<td id="pagina">
					<h2>Agregar un INTERESES </h2></br>
					<form action="create_interes.php" method="post">
						<table style="width:500px;">
                        <tr>
                        	<th style="text-align:left;">Cliente</th>
                            <td>

                            <div class="form-group">
                            <select id="cliente" name="cliente" onchange="mostrarFacturas()" style="width:70%" class="form-control">
                                    <option value=" " selected>Clientes</option>
                                    <?php while($reg1=mysql_fetch_array($paquete1)) { ?>
                                    <option value="<?php echo $reg1[0];?>"><?php echo "<li><a href=\"index.php?fact=".urlencode($reg1[0])."\">".$reg1[1]."</a></li>"?></option>
                                    <?php };?>
                            </select>
                            </div>

                            
                        </td>
                        </tr>
                        <tr>

                            <th style="text-align:left;">Factura</th>
                            <td>
                            <div class="form-group">
                           	<select name="factura" id='factura' style="width:51%" class="form-control">
                            </select>
                            </div>
                            </td>
                        </tr>
                        
                        <tr>
                            <th style="text-align:left;">Monto  Intereses</th>
                            <td><div class="form-group"><input type="text" name="montoAbono" value="" class="form-control"/></td></div>
                        </tr>
                       <!--  <tr>
                            <th style="text-align:left;">Saldo Factura</th>
                            <td><div class="form-group"><input type="text" name="saldo_Factura" value="" class="form-control" readonly/></td></div>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Saldo Global</th>
                            <td><div class="form-group"><input type="text" name="saldo_Global" value="" class="form-control" readonly/></td></div>
                        </tr> -->
                        <tr>
                            <td colspan="2">
                                <div align="center" class="form-group"><button type="submit" class="btn btn-success">Guardar</button></div>

                            </td>
                        </tr>
                    </table>

					</form>
					<a href="content.php"></a>
				</td>
			</tr>
		</table>