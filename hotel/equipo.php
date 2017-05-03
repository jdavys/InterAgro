
<?php
require_once 'equipo.entidad.php';
require_once 'equipo.model.php';

// Logica
$eq = new Producto();
$model = new ProductoModel();

if(isset($_REQUEST['action']))
{
  switch($_REQUEST['action'])
  {
    case 'actualizar':
      $eq->__SET('id_Producto',       $_REQUEST['id_Producto']);
      $eq->__SET('nombre',     $_REQUEST['nombre']);
      $eq->__SET('precio',        $_REQUEST['precio']);
      $eq->__SET('presentacion',        $_REQUEST['presentacion']);
      

      $model->Actualizar($eq);
      header('Location: equipo.php');
      break;

    case 'registrar':
      $eq->__SET('id_Producto',       $_REQUEST['id_Producto']);
      $eq->__SET('nombre',     $_REQUEST['nombre']);
      $eq->__SET('precio',        $_REQUEST['precio']);
      $eq->__SET('presentacion',        $_REQUEST['presentacion']);

      $model->Registrar($eq);
      header('Location: equipo.php');
    break;

    case 'eliminar':
      $eq->__SET('id_Producto',       $_REQUEST['id_Producto']);
      $model->Eliminar($eq);
      header('Location: equipo.php');
      break;

    case 'editar':
      $eq = $model->Obtener($_REQUEST['id_Producto']);
      break;
  }
}
?>

<script type="text/javascript">
function esconde_div($id){
   var elemento = document.getElementById($id);
   elemento.style.display = 'none';
}
 
function visible_div($id){
   var elemento = document.getElementById($id);
   elemento.style.display = 'block';
}

function esconde_table($id){
   var elemento = document.getElementById($id);
   elemento.style.display = 'none';
}
 
function visible_table($id){
   var elemento = document.getElementById($id);
   elemento.style.display = 'block';
}

//validaciones
function valida(){
  var nomb = document.getElementById("nombre");
  var prec = document.getElementById("precio");
  var prese = document.getElementById("presentacion");

  if(nomb=""){
    nom.style.display = 'block';
  }
  if(prec=""){
    pre.style.display = 'block';
  }
  if(prese=""){
    pres.style.display = 'block';
  }

}
</script>


<!DOCTYPE html >
<html lang="es">
<head>
<title>Mantenimiento de Equipo</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href="css/jquery.ennui.contentslider.css" rel="stylesheet" type="text/css" media="screen,projection" />
</head>

<body>
<div ><!-- 1-->
  <div><!-- 2-->
    <div ><!-- 3-->
       <div><div ><div><div class="cs_article"><div ></div></div></div></div></div><!-- 3-->         
  </div><!-- 2-->
</div><!-- 1-->

<div id="menu_wrapper" >
<table align="center" width="800px">
<tr><td></td></tr><tr><td></td></tr><tr><td></td></tr>
<tr  align="center"><td> <label style="color:#FFFFE0; font-size:15pt;">Mantenimiento de Equipo</label></td>
</tr>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<tr><td><td><td ><div ><img src ="images/icono_atras.png" heigth="30px" width=="50px"></a></div></tr></td></td></tr>
</table>
 
</div>

<div id="content_wrapper" >
  <div id="content" style="height:300px;">
 
          <div >
           <form action="?action=<?php echo $eq->id_producto > 0 ? 'actualizar' : 'registrar'; ?>" method="post" style="margin-bottom:30px;">
                    <input type="hidden" name="id_producto" value="<?php echo $eq->__GET('id_producto'); ?>" />
                    
                    <table style="width:800px;" align="center" >
                        <tr><td></td></tr>
                        <tr style="text-align:left;">
                            <th><font  size="2;">Nombre</font></th>
                            <td><input type="text" name="nombre" value="<?php echo $eq->__GET('nombre'); ?>" style="width:300px;" required="required"/><font color="red" size="2" >*</font></td>
                            <td></tr></td></td>
                        </tr>
                        <tr style="text-align:left;">
                            <th><font  size="2;">Precio</font></th>
                            <td><input type="number" name="precio" value="<?php echo $eq->__GET('precio'); ?>" style="width:180px;" required="required"/><label><font color="red" size="2">*     </font></label>
                            </td>
                        </tr>
                        <tr style="text-align:left;">
                            <th><font  size="2;">Precio</font></th>
                            <td><input type="number" name="precio" value="<?php echo $eq->__GET('precio'); ?>" style="width:180px;" required="required"/><label><font color="red" size="2">*     </font></label>
                            </td>
                        </tr>
                        <tr><td height="20px"></td></tr><tr><td><button type="submit"  style="width:80px; background-color:#2F4F4F; border:none; color:#FFFFFF; height:30px; width:70px;">Guardar</button></td></tr>
                        <tr><td height="40px;"></td></tr>
                        <tr><td colspan="4"><font color="red" size="2" id="msj" >No existen Productos agregados</font></td></tr>
                        <tr>

                    </tr>

                    </table>
                   
                </form>

                 <table style="width:600px;"; border="1px"; align="center" id="etq">
                    <thead>
                        <tr bgcolor="#2F4F4F" >
                            <td width="150px" ><font face="arial;" size="2.5;" color="#FFFFFF" >Descripción</font></td>
                            <td width="120px"><font face="arial;" size="2.5;" color="#FFFFFF">Cantidad</font></td>
                            <td width="120px"><font face="arial;" size="2.5;" color="#FFFFFF">Acción</font></td>
                           
                        </tr>
                    </thead>
                    </table>
                    <script type="text/javascript">esconde_table("etq");</script>
                   
                    <?php foreach($model->Listar() as $r): ?>
                    <script type="text/javascript"> visible_table("etq"); esconde_div("msj");</script>
                    <table style="width:600px;"; border="0.8px"; align="center" >
                        <tr>
                            <td width="150px" ><font face="arial;" size="2;" color="#000000"><?php echo $r->__GET('descripcion'); ?></font></td>
                            <td width="120px"><font face="arial;" size="2;" color="#000000"><?php echo $r->__GET('cantidad'); ?></font></td>
                            
                            <td td width="120px"><font face="arial;" size="2;" color="#000000">
                                <a href="?action=editar&id_equipo=<?php echo $r->id_equipo; ?>">Editar</a>
                                <a href="?action=eliminar&id_equipo=<?php echo $r->id_equipo; ?>">Eliminar</a>
                            </font>
                            </td>
                          
                        </tr>
                    </table>
                    <?php endforeach; ?>
            
          </div>
        </div>
      
      </div>
      </div>
    </div>
  </div>
</div>


 

  <center>
   <h>Diseñado por J&Y</h>
  </center>
</div>
<!-- end of footer -->
</body>
</html>
