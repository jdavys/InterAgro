<?php require_once("conexionR.php");?>
<?php require_once("includes/function.php");?>
<?php require_once("includes/consultas.php");?>
<?php
$datos = array("detalleP"=>"","fechaD"=>"","fechaH"=>"");



date_default_timezone_set('America/Costa_Rica');

setlocale(LC_ALL, 'Spanish_Costa_Rica');



if(isset($_POST["detalleP"])){
    $prod=$_POST["detalleP"];
    $datos['detalleP']= $prod; 
}


if(isset($_POST["fechaD"])){
    $fechaD=$_POST["fechaD"];
    $datos["fechaD"]= $fechaD; 
}

if(isset($_POST["fechaH"])){
    $fechaH=$_POST["fechaH"];
    $datos["fechaH"]= $fechaH;
}



$consultaRep=generaConsultaRepProd($datos);
//var_dump($consultaRep);

$con = new DB;


//Llamada al script fpdf
require('fpdf.php');

$archivo="Producto-$prod.pdf";


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
        
    
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(0,6,'FECHA: '.strftime(" %d - %m - %Y"),0,1);
    
    $pdf->Ln(2); 
    
    
    $historial = $con->conectar();  
    $historial = mysql_query($consultaRep);
    $numfilasH = mysql_num_rows($historial);
    $cantT=0;

    $nombreP="SELECT name from facturador.Product
                             where name='{$prod}'";
                 $nom=$con->conectar();
                 $nom=mysql_query($nombreP);
                 $fila=mysql_fetch_array($nom);
                 $pdf->SetFont('Arial','',12);
                 $pdf->Cell(0,6,'PRODUCTO: '.utf8_decode($fila['name']),0,1);
  
        //var_dump($fila);

       
        for ($i=0; $i < $numfilasH; $i++) //historico de la consulta general
            {
                $filaG = mysql_fetch_array($historial);

               
                
                $pdf->SetWidths(array(25,25, 30, 25, 25));
                $pdf->SetFont('Arial','B',10);
                $pdf->SetFillColor(85,107,47);
                $pdf->SetTextColor(255);
                $pdf->Row(array('Núm Fact','Fecha_Venta','Presentación','Cantidad','Precio Venta'));
                
                $cantT+=$filaG['cantidad'];
                    
                        

                    $pdf->SetFont('Arial','',10);
                    
                    if($i%2 == 1)
                    {
                        $pdf->SetFillColor(153,255,153);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array($filaG['id'],$filaG['fecha'],$filaG['descripcion'], $filaG['cantidad'],$filaG['price_out']));
                    }
                    else
                    {
                        $pdf->SetFillColor(102,204,51);
                        $pdf->SetTextColor(0);
                        $pdf->Row(array($filaG['id'],$filaG['fecha'],$filaG['descripcion'], $filaG['cantidad'],$filaG['price_out']));
                    }

            }// fin del for historico
            $pdf->Ln(2);
            $pdf->SetFont('Arial','',14);
            $pdf->SetTextColor(10);
            $pdf->Cell(0,6,'CANTIDAD TOTAL VENDIDA : '.$cantT,0,1,'R');
   
    

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
    


