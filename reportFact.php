<?php
require_once 'model/fact.entidad.php';
require_once 'model/database.php';
require_once 'model/comprobante.model.php';

// Logica
$model = new ComprobanteModel();

$tipo = isset($_REQUEST['t']) ? $_REQUEST['t'] : 'excel';
$extension = $tipo == 'excel' ? '.xls' : '.doc';

header("Content-type: application/vnd.ms-$tipo");
header("Content-Disposition: attachment; filename=mi_archivo$extension");
header("Pragma: no-cache");
header("Expires: 0");
//<?php echo $tipo; 
?>

<h1>REPORTE DE FACTURACION </h1>
<!--<p>Hemos creado nuestro reporte en <b><?php echo $tipo; ?></b> usando PHP y HTML :).</p>-->

<table>
    <thead>
        <tr>
            <th style="text-align:left;">Cliente</th>
            <th style="text-align:left;">Factura</th>
            <th style="text-align:left;">Tigo Pago</th>
            <th style="text-align:left;">Moneda</th>
            <th style="text-align:left;">Fecha</th>
            <th style="text-align:left;">Fecha Vence</th>
            <th style="text-align:left;">SubTotal</th>
            <th style="text-align:left;">Descuento</th>
            <th style="text-align:left;">Total</th>
            <th style="text-align:left;">Estado</th>
        </tr>
    </thead>
    <?php foreach($model->ListarFact() as $r): ?>
        <tr>
            <td><?php echo $r->__GET('Cliente'); ?></td>
            <td><?php echo $r->__GET('Factura'); ?></td>
            <td><?php echo $r->__GET('TipoPago'); ?></td>
            <td><?php echo $r->__GET('Moneda'); ?></td>
            <td><?php echo $r->__GET('Fecha'); ?></td>
            <td><?php echo $r->__GET('FechaVence'); ?></td>
            <td><?php echo $r->__GET('SubTotal'); ?></td>
            <td><?php echo $r->__GET('Descuento'); ?></td>
            <td><?php echo $r->__GET('Total'); ?></td>
            <td><?php echo $r->__GET('Estado'); ?></td>
        </tr>
    <?php endforeach; ?>
</table>   