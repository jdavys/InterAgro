<?php require_once("conexionR.php");?>
<?php require_once("includes/function.php");?>
<?php require_once("includes/consultas.php");?>
<?php
$datos = array("tipo"=>"","gestor"=>"","cliente"=>"","estadoF"=>"","moneda"=>"","fechaD"=>"","fechaH"=>"");


date_default_timezone_set('America/Costa_Rica');

setlocale(LC_ALL, 'Spanish_Costa_Rica');



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



$consultaRep=generaConsultaRep($datos);

//print_r($consultaRep);

$con = new DB;

/*
$consulTodo="SELECT * from comprobante";
$respT=mysql_query($consulTodo);
$todo=mysql_fetch_array($respT);*/
/*$con = new DB;

$fD=date('d-m-Y',strtotime($fechaD));
$fH=date('d-m-Y',strtotime($fechaH));

$consulFech="SELECT nombre,f.id,total,tipo_pago,moneda,estado from facturador.comprobante f, facturador.cliente c where STR_TO_DATE(fecha,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and STR_TO_DATE('{$fH}','%d-%m-%Y') and c.id=cliente_id";

$consulAge="SELECT nombre,f.id,total,tipo_pago,moneda,estado,Agente 
from facturador.comprobante f, facturador.cliente c 
where c.Agente='{$agente}' and c.id=f.cliente_id";

$consultCli="SELECT id_comprobante,fecha_inicio,fecha_vence,moneda,monto_inicial,saldo_Factura 
from facturador.cuenta_cobrar 
where id_Cliente={$cli}  and moneda='{$moneda}' and estado='{$numFact}'";



//string(1) "t" string(1) "t" string(1) "a" string(1) "t" string(0) "" string(0) "" age-todo-sin fecha
//string(1) "t" string(1) "t" string(1) "a" string(13) "DANIEL CASTRO" string(0) "" string(0) "" agente-nom-sinf
//string(1) "c" string(2) "44" string(1) "a" string(13) "DANIEL CASTRO" string(0) "" string(0) "" facturas cancelas string(1) "p" pendientes*/

/*if(mysql_query($consulFech)){
    var_dump($numFact);
    var_dump($cli);
    var_dump($tipoRepo);
    var_dump($agente);
    var_dump($fechaD);
    var_dump($fechaH);
}*/

 



//Llamada al script fpdf

require('fpdf.php');

$archivo="factura-$numFact.pdf";


$archivo_de_salida=$archivo;


class PDF extends FPDF
{

var $widths;
var $aligns;

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=7*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        
        //$this->Rect($x,$y,$w,$h);

        $this->MultiCell($w,5,$data[$i],0,$a,'true');
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}
// Cabecera de página
function Header()
{
    // Logo
    $this->Image('interAgro.jpg',10,10 );
    // Arial bold 15
    $this->SetFont('Arial','B',12);
    // Movernos a la derecha
   

    $this->SetXY(50, 10);
    $this->MultiCell(100,5, 
    "INTER Soluciones Agropecuarias de CR S.A\n".
    "Cédula jurídica 3-101-196052 \n".
    "San Nicolás, Cartago, Costa Rica"."\n".
    "Bodega, La Virgen, Sarapiquí, Heredia"."\n".
    "Teléfono: +(506) 2761-2200, +(506) 2573-0903"."\n".
    "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
     "C", // texto justificado 
     false);
    
    // Salto de línea
    $this->Ln(10);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function FancyTable($header, $data)
{
    // Colores, ancho de línea y fuente en negrita
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    //$this->SetDrawColor(128,0,0);
    //$this->SetLineWidth(.3);
    $this->SetFont('','B');
    // Cabecera
    $w = array(40, 35, 45, 40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    // Restauración de colores y fuentes
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    // Datos
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Línea de cierre
    $this->Cell(array_sum($w),0,'','T');
}
}

    // Creación del objeto de la clase heredada
    $pdf=new PDF;
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetMargins(10,10,10);
        

    /*$pdf->Cell(0,6,'Clave: '.$fila['clave'],0,1);
    $pdf->Cell(0,6,'DESDE: '.$fD."  HASTA:".$fH,0,1); //.' '.$fila['apellido_paterno'].' '.$fila['apellido_materno'],0,1);
    $pdf->Cell(0,6,'Sexo: '.$fila['sexo'],0,1); 
    $pdf->Cell(0,6,'Domicilio: '.$fila['domicilio'],0,1); */
    //setlocale(LC_ALL,"es_ES");
      
    $status=$datos['estadoF'];
    if($status=='t'){
        $status='TODO';
    }

        $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,6,'FECHA: '.strftime(" %d - %m - %Y"),0,1);
    $pdf->Cell(0,6,'ESTADO: '.$status,0,1);
    $pdf->Ln(2);
   
    /*$pdf->SetWidths(array(20,20,28, 25, 15, 20, 25,35));
    $pdf->SetFont('Arial','B',10);
    $pdf->SetFillColor(85,107,47);
    $pdf->SetTextColor(255);*/



    /*for($i=0;$i<1;$i++)
    {
        //$pdf->Row(array('CLIENTE','FACTURA','MONTO','PAGO','MONEDA','ESTADO'));
        $pdf->Row(array('Tipo Doct','Núm Fact','Fecha_Emisión','Fecha_Vence','Días','Moneda','Monto Inicial','Monto_Pendiente'));
    }*/
    
    $historial = $con->conectar();  
    $historial = mysql_query($consultaRep);
    $numfilasH = mysql_num_rows($historial);

  
    

    if( $datos['gestor'] != "t" &&  $datos['gestor'] != "n"){

        $totalCC=0;// totales general
        $totalDC=0;
        for ($i=0; $i < $numfilasH; $i++) //historico de la consulta general
            {
                $filaG = mysql_fetch_array($historial);

                $totalCC=0;// totales general
                $totalDC=0;
                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(0,6,'CLIENTE: '.utf8_decode($filaG['name']),0,1);
                $pdf->SetWidths(array(20,20,28, 25, 15, 20, 25,35));
                $pdf->SetFont('Arial','B',10);
                $pdf->SetFillColor(85,107,47);
                $pdf->SetTextColor(255);
                $pdf->Row(array('Tipo Doct','Núm Fact','Fecha_Emisión','Fecha_Vence','Atraso','Moneda','Monto Inicial','Monto_Pendiente'));

                $cueCli="SELECT * from cuenta_cobrar, cliente
             where  id_Cliente={$filaG['id_Cliente']} and estado='{$filaG['estado']}' and id=id_cliente";

            
                $histCli = $con->conectar();  
                $histCli = mysql_query($cueCli);
                $numfilasHC = mysql_num_rows($histCli);

                for ($j=0; $j < $numfilasHC; $j++){

                    $fila = mysql_fetch_array($histCli);

                    // filto por estado
                    if($fila['estado']=='CANCELADO'){
                        if($fila['moneda']==='COLONES'){
                            $totalCC+=$fila['monto_Inicial'];
                        }else{
                            $totalDC+=$fila['monto_Inicial'];
                        }
                    }else if($fila['estado']=='PENDIENTE'){
                        if($fila['moneda']==='COLONES'){
                            $totalCC+=$fila['saldo_Factura'];
                        }else{
                            $totalDC+=$fila['saldo_Factura'];
                        }
                    }
                    
                    $fechaVt=$fila['fecha_Vence'];
                    $fechaV= date("m-d-y",strtotime($fila['fecha_Vence']));
                    $fecha=date("m-d-y");
                    if(strtotime($fechaVt) > strtotime("now")){
                        $dias=0;
                    }else{
                        $dias = (strtotime($fechaVt) - strtotime("now")) /86400;
                        $dias   = abs($dias); 
                        $dias = floor($dias);  
                    }
                    
                        

                    $pdf->SetFont('Arial','',10);
                    
                    if($i%2 == 1)
                    {
                        $pdf->SetFillColor(153,255,153);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array('FAC', $fila['id_Comprobante'],$fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                    }
                    else
                    {
                        $pdf->SetFillColor(102,204,51);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array('FAC', $fila['id_Comprobante'], $fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                        //$pdf->Row(array($fila['nombre'], $fila['id'], $fila['total'],$fila['tipo_pago'],$fila['moneda'], $fila['estado']));
                    }

                }//for consulta filto cliente 

                if($fila['estado']=='CANCELADO'){
                        $pdf->SetFont('Arial','',14);
                        $pdf->SetTextColor(10);
                        $pdf->Cell(0,6,'TOTAL PAGADO C/: '.number_format($totalCC,2),0,1,'R');
                        $pdf->Cell(0,6,'TOTAL PAGADO $/: '.number_format($totalDC,2),0,1,'R');
                    }else if($fila['estado']=='PENDIENTE'){
                        $pdf->SetTextColor(10);
                        $pdf->Cell(0,6,'TOTAL PENDIENTE C/: '.number_format($totalCC,2),0,1,'R');
                        $pdf->Cell(0,6,'TOTAL PENDIENTE $/: '.number_format($totalDC,2),0,1,'R');
                    }
               
                

            }// fin del for historico
    }else{
        $totalC=0;// totales general
        $totalD=0;
        if($datos['cliente'] != "t" && $datos['cliente'] >0){
/*             $nombreCli="SELECT name from facturador.person 
                         where id={$cli}";
             $nom=$con->conectar();
             $nom=mysql_query($nombreCli);
             $filaC=mysql_fetch_array($nom);
             $pdf->SetFont('Arial','',12);
             $pdf->Cell(0,6,'CLIENTE: '.utf8_decode($filaC['name']),0,1);

             $pdf->SetWidths(array(20,20,28, 25, 15, 20, 25,35));
            $pdf->SetFont('Arial','B',10);
            $pdf->SetFillColor(85,107,47);
            $pdf->SetTextColor(255);
            $pdf->Row(array('Tipo Doct','Núm Fact','Fecha_Emisión','Fecha_Vence','Atraso','Moneda','Monto Inicial','Monto_Pendiente'));
            for ($i=0; $i<$numfilasH; $i++)
            {
                $filaG = mysql_fetch_array($historial);
                
                

                $cueCli="SELECT * from cuenta_cobrar, person where  id_Cliente={filaG['id_Cliente']} and estado='{$filaG['estado']}' and person.id=cuenta_cobrar.id_Cliente";
            
                $histCli = $con->conectar();  
                $histCli = mysql_query($cueCli);
                $numfilasHC = mysql_num_rows($histCli);

                for ($j=0; $j < $numfilasHC; $j++){

                    $fila = mysql_fetch_array($histCli);

                    // filto por estado
                    if($fila['estado']==='CANCELADO'){
                    if($fila['moneda']==='COLONES'){
                        $totalC+=$fila['monto_Inicial'];
                    }else{
                        $totalD+=$fila['monto_Inicial'];
                    }
                }else if($fila['estado']==='PENDIENTE'){
                    if($fila['moneda']==='COLONES'){
                        $totalC=$fila['saldo_Global'];
                    }else{
                        $totalD=$fila['saldo_global_dolares'];
                    }
                }

                $fechaVt=$fila['fecha_Vence'];
                $fechaV= date("m-d-y",strtotime($fila['fecha_Vence']));
                $fecha=date("m-d-y");
                if(strtotime($fechaVt) > strtotime("now")){
                    $dias=0;
                }else{
                    $dias = (strtotime($fechaVt) - strtotime("now")) /86400;
                    $dias   = abs($dias); 
                    $dias = floor($dias);  
                }
                

                $pdf->SetFont('Arial','',10);
                
                if($i%2 == 1)
                {
                    $pdf->SetFillColor(153,255,153);
                    $pdf->SetTextColor(0);
                    $pdf->Row(array('FAC', $fila['id_Comprobante'],$fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                }
                else
                {
                    $pdf->SetFillColor(102,204,51);
                    $pdf->SetTextColor(0);
                    $pdf->Row(array('FAC', $fila['id_Comprobante'], $fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                    //$pdf->Row(array($fila['nombre'], $fila['id'], $fila['total'],$fila['tipo_pago'],$fila['moneda'], $fila['estado']));
                }*/

                $filaG = mysql_fetch_array($historial);
                //$idC=$filaG['id_cliente'];


                $totalCC=0;// totales general
                $totalDC=0;

                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(0,6,'CLIENTE: '.utf8_decode($filaG['name']),0,1);
                $pdf->SetWidths(array(20,20,28, 25, 15, 20, 25,35));
                $pdf->SetFont('Arial','B',10);
                $pdf->SetFillColor(85,107,47);
                $pdf->SetTextColor(255);
                $pdf->Row(array('Tipo Doct','Núm Fact','Fecha_Emisión','Fecha_Vence','Atraso','Moneda','Monto Inicial','Monto_Pendiente'));
                if($datos["estadoF"]=="t"){
                    $cueCli="SELECT * from cuenta_cobrar, person where  id_Cliente={$filaG['id_Cliente']}  and person.id=cuenta_cobrar.id_Cliente";
                }else{
                $cueCli="SELECT * from cuenta_cobrar, person where  id_Cliente={$filaG['id_Cliente']} and estado='{$filaG['estado']}' and person.id=cuenta_cobrar.id_Cliente";
                }
                $histCli = $con->conectar();  
                $histCli = mysql_query($cueCli);
                $numfilasHC = mysql_num_rows($histCli);

                print_r($numfilasHC);

                for ($j=0; $j < $numfilasHC; $j++){

                    $fila = mysql_fetch_array($histCli);

                    // filto por estado
                    if($fila['estado']=='CANCELADO'){
                        if($fila['moneda']==='COLONES'){
                            $totalCC+=$fila['monto_Inicial'];
                        }else{
                            $totalDC+=$fila['monto_Inicial'];
                        }
                    }else if($fila['estado']=='PENDIENTE'){
                        if($fila['moneda']==='COLONES'){
                            $totalCC+=$fila['saldo_Factura'];
                        }else{
                            $totalDC+=$fila['saldo_Factura'];
                        }
                    }
                    
                    $fechaVt=$fila['fecha_Vence'];
                    $fechaV= date("m-d-y",strtotime($fila['fecha_Vence']));
                    $fecha=date("m-d-y");
                    if(strtotime($fechaVt) > strtotime("now")){
                        $dias=0;
                    }else{
                        $dias = (strtotime($fechaVt) - strtotime("now")) /86400;
                        $dias   = abs($dias); 
                        $dias = floor($dias);  
                    }
                    
                        

                    $pdf->SetFont('Arial','',10);
                    
                    if($j%2 == 1)
                    {
                        $pdf->SetFillColor(153,255,153);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array('FAC', $fila['id_Comprobante'],$fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                    }
                    else
                    {
                        $pdf->SetFillColor(102,204,51);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array('FAC', $fila['id_Comprobante'], $fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                        //$pdf->Row(array($fila['nombre'], $fila['id'], $fila['total'],$fila['tipo_pago'],$fila['moneda'], $fila['estado']));
                    }

            }

            
            //$tot=$con->conectar();
            //$tot=mysql_query($consulTot);
            //$fila=mysql_fetch_array($tot);
           if($fila['estado']=='CANCELADO'){
                                   $pdf->SetFont('Arial','',14);
                                   $pdf->SetTextColor(10);
                                   $pdf->Cell(0,6,'TOTAL PAGADO C/: '.number_format($totalCC,2),0,1,'R');
                                   $pdf->Cell(0,6,'TOTAL PAGADO $/: '.number_format($totalDC,2),0,1,'R');
                               }else if($fila['estado']=='PENDIENTE'){
                                   $pdf->SetFont('Arial','',14);
                                   $pdf->SetTextColor(10);
                                   $pdf->Cell(0,6,'TOTAL PENDIENTE C/: '.number_format($totalCC,2),0,1,'R');
                                   $pdf->Cell(0,6,'TOTAL PENDIENTE $/: '.number_format($totalDC,2),0,1,'R');
                               }
        }
        else
        {

            $totalCC=0;// totales general
            $totalDC=0;
            $idc=0;


        for ($i=0; $i < $numfilasH; $i++) //historico de la consulta general
            {
                $filaG = mysql_fetch_array($historial);
                //$idC=$filaG['id_cliente'];


                $totalCC=0;// totales general
                $totalDC=0;
                $totalCCP=0;// totales general
                $totalDCP=0;

                $pdf->SetFont('Arial','B',12);
                $pdf->Cell(0,6,'CLIENTE: '.utf8_decode($filaG['name']),0,1);
                $pdf->SetWidths(array(20,20,28, 25, 15, 20, 25,35));
                $pdf->SetFont('Arial','B',10);
                $pdf->SetFillColor(85,107,47);
                $pdf->SetTextColor(255);
                $pdf->Row(array('Tipo Doct','Núm Fact','Fecha_Emisión','Fecha_Vence','Atraso','Moneda','Monto Inicial','Monto_Pendiente'));
                
                $cueCli="SELECT * from cuenta_cobrar, person where  id_Cliente={$filaG['id_Cliente']} and person.id=cuenta_cobrar.id_Cliente";
            
                $histCli = $con->conectar();  
                $histCli = mysql_query($cueCli);
                $numfilasHC = mysql_num_rows($histCli);

                for ($j=0; $j < $numfilasHC; $j++){

                    $fila = mysql_fetch_array($histCli);

                    // filto por estado
                    if($fila['estado']=='CANCELADO'){
                        if($fila['moneda']==='COLONES'){
                            $totalCC+=$fila['monto_Inicial'];
                        }else{
                            $totalDC+=$fila['monto_Inicial'];
                        }
                    }else if($fila['estado']=='PENDIENTE'){
                        if($fila['moneda']==='COLONES'){
                            $totalCCP+=$fila['saldo_Factura'];
                        }else{
                            $totalDCP+=$fila['saldo_Factura'];
                        }
                    }
                    
                    $fechaVt=$fila['fecha_Vence'];
                    $fechaV= date("m-d-y",strtotime($fila['fecha_Vence']));
                    $fecha=date("m-d-y");
                    if(strtotime($fechaVt) > strtotime("now")){
                        $dias=0;
                    }else{
                        $dias = (strtotime($fechaVt) - strtotime("now")) /86400;
                        $dias   = abs($dias); 
                        $dias = floor($dias);  
                    }
                    
                        

                    $pdf->SetFont('Arial','',10);
                    
                    if($j%2 == 1)
                    {
                        $pdf->SetFillColor(153,255,153);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array('FAC', $fila['id_Comprobante'],$fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                    }
                    else
                    {
                        $pdf->SetFillColor(102,204,51);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array('FAC', $fila['id_Comprobante'], $fila['fecha_Inicio'],$fila['fecha_Vence'],$dias,$fila['moneda'], $fila['monto_Inicial'],$fila['saldo_Factura']));
                        //$pdf->Row(array($fila['nombre'], $fila['id'], $fila['total'],$fila['tipo_pago'],$fila['moneda'], $fila['estado']));
                    }

                }//for consulta filto cliente 

                
               if($fila['estado']=='CANCELADO'){
                        $pdf->SetFont('Arial','',14);
                        $pdf->SetTextColor(10);
                        $pdf->Cell(0,6,'TOTAL PAGADO C/: '.number_format($totalCC,2),0,1,'R');
                        $pdf->Cell(0,6,'TOTAL PAGADO $/: '.number_format($totalDC,2),0,1,'R');
                    }else if($fila['estado']=='PENDIENTE'){
                        $pdf->SetFont('Arial','',14);
                        $pdf->SetTextColor(10);
                        $pdf->Cell(0,6,'TOTAL PENDIENTE C/: '.number_format($totalCCP,2),0,1,'R');
                        $pdf->Cell(0,6,'TOTAL PENDIENTE $/: '.number_format($totalDCP,2),0,1,'R');
                    }
                

            }// fin del for historico
        }


        
    }
    

ob_end_clean(); 
$pdf->Output();

    
header ("Content-Type: application/download");
header ("Content-Disposition: attachment; filename=$archivo");
header("Content-Length: " . filesize("$archivo"));
$fp = fopen($archivo, "r");
fpassthru($fp);
fclose($fp);

    
unlink($archivo);

?>
    


