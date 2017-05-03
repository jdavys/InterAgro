<?php require_once("conexionR.php");?>
<?php require_once("includes/function.php");?>
<?php require_once("includes/consultas.php");?>
<?php
$datos = array("tipo"=>"cl","gestor"=>"n","cliente"=>"t","estadoF"=>"PENDIENTE","moneda"=>"t","fechaD"=>"","fechaH"=>"");


date_default_timezone_set('America/Costa_Rica');

setlocale(LC_ALL, 'Spanish_Costa_Rica');



/*if(isset($_POST["tipo"])){
    $tipoRepo=$_POST["tipo"];
    $datos['tipo']= $tipoRepo; 
}else{

    $datos['tipo']= "cl"; 
}

if(isset($_POST["gestor"])){
    $agente=$_POST["gestor"];
    $datos['gestor']= $agente; 
}else{
    $agente="n";
    $datos['gestor']= $agente; 
}

if(isset($_POST["cliente"])){
   $cli=$_POST["cliente"];
   $datos["cliente"]=$cli;
}else{
    $cli=0;
    $datos["cliente"]=$cli;
}

if(isset($_POST["factura"])){
    $numFact=$_POST["factura"];
    $datos["estadoF"]= $numFact;
}else{
    $numFact=0;
    $datos["estadoF"]= $numFact;
}

if(isset($_POST["tmoneda"])){
    $moneda=$_POST["tmoneda"];
    $datos["moneda"]= $moneda;
}else{
    $moneda="n";
    $datos["moneda"]= $moneda;
}


if(isset($_POST["fechaD"])){
    $fechaD=$_POST["fechaD"];
    $datos["fechaD"]= $fechaD; 
}

if(isset($_POST["fechaH"])){
    $fechaH=$_POST["fechaH"];
    $datos["fechaH"]= $fechaH;
}*/


//print_r($datos);
$consultaRep=generaConsultaRep($datos);

//print_r($consultaRep);

$con = new DB;

    $historial = $con->conectar();  
    $historial = mysql_query($consultaRep);
    $numfilasH = mysql_num_rows($historial);

    //$fila = mysql_fetch_array($historial);
  //  print_r($historial);
    //print_r($numfilasH);
    
    //print_r($fila);

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
            <th style="text-align:left;">Fecha Inicio</th>
            <th style="text-align:left;">Fecha Vence</th>
            <th style="text-align:left;">Moneda</th>
            <th style="text-align:left;">Monto Inicial</th>
            <th style="text-align:left;">Saldo Factura</th>
            <th style="text-align:left;">Saldo Global Col</th>
            <th style="text-align:left;">Saldo Global Dol</th>
            
        </tr>
    </thead>   


    <?php while($fila=mysql_fetch_array($historial,MYSQL_NUM)){ ?>
        <tr>
        <td><?php echo $fila[13]; ?></td>
        <td><?php echo $fila[1]; ?></td>
            <td><?php echo $fila[2]; ?></td>
            <td><?php echo $fila[3]; ?></td>
            <td><?php echo $fila[9]; ?></td>
            <td><?php echo $fila[4]; ?></td>
            <td><?php echo $fila[6]; ?></td>
            <td><?php echo $fila[7]; ?></td>
            <td><?php echo $fila[8]; ?></td>
            
            
        </tr>
    <?php } ?>
</table>   