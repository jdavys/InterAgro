<?php

$numFact=$_POST["comprob_id"];
$cli=$_POST["cliente_id"];
$tPago = $_POST["pago"];
$idPro = $_POST["idProd"];
$Vplazo= $_POST["plazo"];
$tMoneda=$_POST["moneda"];

date_default_timezone_set('America/Costa_Rica');

$fec=date('d-m-Y');
$fecPlazo=date('d-m-Y',strtotime("+$Vplazo day"));

$c=mysql_connect("localhost","root","");
mysql_select_db("facturador");

if($tPago=='Contado'){
    $sSQL="UPDATE comprobante Set Tipo_pago='Contado' Where id='$numFact'";
    mysql_query($sSQL);
}else if($tPago='Credito'){
    $sSQL="UPDATE comprobante Set Tipo_pago='Credito' Where id='$numFact'";
    mysql_query($sSQL);
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}

if($tMoneda=='Colones'){
    $sSQL="UPDATE comprobante Set Moneda='Colones' Where id='$numFact'";
    mysql_query($sSQL);
}else if($tMoneda='Dolares'){
    $sSQL="UPDATE comprobante Set Moneda='Dolares' Where id='$numFact'";
    mysql_query($sSQL);
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}

$orden1="SELECT Nombre,Direccion,RUC,comprobante.id
FROM cliente,comprobante
WHERE cliente.id=comprobante.Cliente_id
AND comprobante.id=$numFact";
$paquete1=mysql_query($orden1);
$reg1=mysql_fetch_array($paquete1);

$orden2="SELECT nombre,precioUnitario,cantidad,total
FROM producto,comprobante_detalle
WHERE comprobante_detalle.Producto_id=producto.id
AND comprobante_detalle.Comprobante_id=$numFact";
$paquete2=mysql_query($orden2);

$orden3="SELECT presentacion from producto
WHERE id=$idPro";
$paquete3=mysql_query($orden3);

//Llamada al script fpdf
require('fpdf.php');




$pdf=new FPDF('P','mm',array(100,150));  //crea el objeto
$pdf->AddPage();  //a�adimos una p�gina. Origen coordenadas, esquina superior izquierda, posici�n por defeto a 1 cm de los bordes.




// Datos de la tienda
$pdf->SetFont('Arial','B',8);
$pdf->SetXY(0, 5);
$pdf->MultiCell(100, //posici�n X
5, //posici�n Y
"INTER Soluciones Agropecuarias de CR S.A\n".
"C�dula jur�dica 3-101-196052 \n".
"San Nicol�s, Cartago, Costa Rica"."\n".
"Bodega, La Virgen, Sarapiqu�, Heredia"."\n".
"Tel�fono: +(506) 2761-2200, +(506) 2573-0903"."\n".
"Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
 "C", false);

$pdf->SetFont('Arial','',8);
$pdf->SetXY(0, 40);
$pdf->Cell(100,5,"FACTURA #: ".$reg1[3],0,1,'C');
$pdf->SetXY(0, 45);
$pdf->Cell(100,5,"FECHA : ".date('d/m/Y H:i:s'),0,1,'C');
$pdf->Ln(2);


//Salto de l�nea
$pdf->Ln(2);


// extracci�n de los datos de los productos a trav�s de la funci�n explode
// $e_productos = explode(",", $productos);
// $e_unidades = explode(",", $unidades);
// $e_precio_unidad = explode(",", $precio_unidad);

//Creaci�n de la tabla de los detalles de los productos productos
$top_productos = 60;
    $pdf->SetXY(10, $top_productos);
    $pdf->Cell(5, 5, 'CANT', 0, 1, 'C');

    $pdf->SetXY(20, $top_productos);
    $pdf->Cell(20, 5, 'DESC', 0, 1, 'C'); 

    $pdf->SetXY(55, $top_productos);
    $pdf->Cell(55, 5, 'TOTAL', 0, 1, 'C');
    
    
$pdf->SetXY(5, 65);
$pdf->Cell(5, 5, '-------------------------------------------------------------------------------------', 0, 1, 'L');   

$precio_subtotal = 0; // variable para almacenar el subtotal
$y = 70; // variable para la posici�n top desde la cual se empezar�n a agregar los datos
$x=0;

while ($reg2=mysql_fetch_array($paquete2, MYSQL_NUM)) {
    
    $pdf->SetFont('Arial','',7);
       
    $pdf->SetXY(10, $y);
    $pdf->Cell(5, 5,$reg2[2], 0, 1, 'C');

    $pdf->SetXY(35, $y);
    $pdf->Cell(15, 5, $reg2[0], 0, 1, 'C');

    $pdf->SetXY(55, $y);
    $pdf->Cell(55, 5, $reg2[3], 0, 1, 'C');

    while ($reg3=mysql_fetch_array($paquete3, MYSQL_NUM)) {
        $pdf->SetFont('Arial','',8);
       
        $pdf->SetXY(48, $y);
        $pdf->Cell(48, 5, $reg3[0], 0, 1, 'C');
    }
    //C�lculo del subtotal  
    $precio_subtotal += $reg2[1] * $reg2[2];
    $x++;

    // aumento del top 5 cm
    $y = $y + 5;
}



mysql_close($c);


//C�lculo del Impuesto
//$add_iva = $precio_subtotal * $iva / 100;

//C�lculo del precio total
$total_mas_iva = round($precio_subtotal, 2);

$pdf->Ln(2);
$pdf->Ln(2);
$pdf->Ln(2);
$pdf->SetFont('Arial','',8);
$pdf->SetXY(75,$y+5);
$pdf->MultiCell(190, 5, "Subtotal: ".$precio_subtotal."\n".
                        "I.V.A: 0%"."\n".
                        "Descuento: 0"."\n".
                        "TOTAL: ".$total_mas_iva, 0,"J",false);



$pdf->Ln(2);
$pdf->Ln(2);
$pdf->Ln(2);
$pdf->SetFont('Arial','',8);
$pdf->MultiCell(190, 5,"Autorizo por administraci�n tributaria seg�n resoluci�n #11-97\n". "de la Gaceta #171 del 12 de agosto de 1997",0,"L",false);
$pdf->Ln(2);
$pdf->Ln(2);
$pdf->SetFont('Arial','B',8);
$pdf->Cell(190, 5, "GRACIAS POR SU CONFIANZA EN NOSOTROS", 0, 1, "L");

/*$pdf->SetFont('Arial','B',12);
$top_datos=300;
$pdf->SetXY(80, $top_datos);
$pdf->Cell(190, 10, "FACTURA", 0, 2, "C");
$pdf->SetFont('Arial','B',10);
$pdf->MultiCell(190,5, "N�mero de factura: ".$reg1[3]."\n".
    "Fecha: ".date('d/m/y')."\n".
    "Tipo Moneda: [Colones]  [Dolares]\n".
    "Tipo de Pago:[Contado]  [Credito]\n",
     0, "C", false);
$pdf->Ln(2);*/



$pdf->Output();//cierra el objeto pdf


