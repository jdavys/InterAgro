<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php

if(isset($_POST["factura"])){
    $numFact=$_POST["factura"];
}else{
    $numFact=0;
}

if(isset($_POST["cliente"])){
    $cli=$_POST["cliente"];
}else{
    $cli=0;
}

if(isset($_POST["tipo"])){
    $tipoRepo=$_POST["tipo"];
}

if(isset($_POST["gestor"])){
    $agente=$_POST["gestor"];
}else{
    $agente="n";
}

if(isset($_POST["fechaD"])){
    $fechaD=$_POST["fechaD"];
}else{
    $fechaD="n";
}

if(isset($_POST["fechaH"])){
    $fechaH=$_POST["fechaH"];
}else{
    $fechaH="n";
}

if(isset($_POST["tmoneda"])){
    $moneda=$_POST["tmoneda"];
}else{

}


/*
$consulTodo="SELECT * from comprobante";
$respT=mysql_query($consulTodo);
$todo=mysql_fetch_array($respT);*/

$fD=date('d-m-Y',strtotime($fechaD));
$fH=date('d-m-Y',strtotime($fechaH));

$consulFech="SELECT nombre,f.id,total,tipo_pago,moneda,estado from comprobante f, cliente c where STR_TO_DATE(fecha,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and STR_TO_DATE('{$fH}','%d-%m-%Y') and c.id=cliente_id";

$consultCli="SELECT nombre,f.id_comprobante,fecha_inicio,fecha_vence,moneda,monto_inicial,saldo_Factura 
from facturador.cuenta_cobrar f, facturador.cliente c 
where c.id={$cli} and c.id=f.id_cliente and moneda='{$moneda}' and estado='{$numFact}'";  

//Llamada al script fpdf
require('fpdf.php');

$archivo="factura-$numFact.pdf";


$archivo_de_salida=$archivo;

$pdf=new FPDF();  //crea el objeto
    
$pdf->AddPage();  


    //logo de la tienda
$pdf->Image('interAgro.jpg',10,10 );
// Datos de la tienda
$pdf->SetFont('Arial','B',12);
$pdf->SetXY(50, 10);
$pdf->MultiCell(100,5, 
"INTER Soluciones Agropecuarias de CR S.A\n".
"Cédula jurídica 3-101-196052 \n".
"San Nicolás, Cartago, Costa Rica"."\n".
"Bodega, La Virgen, Sarapiquí, Heredia"."\n".
"Teléfono: +(506) 2761-2200, +(506) 2573-0903"."\n".
"Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
 "C", // texto justificado 
 false);

$top_productos = 60;
$pdf->SetXY(8, $top_productos);

$pdf->Cell(40, 5, 'CLIENTE', 0, 1, 'L');
$pdf->SetXY(75, $top_productos);
$pdf->Cell(20, 5, 'FACTURA', 0, 1, 'L');
$pdf->SetXY(100, $top_productos);
$pdf->Cell(20, 5, 'TOTAL', 0, 1, 'L');
$pdf->SetXY(120, $top_productos);
$pdf->Cell(20, 5, 'TIPO', 0, 1, 'L');    
$pdf->SetXY(140, $top_productos);
$pdf->Cell(20, 5, 'MONEDA', 0, 1, 'L');
$pdf->SetXY(160, $top_productos);
$pdf->Cell(20, 5, 'ESTADO', 0, 1, 'L');


     

$y = 70; 
$x=0;

if(mysql_query($consultCli)){
    $respTF=mysql_query($consultCli);
    while ($todoF=mysql_fetch_array($respTF, MYSQL_NUM)) {
               
        $pdf->SetFont('Arial','',10);
        
        $pdf->SetXY(8, $y);
        $pdf->Cell(30, 5, utf8_decode($todoF[0]), 0, 1, 'L');
        $pdf->SetXY(85, $y);
        $pdf->Cell(7, 5, $todoF[1], 0, 1, 'L');
        $pdf->SetXY(100, $y);
        $pdf->Cell(15, 5, $todoF[2], 0, 1, 'L');
        $pdf->SetXY(120, $y);
        $pdf->Cell(15, 5, $todoF[3], 0, 1, 'L');
        $pdf->SetXY(140, $y);
        $pdf->Cell(10, 5, $todoF[4], 0, 1, 'L');
        $pdf->SetXY(160, $y);
        $pdf->Cell(20, 5, $todoF[5], 0, 1, 'L');
        
        $y = $y + 5; 
    }
   
}
   
    
$pdf->Output();

    
header ("Content-Type: application/download");
header ("Content-Disposition: attachment; filename=$archivo");
header("Content-Length: " . filesize("$archivo"));
$fp = fopen($archivo, "r");
fpassthru($fp);
fclose($fp);

    
unlink($archivo);

?>