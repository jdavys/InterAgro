
<?php
require_once '/Model/equipo.entidad.php';
require_once '/Model/equipo.model.php';

// Logica
$eq = new Equipo();
$model = new EquipoModel();

if(isset($_REQUEST['action']))
{
  switch($_REQUEST['action'])
  {
    case 'actualizar':
      $eq->__SET('id_equipo',       $_REQUEST['id_equipo']);
      $eq->__SET('descripcion',     $_REQUEST['descripcion']);
      $eq->__SET('cantidad',        $_REQUEST['cantidad']);
      

      $model->Actualizar($eq);
      header('Location: index.php');
      break;

    case 'registrar':
      $eq->__SET('id_equipo',       $_REQUEST['id_equipo']);
      $eq->__SET('descripcion',     $_REQUEST['descripcion']);
      $eq->__SET('cantidad',        $_REQUEST['cantidad']);

      $model->Registrar($eq);
      header('Location: index.php');
    break;

    case 'eliminar':
      $eq->__SET('id_equipo',       $_REQUEST['id_equipo']);
      $model->Eliminar($eq);
      header('Location: index.php');
      break;

    case 'editar':
      $eq = $model->Obtener($_REQUEST['id_equipo']);
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
  var desc = document.getElementById("descripcion");
  var cant = document.getElementById("cantidad");

  if(desc=""){
    des.style.display = 'block';
  }
  if(cant=""){
    can.style.display = 'block';
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
<tr><td><td><td ><div ><a href="menu.php"><img src ="images/icono_atras.png" heigth="30px" width=="50px"></a></div></tr></td></td></tr>
</table>
 
</div>

<div id="content_wrapper" >
  <div id="content" style="height:300px;">
 
          <div >
           <form action="?action=<?php echo $eq->id_equipo > 0 ? 'actualizar' : 'registrar'; ?>" method="post" style="margin-bottom:30px;">
                    <input type="hidden" name="id_equipo" value="<?php echo $eq->__GET('id_equipo'); ?>" />
                    
                    <table style="width:800px;" align="center" >
                        <tr><td></td></tr>
                        <tr style="text-align:left;">
                            <th><font  size="2;">Descripción</font></th>
                            <td><input type="text" name="descripcion" value="<?php echo $eq->__GET('descripcion'); ?>" style="width:300px;" required="required"/><font color="red" size="2" >*</font></td>
                            <td></tr></td></td>
                        </tr>
                        <tr style="text-align:left;">
                            <th><font  size="2;">Cantidad</font></th>
                            <td><input type="number" name="cantidad" value="<?php echo $eq->__GET('cantidad'); ?>" style="width:180px;" required="required"/><label><font color="red" size="2">*     </font></label>
                            </td>
                        </tr>
                        <tr><td height="20px"></td></tr><tr><td><button type="submit"  style="width:80px; background-color:#2F4F4F; border:none; color:#FFFFFF; height:30px; width:70px;">Guardar</button></td></tr>
                        <tr><td height="40px;"></td></tr>
                        <tr><td colspan="4"><font color="red" size="2" id="msj" >No existen equipos agregados</font></td></tr>
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
