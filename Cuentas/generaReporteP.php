<?php require_once("conexionR.php");?>
<?php require_once("includes/function.php");?>
<?php require_once("includes/consultas.php");?>
<?php
//$datos = array("detalleP"=>"","fechaD"=>"","fechaH"=>"");
/*$datos = array("tipo"=>"","gestor"=>"","cliente"=>"","estadoF"=>"","moneda"=>"","fechaD"=>"","fechaH"=>"");*/

if(isset($_POST["detalleP"])){
    $tipoRepo=$_POST["detalleP"];
    $datos['detalleP']= $tipoRepo; 
}


if(isset($_POST["tipo"])){
    $tipoRepo=$_POST["tipo"];
    $datos['tipo']= $tipoRepo; 
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
}


var_dump($datos);
$consultaRep=generaConsultaRep($datos);
var_dump($consultaRep);

/*$nombreP="SELECT nombre from facturador.Producto 
                             where nombre='{$detalleP}'";
                 $nom=$con->conectar();
                 $nom=mysql_query($nombreP);
                 $fila=mysql_fetch_array($nom);

                 var_dump($fila);*/
