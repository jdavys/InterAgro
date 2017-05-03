<?php
require_once 'cuentasC.entidad.php';
require_once 'cuentasC.model.php';


$c=mysql_connect("127.0.0.1","root",'123456');
mysql_select_db("facturador");

$orden1="SELECT * FROM cliente ";
$paquete1=mysql_query($orden1);




// Logica
$eq = new CuentaC();
$model = new CuentasCModel();

if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'actualizar':
            $eq->__SET('cuenta_id',       $_REQUEST['cuenta_id']);
			$eq->__SET('id_Cliente',       $_REQUEST['id_Cliente']);
			$eq->__SET('id_Comprobante',     $_REQUEST['id_Comprobante']);
			$eq->__SET('fecha_Inicio',        $_REQUEST['fecha_Inicio']);
            $eq->__SET('monto_Inicial',  $_REQUEST['monto_Inicial']);
            
			

			$model->Actualizar($eq);
			header('Location: index.php');
			break;

		case 'registrar':
            $eq->__SET('cuenta_id',       $_REQUEST['cuenta_id']);
			$eq->__SET('id_Cliente',       $_REQUEST['id_Cliente']);
            $eq->__SET('id_Comprobante',   $_REQUEST['id_Comprobante']);
            $eq->__SET('fecha_Inicio',    $_REQUEST['fecha_Inicio']);
            $eq->__SET('monto_Inicial',  $_REQUEST['monto_Inicial']);
            

			$model->Registrar($eq);
			header('Location: index.php');
			break;

		case 'eliminar':
			$model->Eliminar($_REQUEST['cuenta_id']);
			header('Location: index.php');
			break;

		case 'editar':
			$eq = $model->Obtener($_REQUEST['cuenta_id']);
			break;
	}
}
?>

<!DOCTYPE html>
<html lang="ES">
	<head>
        <meta charset="utf-8">
		<title>Manteminiento Clientes</title>

        <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
        <link rel="stylesheet" type="text/css" href="tcal.css" />
        <script type="text/javascript" src="tcal.js"></script>
        <script type="text/javascript" src="js/jquery.min.js"></script>

	</head>
    <body style="padding:15px;">
        <h1>Manteminiento Cuentas x Cobrar</h1>

        <div class="pure-g">
            <div class="pure-u-1-12">
                
                <form action="?action=<?php echo $eq->cuenta_id > 0? 'actualizar' : 'registrar'; ?>" method="post" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <input type="hidden" name="cuenta_id" value="<?php echo $eq->__GET('cuenta_id'); ?>" />

                    <table style="width:500px;">
                        <tr>
                            <td>
                            <select id="id_Cliente" name ="id_Cliente">
                                    <option value=" " >Clientes</option>
                                    <?php while($reg1=mysql_fetch_array($paquete1)) { ?>
                                    <option value= "<?php echo $reg1[0]; ?>" > <?php echo $reg1[1]; ?> </option>
                                    <?php } ?>
                            </select>
                        </td>
                        </tr>
                        <tr>
                            
                           
                            <th style="text-align:left;">Factura</th>
                            <td>
                                    <input type="text" name="id_Comprobante" value="<?php echo $eq->__GET('id_Comprobante'); ?>" style="width:200%;" />
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Fecha Inicio</th>
                            <td><input type="text" style="width: 223px; padding:14px;" name="fecha_Inicio" class="tcal" value="<?php echo $eq->__GET('fecha_Inicio'); ?>" /> </td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Monto Inicio</th>
                            <td><input type="text" name="monto_Inicial" value="<?php echo $eq->__GET('monto_Inicial'); ?>" style="width:200%;" /></td>
                        </tr>
                        
                        <tr>
                            <td colspan="2">
                                <button type="submit" class="pure-button pure-button-primary">Guardar</button>
                            </td>
                        </tr>
                    </table>
                </form>

                <table class="pure-table pure-table-horizontal" style="width:1300px;">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Cliente</th>
                            <th style="text-align:left;">Factura</th>
                            <th style="text-align:left;">Fecha Inicio</th>
                            <th style="text-align:left;">Monto Inicial</th>
                            



                        </tr>
                    </thead>

                    <?php foreach($model->Listar() as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('id_Cliente'); ?></td>
                            <td><?php echo $r->__GET('id_Comprobante'); ?></td>
                            <td><?php echo $r->__GET('fecha_Inicio'); ?></td>
                            <td><?php echo $r->__GET('monto_Inicial'); ?></td>
                            

                            <td>
                                <a href="?action=editar&cuenta_id=<?php echo $r->cuenta_id; ?>">Editar</a>
                            </td>
                            <td>
                                <a href="?action=eliminar&cuenta_id=<?php echo $r->cuenta_id; ?>">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                   
                </table>    
              
            </div>
        </div>

    </body>
    <script type="text/javascript" src="js/jquery.min.js"></script>

</html>
