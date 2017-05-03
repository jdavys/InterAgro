<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php

$orden1="SELECT id,name FROM person, cuenta_cobrar
            where id=id_cliente and saldo_global>0 and fecha_Vence group by name ";
$paquete1=mysql_query($orden1);

$orden2="SELECT * FROM product ORDER BY name asc";
$paquete2=mysql_query($orden2);

?>
<?php
    obtenerPagina();
?>
<?php include("includes/header.php");?>
        <link rel="stylesheet" href="css/font-awesome.min.css">
            
        <link href="css/bootstrap-responsive.css" rel="stylesheet">
        
        <link href="style.css" media="screen" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" type="text/css" href="css/DT_bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="tcal.css" />
        <script type="text/javascript" src="tcal.js"></script>
        <script src="jquery.min.js"></script>
        <script>
                
                function agregarProducto(){
                    var idP=$("#producto").val();
                        $.ajax({
                                url: "agregarProd.php",
                                data:{idProd:idP},
                                type: "POST",
                                success:function(data){
                                        $("#detP").html(data);
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

        </script>
        <table id="estructura">
            <tr>
                <td id="menu">
                    <?php echo menu($reg_cliente,$reg_fact);?>
                <br />

                </td>
                <td id="pagina">
                    <h2>REPORTES </h2></br>
                    <form action="generaReporteProd.php" method="post">
                        <table style="width:500px;">
                        <!--<tr>
                            <th style="text-align:left;">Tipo</th>

                            <td>
                                <div class="form-group">
                                    <select id="tipo" onchange="mostrarTipo()" name="tipo"  style="width:70%" class="form-control">
                                    <option value="t">TODO</option>
                                    <option value="a" selected>AGENTE</option>
                                    <option value="cl">CLIENTE</option>
                                </div>
                            </td>
                           
                        </tr>-->
                        <tr>
                            <th style="text-align:left;">Productos</th>
                            <td>

                            <div class="form-group">
                            <select id="producto" name="producto" style="width:70%" class="form-control">
                                    <option value="t" selected>TODO</option>
                                    <?php while($reg1=mysql_fetch_array($paquete2)) { ?>
                                    <option value="<?php echo $reg1[0];?>"><?php echo $reg1[3] ?></option>
                                    <?php };?>
                            </select>
                            <td>
                                 <button type="button" class="btn btn-success" onclick="agregarProducto()">AGREGAR</button> 
                            </td>
                            
                            </div>
                          
                        </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;"></th>
                            <td>

                            <div name="detP" id="detP" class="form-group">
                               
                            </div>
                          
                        </td>
                        </tr>
                        <tr>
                            <!--<th style="text-align:left;">Factura</th>
                            <td>
                            <div class="form-group">
                            <select name="prod" id='prod'  style="width:51%" class="form-control">
                                
                            </select>
                            </div>
                            </td>-->
                        </tr>
                         <tr>
                            <!--<th style="text-align:left;">Moneda</th>
                            <td>
                            <div class="form-group">
                            <select name="tmoneda" id='tmoneda' style="width:51%" class="form-control">
                                
                            </select>
                            </div>
                            </td>-->
                        </tr>
                        <tr>
                            <th style="text-align:left;">DESDE:  </th>
                            <td><input type="text" style="width: 150px; padding:10px;" name="fechaD" id="fechaD" class="tcal" /> </td>
                            <th style="text-align:left;">HASTA: </th>
                            <td><input type="text" style="width: 150px; padding:10px;" name="fechaH" id= "fechaH" class="tcal" /> </td>
                        </tr>

                            
                        
                        <!--<tr>
                            <th style="text-align:left;">Monto  Intereses</th>
                            <td><div class="form-group"><input type="text" name="montoAbono" value="" class="form-control"/></td></div>
                        </tr>
                         <tr>
                            <th style="text-align:left;">Saldo Factura</th>
                            <td><div class="form-group"><input type="text" name="saldo_Factura" value="" class="form-control" readonly/></td></div>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Saldo Global</th>
                            <td><div class="form-group"><input type="text" name="saldo_Global" value="" class="form-control" readonly/></td></div>
                        </tr> -->
                        <tr>
                            <td colspan="16">
                                <hr><br><br>
                                <div align="center" class="form-group"><button type="submit" class="btn btn-success">GENERAR</button> <a class="btn btn-success" href="new_report_product.php">REFRESCAR</a></div>

                            </td>
                        </tr>
                    </table>

                    </form>
                    <a href="content.php"></a>
                </td>
            </tr>
        </table>