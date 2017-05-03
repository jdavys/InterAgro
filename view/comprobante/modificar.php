<?php
$fecha1 = new DateTime($comprobante->fechaVence);
$fecha2 = new DateTime($comprobante->fecha);
/*$intervalo = $fecha1 ->diff($fecha2);
echo $intervalo ->format('%R%a días')."\n\r";*/
$intervalo = $fecha1 ->diff($fecha2, true);
?>

<ol class="breadcrumb">
  <li><a href="?c=Comprobante&a=index">Inicio</a></li>
  <li class="active">Comprobante #<?php echo str_pad($comprobante->id, 5, '0', STR_PAD_LEFT); ?></li>
</ol>
<form id="frm-comprobante-modi" method="post" action="?c=Comprobante&a=EditarPre">

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
                            <input name="comprob_id" type="hidden" value="<?php echo $comprobante->id; ?>" />
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
                                        <select id="moneda" name ="moneda" class="form-control" type="text"/>
                                            <option value="<?php echo $comprobante->Moneda; ?>" selected><?php echo $comprobante->Moneda; ?></option>
                                            <option value='COLONES'>COLONES</option>"; 
                                            <option value='DOLARES'>DOLARES</option>";
                                        </select>
                                       
                            </div>
                        </div>
                        <div class="col-xs-2">
                                <div class="form-group">
                                        <label>Plazo </label>
                                        <input type="text" id="plazo" name="plazo" class="form-control" value="<?php echo $intervalo->format('%R%a');?>" />
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
            </fieldset>        
        </div>
        <hr />

        <ul id="facturador-detalle" class="list-group"></ul>
            
        <button class="btn btn-primary btn-block btn-lg" type="submit">Guardar Cambios</button>
</form>  
<script src="assets/scripts/comprobante.js"></script>
            
            <!--<ul id="facturador-detalle" class="list-group">
                <?php 
                $desc=0;
                foreach($comprobante->Detalle as $d): ?>
                <li class="list-group-item">
                    <input name="idProd" type="hidden" value="<?php echo $d->Producto->id; ?>" />

                    <div class="row">
                        <div class="col-xs-1">
                            <a href="?c=comprobante&a=EliminarProdDeta&id=<?php echo $d->Comprobante_id; ?>&idProd=<?php echo $d->Producto_id; ?>" onclick="return confirm('¿Está seguro de eliminar este comprobante?');"><i class="glyphicon glyphicon-minus"></i></a>
                        </div>
                        <div class="col-xs-5">
                            <?php echo $d->Producto->Nombre; ?>
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
                            <b><?php echo number_format($comprobante->SubTotal-$desc, 2); ?></b>
                        </div>
                    </div>
                </li>
            </ul>-->

         
            
