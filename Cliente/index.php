<?php
require_once 'equipo.entidad.php';
require_once 'equipo.model.php';

// Logica
$eq = new Cliente();
$model = new ClienteModel();

$c=mysql_connect("127.0.0.1","root",'123456');
mysql_select_db("facturador");

$orden1="SELECT * FROM vendedor"; 
$paquete1=mysql_query($orden1);


if(!empty($_POST))
{
    $valor = $_POST['campo'];
    if(!empty($valor)){
        $result=$model->ListarC($valor);
    }
}


if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case 'actualizar':
			$eq->__SET('id',       $_REQUEST['id']);
			$eq->__SET('Nombre',     $_REQUEST['Nombre']);
			$eq->__SET('RUC',        $_REQUEST['RUC']);
            $eq->__SET('Direccion',  $_REQUEST['Direccion']);
			$eq->__SET('Agente',  $_REQUEST['Agente']);

			$model->Actualizar($eq);
			header('Location: index.php');
			break;

		case 'registrar':
			$eq->__SET('id',       $_REQUEST['id']);
            $eq->__SET('Nombre',     $_REQUEST['Nombre']);
            $eq->__SET('RUC',        $_REQUEST['RUC']);
            $eq->__SET('Direccion',  $_REQUEST['Direccion']);
             $eq->__SET('Agente',  $_REQUEST['Agente']);

			$model->Registrar($eq);
			header('Location: index.php');
			break;

		case 'eliminar':
			$model->Eliminar($_REQUEST['id']);
			header('Location: index.php');
			break;

		case 'editar':
			$eq = $model->Obtener($_REQUEST['id']);
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
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="../assets/css/bootstrap-theme.min.css" />
        <link rel="stylesheet" href="../assets/js/jquery-ui/jquery-ui.min.css" />
        <link rel="stylesheet" href="../assets/css/style.css" />
        
        <script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
	</head>
    <body style="padding:15px;">
        <h1>Manteminiento Clientes</h1>

        <div class="pure-g">
            <div class="pure-u-1-12">
                <form action="<?php $_SERVER['PHP_SELF']; ?>" method="POST" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <table style="width:500px;">
                        <tr>
                            <th style="text-align:left;"><b>Cliente:</b></th>
                            <td><input type="text" id="campo" name="campo" style="width:80%;"/></td>
                            <td><input type="submit" id="enviar" name="enviar" value="BUSCAR" class="btn btn-info" /></td>
                            <td><a class="btn btn-info" href="http://localhost:8080/facturador/Cliente">LIMPIAR</a></td>
                        </tr>
                    </table>                     
                </form>
                
                <form action="?action=<?php echo $eq->id > 0 ? 'actualizar' : 'registrar'; ?>" method="post" class="pure-form pure-form-stacked" style="margin-bottom:30px;">
                    <input type="hidden" name="id" value="<?php echo $eq->__GET('id'); ?>" />
                    
                    <table style="width:500px;">
                        <!-- <tr>
                            <th style="text-align:left;"><label>Cliente</label></th>
                            <td>
                            <select id="cliente" style="width:100%;" onChange="document.href = 'index.php?y='+this.value;">
                                <?php //foreach($model->Listar() as $r): ?>
                                    <option value="<?php //echo $r->id; ?>"> <?php// echo $r->__GET('Nombre'); ?> </option>
                                <?php// endforeach; ?>   
                            </select>
                            </td>
                            <td style="width:10%;">
                            </td>

                            <td>
                                <a href="?action=editar&id=<?php $_GET['y'];?>" class="btn btn-primary form-control">    BUSCAR   </a>
                           </td>

                        </tr> -->
                        <tr>
                            <th style="text-align:left;">Nombre</th>
                            <td><input type="text" name="Nombre" value="<?php echo htmlspecialchars($eq->__GET('Nombre')); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Telefono</th>
                            <td><input type="text" name="RUC" value="<?php echo $eq->__GET('RUC'); ?>" style="width:100%;" /></td>
                        </tr>
                        <tr>
                            <th style="text-align:left;">Direccion</th>
                            <td><input type="text" name="Direccion" value="<?php echo $eq->__GET('Direccion'); ?>" style="width:200%;" /></td>
                        </tr>
                         <tr>
                            <th style="text-align:left;">Agente</th>
                            <td>
                            <select name="Agente" class="form-control" type="text" placeholder="Agente">
                                                                      
                        
                        <option value="<?php echo $eq->__GET('Agente');?>"> <?php echo $eq->__GET('Agente');?> </option>

                          
                                        
                            <?php
                                while ($reg=mysql_fetch_array($paquete1, MYSQL_NUM)) {?>
                                <option value="<?php echo $reg[1]; ?>"> <?php echo $reg[1]; ?> </option>
                                 <?php }?>
                                  
                            </select></td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <button type="submit" class="btn btn-primary form-control">Guardar</button>
                            </td>
                        </tr>
                    </table>
                </form>

                <table class="pure-table pure-table-horizontal" style="width:1300px;">
                    <thead>
                        <tr>
                            <th style="text-align:left;">Nombre</th>
                            <th style="text-align:left;">Teléfono</th>
                             <th style="text-align:left;">Dirección</th>
                               <th style="text-align:left;">Agente</th>
                               <th style="text-align:left;"></th>
                        </tr>
                    </thead>
                   
                    <?php 
                    if(empty($_POST)){
                        foreach($model->Listar() as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('Nombre'); ?></td>
                            <td><?php echo $r->__GET('RUC'); ?></td>
                            <td><?php echo $r->__GET('Direccion'); ?></td>
                            <td><?php echo $r->__GET('Agente'); ?></td>
                            
                            <td>
                                <a href="?action=editar&id=<?php echo $r->id; ?>">Editar</a>
                            </td>
                            <td>
                                <a href="?action=eliminar&id=<?php echo $r->id; ?>">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach; 
                    }else{
                        foreach($result as $r): ?>
                        <tr>
                            <td><?php echo $r->__GET('Nombre'); ?></td>
                            <td><?php echo $r->__GET('RUC'); ?></td>
                            <td><?php echo $r->__GET('Direccion'); ?></td>
                            <td><?php echo $r->__GET('Agente'); ?></td>
                            
                            <td>
                                <a href="?action=editar&id=<?php echo $r->id; ?>">Editar</a>
                            </td>
                            <td>
                                <a href="?action=eliminar&id=<?php echo $r->id; ?>">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach;     
                    }?>
      
                </table>     
              
            </div>
        </div>
    </body>
</html>