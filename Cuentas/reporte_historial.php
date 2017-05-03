<?php

require('fpdf.php');
require('conexionR.php');
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
		
		$this->Rect($x,$y,$w,$h);

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

function Header()
{
    // Logo
    $this->Image('interAgro.jpg',10,10 );
    // Arial bold 15
    $this->SetFont('Arial','B',12);
    // Movernos a la derecha
    $this->Cell(80);

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
    $this->Ln(20);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}

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

	///$paciente= $_GET['id'];
	$con = new DB;
	$pacientes = $con->conectar();	
	
	//$strConsulta = "SELECT * from pacientes where id_paciente =  '$paciente'";

	$fD=date('d-m-Y',strtotime($fechaD));
	$fH=date('d-m-Y',strtotime($fechaH));

	$consulFech="SELECT nombre,f.id,total,tipo_pago,moneda,estado from facturador.comprobante f, facturador.cliente c where STR_TO_DATE(fecha,'%d-%m-%Y') between STR_TO_DATE('{$fD}','%d-%m-%Y') and STR_TO_DATE('{$fH}','%d-%m-%Y') and c.id=cliente_id";

	$consulAge="SELECT nombre,f.id,total,tipo_pago,moneda,estado 
	from facturador.comprobante f, facturador.cliente c 
	where c.Agente='{$agente}' and c.id=f.cliente_id";

	
/*	$agent = mysql_query($consulAge);
	
	$fila = mysql_fetch_array($agent);*/

	$pdf=new PDF('L','mm','Letter');
	//$pdf->Open();
	$pdf->AddPage();
	$pdf->SetMargins(20,20,20);
	$pdf->Ln(10);


    $pdf->SetFont('Arial','',12);
    //$pdf->Cell(0,6,'Clave: '.$fila['clave'],0,1);
	$pdf->Cell(0,6,'Corresponsal: '.$agente,0,1); //.' '.$fila['apellido_paterno'].' '.$fila['apellido_materno'],0,1);
	//$pdf->Cell(0,6,'Sexo: '.$fila['sexo'],0,1); 
	//$pdf->Cell(0,6,'Domicilio: '.$fila['domicilio'],0,1); 
	
	$pdf->Ln(10);
	
	$pdf->SetWidths(array(70, 20, 30, 20, 30, 30));
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(85,107,47);
    $pdf->SetTextColor(255);

		for($i=0;$i<1;$i++)
			{
				$pdf->Row(array('CLIENTE','FACTURA','MONTO','PAGO','MONEDA','ESTADO'));
			}
	
	$historial = $con->conectar();	
	/*$strConsulta = "SELECT consultas_medicas.fecha_consulta, consultas_medicas.consultorio, consultas_medicas.diagnostico, medicos.nombre_medico 
	FROM consultas_medicas 
	Inner Join pacientes ON consultas_medicas.id_paciente = pacientes.id_paciente 
	Inner Join medicos ON consultas_medicas.id_medico = medicos.id_medico
	WHERE pacientes.id_paciente = '$paciente'";*/
	
	$historial = mysql_query($consulAge);
	$numfilas = mysql_num_rows($historial);
	
	for ($i=0; $i<$numfilas; $i++)
		{
			$fila = mysql_fetch_array($historial);
			$pdf->SetFont('Arial','',10);
			
			if($i%2 == 1)
			{
				$pdf->SetFillColor(153,255,153);
    			$pdf->SetTextColor(0);
				$pdf->Row(array($fila['nombre'], $fila['id'], $fila['total'],$fila['tipo_pago'],$fila['moneda'], $fila['estado']));
			}
			else
			{
				$pdf->SetFillColor(102,204,51);
    			$pdf->SetTextColor(0);
				$pdf->Row(array($fila['nombre'], $fila['id'], $fila['total'],$fila['tipo_pago'],$fila['moneda'], $fila['estado']));
			}
		}
ob_end_clean(); 
$pdf->Output();
?>