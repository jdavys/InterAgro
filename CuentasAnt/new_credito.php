<?php header("Content-Type: text/html;charset=utf-8"); ?>
<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php
$orden1="SELECT * FROM cliente ";
$paquete1=mysql_query($orden1);
?>
<?php
	obtenerPagina();
?>0
<?php include("includes/header.php");?>
        <link rel="stylesheet" type="text/css" href="tcal.css" />
        <script type="text/javascript" src="tcal.js"></script>
		<table id="estructura">
			<tr>
				<td id="menu">
					<?php echo menu($reg_cliente,$reg_fact);?>
				<br />

				</td>
				<td id="pagina">
					<h2>Agregar una nueva Cuenta de credito</h2>
					<form action="create_cuenta.php" method="post">
						<table style="width:500px;">
                        <tr>
                        	<th style="text-align:left;">Cliente</th>
                            <td>

                            <div class="form-group">
                            <select id="cliente" name="cliente" style="width:70%" class="form-control">
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
                           	<input type="text" id="factura" name="facturas" style="width:51%" class="form-control"/>
                                    
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha Inicio</th>
                            <td><div class="form-group"><input type="text" name="d1" value="" class="tcal"/></td></div></div>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha Vence</th>
                            <td><div class="form-group"><input type="text" name="d2" value="" class="tcal"/></td></div></div>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Monto Inicio</th>
                            <td><div class="form-group"><input type="text" name="monto_Inicio" value=""  class="form-control"/></td></div>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Intereses</th>
                            <td><div class="form-group"><input type="text" name="intereses" value="" class="form-control"/></td></div>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="form-group"><button type="submit" class="pure-button pure-button-primary">Guardar</button></div>
                            </td>
                        </tr>
                    </table>

					</form>
					<a href="content.php"></a>
				</td>
			</tr>
		</table>
