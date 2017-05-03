<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php

$fec=date('d-m-y');
$orden1="SELECT id,name FROM person, cuenta_cobrar
            where id=id_cliente  group by name order by name  asc ";
$paquete1=mysql_query($orden1);

$orden2="SELECT * FROM tipo_credito";
$paquete2=mysql_query($orden2);

$orden3="SELECT * FROM comprobante_detalle";
$paquete3=mysql_query($orden2);


?>
<?php
    obtenerPagina();
?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.12.1/themes/ui-darkness/jquery-ui.css"></script>
<script>
      $(function() {
        $("#cliente").autocomplete({
          source: 'search.php'
        });
      });
</script>
<?php include("includes/header.php");?>
        
        
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
                function mostrarSaldo(){
                    var fact=$("#factura").val();
                    $.ajax({
                            url: "cargarSaldo.php",
                            data:{idFact:fact},
                            type: "POST",
                            success:function(data){
                                    $("#saldoF").html(data);
                                }               
                        })      
                }

                function mostrarCant(){
                    var id=$("#producto").val();
                    var fact=$("#factura").val();
                    $.ajax({
                            url: "cargarCant.php",
                            data:{idPro:id,idFact:fact},
                            type: "POST",
                            success:function(data){
                                    $("#cantidad").html(data);
                                }               
                        })      
                }

                 function mostrarProd(){
                    var fac=$("#factura").val();
                    $.ajax({
                            url: "cargarProd.php",
                            data:{idF:fac},
                            type: "POST",
                            success:function(data){
                                    $("#producto").html(data);
                                }               
                        })      
                }

                function cambiaTipo(){
                    var tip=$("#tipoAbono").val();
                    if(tip=='2'){
                        $('#producto').removeAttr("disabled");
                        mostrarProd();
                    }else{
                        $("#producto option[value=0]").attr("selected",true); 
                        $('#producto').attr('disabled', 'disabled');
                        $('#cantExi').hide();
                        
                    }
                   
                }

        </script>
        <table id="estructura">
            <tr>
                <td id="menu">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="input-group">
                              <input type="text" class="form-control" placeholder="Search for...">
                              <span class="input-group-btn">
                                <button class="btn btn-default" type="button">Go!</button>
                              </span>
                            </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                   
                    <!--<?php //echo menu($reg_cliente,$reg_fact);?>-->
                <br />
             
                </td>
                <td id="pagina">
                    <h2>Agregar un ABONO </h2></br>
                    <form action="create_abono.php" method="post">
                        <table style="width:500px; border-collapse: separate;              border-spacing: 0px 10px;">
                        <tr>
                            <th style="text-align:left;">Cliente</th>
                            <td>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="input-group">                               
                                            <input id="cliente" name="cliente" type="text" class="form-control" placeholder="cliente" >
                                            <span class="input-group-btn">
                                                <button id="btnBuscar" onclick="mostrarFacturas()" class="btn btn-default" type="button">Go!</button>
                                            </span>
                                        </div><!-- /input-group -->
                                    </div><!-- /.col-lg-6 -->
                                </div><!-- /.row -->
                           <!-- <div class="form-group">
                            <select id="cliente" name="cliente" onchange="mostrarFacturas()" style="width:70%" class="form-control">
                                    <option value=" " selected>Clientes</option>
                                    <?php while($reg1=mysql_fetch_array($paquete1)) { ?>
                                    <option value="<?php echo $reg1[0]; ?>" > <?php echo  $reg1[1]; ?> </option>
                                    <?php } ?>
                            </select>
                            </div>-->

                            
                        </td>
                        </tr>
                        <tr>

                            <th style="text-align:left;">Factura</th>
                            <td>
                            <div class="form-group">
                            <select name="factura" id='factura' onchange="mostrarSaldo()" style="width:51%" class="form-control">
                                
                            </select>
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Saldo Factura</th>
                            <td><div name="saldoF" id="saldoF" class="form-group"></div></td>
                        </tr>                        
                        <tr>
                            <th style="text-align:left;">Monto a abonar</th>
                            <td><div class="form-group"><input type="text" name="montoAbono"  class="form-control"/></div></td>
                        </tr>
                       <tr>
                            <th style="text-align:left;">Tipo Abono</th>
                            <td>
                                <div class="form-group">
                                <select id="tipoAbono" name="tipoAbono" onchange="cambiaTipo()" style="width:51%" class="form-control">
                                        <option value="0" selected>TIPO ABONO</option>
                                        <?php while($reg2=mysql_fetch_array($paquete2)) { ?>
                                        <option value="<?php echo $reg2[0]; ?>" > <?php echo  $reg2[1]; ?> </option>
                                        <?php } ?>
                                </select>
                                </div>

                                <!--<div class="container-fluid">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="dropdown">
                                              <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                TIPO CREDITO
                                                <span class="caret"></span>
                                              </button>
                                              <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                <li><a href="create_abono.php?tipo=">PAGO</a></li>
                                                <li><a href="#">DESCUENTO</a></li>
                                                <li><a href="#">DEVOLUCIÓN</a></li>
                                                <li><a href="#">ANULACIÓN</a></li>
                                                <li role="separator" class="divider"></li>
                                                <li><a href="#">OTRO</a></li>
                                              </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>-->
                            </td>
                        </tr>
                        </<tr>
                            <th style="text-align:left;">Producto</th>
                            <td>
                                <div class="form-group">
                                <select id="producto" name="producto" onchange="mostrarCant()" style="width:51%" class="form-control">
                                        
                                </select>
                                </div>
                            </td>
                            <th style="text-align:left;">Cantidad</th>
                            <td>
                                <div class="form-group">
                                    <td><div name="cantidad" id="cantidad" class="form-group"></div></td>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha </th>
                            <td><input type="text" style="width: 223px; padding:14px;" name="fecha" class="tcal" /> </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                            </td>
                        </tr>
                    </table>
                     <hr><br>
                    <textarea name="detalle" rows="5" cols="55"></textarea>
                    <hr><br><br>
                    <div align="center" class="form-group"><button type="submit" class="btn btn-success">Guardar</button></div>

                    </form>
                    
                </td>
            </tr>
        </table>
<?php include("includes/footer.php");?>
