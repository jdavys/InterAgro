<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php

$fec=date('d-m-y');
$orden1="SELECT id,name FROM person, cuenta_cobrar
            where id=id_cliente and saldo_global > 0 group by name ";
$paquete1=mysql_query($orden1);
?>
<?php
    obtenerPagina();
?>
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script>
      $(function() {
        $("#cliente").autocomplete({
          source: 'search.php'
        });
      });
</script>
<?php include("includes/header.php");?>
       
        
        <table id="estructura">
            <tr>
                <td id="menu">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="input-group">                               
                                <input id="cliente" type="text" class="form-control" placeholder="Search for...">
                                <span class="input-group-btn">
                                    <button id="btnBuscar" class="btn btn-default" type="button">Go!</button>
                                </span>
                            </div><!-- /input-group -->
                        </div><!-- /.col-lg-6 -->
                    </div><!-- /.row -->
                    <div id="clienteEnc" style="margin: 30px 20px auto auto;">

                        
                    </div>
                    <script>

                        
                        $(document).ready(function() { 
                            
                            $('button').on('click', function() {
                                var nom=$('#cliente').val();
                                $.ajax({
                                    url: "enc_cliente.php",
                                    data:"nom="+nom,
                                    type: "POST",
                                    success:function(data){
                                        $('#clienteEnc').html(data);
                                    }
                        
                                })     
                            });
                        });


                    </script>
                    <!--<?php //echo menu($reg_cliente,$reg_fact);?>-->

                   <br />
                </td>
                <td id="pagina">
                    
                    <form action="edit_abono.php?id=<?php urlencode($reg_abono['id_abono'])?>" method="post">
                        <?php if (!is_null($reg_cliente)){ ?>

                        <h2> <?php echo $reg_cliente['name']; ?></h2></br>
                        <?php  echo "<h3><b>SALDO GLOBAL COLONES:</b> <strong>".number_format($reg_cliente['saldo_Global'],2)."</strong></h3>"; ?></br>
                        <?php  echo "<h3><b>SALDO GLOBAL DOLARES:</b> <strong>$".number_format($reg_cliente['saldo_global_dolares'],2)."</strong></h3>"; ?></br>
                        <?php } ?>
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-hover table-striped">
                                        <thead >
                                                                        <tr >
                                                                            <th style="text-align:left;">Num-Abono</th>
                                                                            <th style="text-align:left;">Cliente</th>
                                                                            <th style="text-align:left;">Factura</th>
                                                                            <th style="text-align:left;">Monto</th>
                                                                            <th style="text-align:left;">Fecha</th>
                                                                        </tr>
                                                                        
                                                                    </thead>
                                        <tbody>
                                            <?php while($rowC=mysql_fetch_array($reg_abono)){ 
                                                //modifica.php?idab=<?php echo urlencode($rowC['id_abono']);?>
                                                <tr>
                                                    <td><?php echo $rowC['id_abono']; ?></td>
                                                    <td><a href="modifica.php?idab=<?php echo urlencode($rowC['id_abono']);?>"><?php echo $rowC['name']; ?></a></td>
                                                    <td><?php echo $rowC['comprobante_id']; ?></td>
                                                    <td><?php echo $rowC['monto']; ?></td>
                                                    <td><?php echo $rowC['fecha']; ?></td>
                                                </tr>
                                            <?php }; ?>
                                           
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>                        

                    </form>
                    
                </td>
            </tr>
        </table>
<!--<?php //include("includes/footer.php");?>-->