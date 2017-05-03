<?php 

$c=mysql_connect("127.0.0.1","root","");

mysql_select_db("facturador");
$desc=0;

$fecha1 = new DateTime($comprobante->fechaVence);
$fecha2 = new DateTime($comprobante->fecha);
/*$intervalo = $fecha1 ->diff($fecha2);
echo $intervalo ->format('%R%a días')."\n\r";*/
$intervalo = $fecha1 ->diff($fecha2, true);

//var_dump($comprobante);

/*$orden1="SELECT presentacion FROM producto where id= $_GET[id];"; 
$paquete2=mysql_query($orden1);*/
?>
<ol class="breadcrumb">
  <li><a href="?c=Comprobante&a=index">Inicio</a></li>
  <li class="active">Comprobante #<?php echo str_pad($comprobante->id, 5, '0', STR_PAD_LEFT); ?></li>
</ol>

<form method="post" action="factura3.php">
<?php if($comprobante->estado === 'PENDIENTE'){ ?>
<a class="btn btn-primary pull-right btn-lg" href="?c=comprobante&a=eliminar&id=<?php echo $comprobante->id; ?>" onclick="return confirm('¿Está seguro de eliminar este comprobante?');">ELIMINAR FACTURA</a>
<?php }else{ ?>
<a class="btn btn-primary pull-right btn-lg" href="?c=comprobante&a=eliminarF&id=<?php echo $comprobante->id; ?>" onclick="return confirm('¿Está seguro de eliminar este comprobante?');">ELIMINAR FACTURA</a>
<?php } ?>
<a class="btn btn-primary pull-left btn-lg" href="?c=Comprobante&a=index">REGRESAR</a>
<!--<a class="btn btn-primary pull-left btn-lg" href="?c=Comprobante&a=modificar&id=<?php echo $comprobante->id; ?>">MODIFICAR</a>-->

<br>
<br>
<br>
<div class="row">
        <div class="col-xs-12">
            <?php if($comprobante->Tipo_Pago != ''){?>
            <fieldset>
                <legend>Datos de nuestro cliente</legend>
                <div class="row">
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
                            <input name="comprob_id" type="hidden" value="<?php echo $comprobante->id; ?>" />
                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->phone1; ?>"  />                    
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <input name="prod_id" type="hidden" value="<?php echo $comprobante->Producto->id; ?>" />
                           <label>Dirección</label>
                           <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->address1; ?>" />                    
                        </div>
                    </div>
                </div>
                
                <div class="row">
                        <div class="col-xs-2">
                            <div class="form-group">
                                <input name="fecH" type="hidden" value="<?php echo $comprobante->fecha; ?>" />
                                <label>Forma de Pago </label>
                                <input type="text" class="form-control" name="p" value="<?php echo $comprobante->Tipo_Pago; ?>" readonly/>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <input name="fecV" type="hidden" value="<?php echo $comprobante->fechaVence; ?>" />
                                <label>Moneda </label>
                                <input  type="text" name="moneda" class="form-control" value="<?php echo $comprobante->Moneda; ?>" readonly/>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Plazo </label>
                                <input  type="text" name="pz" class="form-control" value="<?php echo $intervalo->format('%R%a');?>" readonly />
                            </div>
                        </div>
                        <?php if($comprobante->estado === 'PREFACTURA'){ ?>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Tipo Factura </label></br>
                                <input type="radio" name="tipoFact" value="prefact" /> PRE-FACTURA<br />
                                <input type="radio" name="tipoFact" value="normal"  /> NORMAL<br />
                                <input type="radio" name="tipoFact" value="ticket" /> TICKET<br />
                                
                            </div>
                        </div>
                        <?php }else{ ?>
                            <!--<input type="hidden" name="tipoFact" value="prefact" />-->
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label>Tipo Factura </label></br>
                                    <input type="radio" name="tipoFact" value="prenormal" checked />PRE-NORMAL<br />      
                                    <input type="radio" name="tipoFact" value="prefact" />PRE-FACTURA<br />
                                                                  
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Impuesto de Ventas </label>
                                <input  name="iv" class="form-control" value="<?php echo $comprobante->IGV; ?>" readonly/>
                                    
                            </div>
                        </div>
                       <input name="formato" type="hidden" value="0" />
                </div>

            </fieldset>
            <?php }else{?>
            
            <fieldset>
                <legend>Datos de nuestro cliente</legend>
                <div class="row">
                    <input name="formato" type="hidden" value="1" />
                    <div class="col-xs-4">
                        <div class="form-group">
                            <label>Cliente</label>
                            <input name="cliente_id" type="hidden" value="<?php echo $comprobante->Cliente->id; ?>" />
                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->Nombre; ?>" />
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="form-group">
                            <label>Telefono</label>
                            <input name="comprob_id" type="hidden" value="<?php echo $comprobante->id; ?>" />
                            <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->RUC; ?>"  />                    
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <input name="prod_id" type="hidden" value="<?php echo $comprobante->Producto->id; ?>" />
                           <label>Dirección</label>
                           <input type="text" class="form-control" disabled value="<?php echo $comprobante->Cliente->Direccion; ?>" />                    
                        </div>
                    </div>
                </div>
                <div class="row">
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Forma de Pago </label></br>
                                <input type="radio" name="pago" value="Contado" checked  /> CONTADO<br />
                                <input type="radio" name="pago" value="Credito" /> CREDITO<br />
                                
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Moneda </label></br>
                                <input type="radio" name="moneda" value="COLONES" checked  /> COLONES<br />
                                <input type="radio" name="moneda" value="DOLARES" /> DOLARES<br />
                                
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Plazo </label>
                                <input type="text" name="plazo" class="form-control" value="0" />
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Tipo Factura </label></br>
                                <input type="radio" name="tipoFact" value="prefact" checked  /> PRE-FACTURA<br />
                                
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="form-group">
                                <label>Impuesto de Ventas </label>
                                <select  name="impuestoV" class="form-control">
                                    <option selected value='0'>Exento%</option>
                                    <option  value='13'>13%</option>
                                    <option  value='15'>15%</option>
                                </select>
                            </div>
                        </div>
                        
            </div>
                
            </fieldset>

            <?php  } ?>


            <ul id="facturador-detalle" class="list-group">
                <?php 
                $desc=0;
                foreach($comprobante->Detalle as $d): ?>
                <li class="list-group-item">
                    <input name="idProd" type="hidden" value="<?php echo $d->Producto->id; ?>" />

                    <div class="row">
                        <div class="col-xs-5">
                            <?php echo $d->Producto->name; ?>
                        </div>

                        <div class="col-xs-1 text-right">
                            <?php echo $d->Cantidad; ?>
                        </div>
                        <div class="col-xs-2 text-right">
                            <?php echo number_format($d->PrecioUnitario, 2); ?>
                        </div>
                        <div class="col-xs-2">
                            <b><?php echo number_format(($d->Descuento/100)*($d->PrecioUnitario)*$d->Cantidad, 2); $desc+=($d->Descuento/100)*($d->PrecioUnitario)*$d->Cantidad ?></b>
                        </div>
                        <div class="col-xs-2 text-right">
                            <?php echo number_format($d->Total, 2); ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
                <li class="list-group-item">
                    <div class="row text-right">
                        <div class="col-xs-10 text-right">
                            Sub Total
                        </div>
                        <div class="col-xs-2">
                            <b><?php echo number_format($comprobante->SubTotal, 2); ?></b>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row text-right">
                        <div class="col-xs-10 text-right">
                            Descuento
                        </div>
                        <div class="col-xs-2">
                            <b><?php echo number_format($desc, 2); ?></b>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row text-right">
                        <div class="col-xs-10 text-right">
                            Total <b>(C/.)</b>
                        </div>
                        <div class="col-xs-2">
                            <b><?php echo number_format($comprobante->Total, 2); ?></b>
                        </div>
                    </div>
                </li>
            </ul>

        </div>
</div>
<button class="btn btn-primary btn-block btn-lg" type="submit">IMPRIMIR</button> 
<!--<a class="btn btn-primary btn-danger btn-lg btn-block" href="?c=comprobante&a=eliminar&id=<?php //echo $comprobante->id; ?>" onclick="return confirm('¿Está seguro de eliminar este comprobante?');">Eliminar comprobante</a>-->
</form>