<?php

$format=$_POST['formato'];
if($format<1){

$numFact=$_POST["comprob_id"];
$cli=$_POST["cliente_id"];
$tPago = $_POST["p"];
$idPro = $_POST["idProd"];
$Vplazo= $_POST["pz"];
$iva=$_POST["iv"];
$descuent=0;
$tipoF=$_POST['tipoFact'];
$tipoC=$_POST['t'];
$fec=$_POST['fecH'];
$fecPlazo=$_POST['fecV'];



$c=mysql_connect("localhost","root","123456");
mysql_select_db("facturador");


if($tPago=='Contado'){
    $sSQL="UPDATE comprobante Set Tipo_pago='Contado' Where id='$numFact'";
    mysql_query($sSQL);
}else if($tPago=='Credito'){
    $sSQL="UPDATE comprobante Set Tipo_pago='Credito', fechaVence=$fecPlazo Where id='$numFact'";
    mysql_query($sSQL);
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}

if($tipoC=='COLONES'){
    $sSQL="UPDATE comprobante Set Moneda='COLONES' Where id='$numFact'";
    mysql_query($sSQL);
}else if($tipoC=='DOLARES'){
    $sSQL="UPDATE comprobante Set Moneda='DOLARES' Where id='$numFact'";
    mysql_query($sSQL);
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}

$sSQLFec="UPDATE comprobante Set fecha='$fec' Where id='$numFact'";
mysql_query($sSQLFec);

$sSQLFec="UPDATE comprobante Set fechaVence='$fecPlazo' Where id='$numFact'";
mysql_query($sSQLFec);

$orden1="SELECT Nombre,Direccion,RUC,comprobante.id
FROM cliente,comprobante
WHERE cliente.id=comprobante.Cliente_id
AND comprobante.id=$numFact";
$paquete1=mysql_query($orden1);
$reg1=mysql_fetch_array($paquete1);

$orden2="SELECT producto_id,nombre,presenta,precioUnitario,cantidad,total,descuento,cantidadExiste
FROM producto,comprobante_detalle
WHERE comprobante_detalle.Producto_id=producto.id
AND comprobante_detalle.Comprobante_id=$numFact";
$paquete2=mysql_query($orden2);


//Llamada al script fpdf
require('fpdf.php');

if($tipoF=="normal"){
//variable que guarda el nombre del archivo PDF
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
    "C�dula jur�dica 3-101-196052 \n".
    "San Nicol�s, Cartago, Costa Rica"."\n".
    "Bodega, La Virgen, Sarapiqu�, Heredia"."\n".
    "Tel�fono: +(506) 2761-2200, +(506) 2573-0903"."\n".
    "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
     "C", // texto justificado 
     false);

    
    $pdf->SetFont('Arial','',12);
    $top_datos=40;
    $pdf->SetXY(80, $top_datos);
    $pdf->Cell(190, 10, "FACTURA", 0, 2, "C");
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(190,5, "Numero de factura: ".$reg1[3]."\n".
        "Fecha: ".$fec."\n".
        "Moneda: ".$tipoC."\n".
        "Tipo de Pago: ".$tPago."\n".
        "Plazo: ". $Vplazo."\n".
        "Fecha de Vence: ".$fecPlazo."\n",
         0, "C", false);
    $pdf->Ln(2);

    // Datos del cliente
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(20, 60);
    $pdf->Cell(190, 10, "Datos del cliente:", 0, 2);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(
    190, 
    5, 
    "Nombre: ".htmlspecialchars($reg1[0])."\n".
    "Direccion: ".$reg1[1]."\n".
    "Telefono: ".$reg1[2]."\n",
    0, // bordes 0 = no | 1 = si
    "J", // texto justificado
    false);
    
    $pdf->Ln(2);


    $top_productos = 100;
       $pdf->SetXY(8, $top_productos);
                   $pdf->Cell(20, 5, 'CANTIDAD', 0, 1, 'C');
                   $pdf->SetXY(38, $top_productos);
                   $pdf->Cell(45, 5, 'DESCRIPCION', 0, 1, 'C');
                   $pdf->SetXY(80, $top_productos);
                   $pdf->Cell(40, 5, 'PRESENTACION', 0, 1, 'C');
                   $pdf->SetXY(110, $top_productos);
                   $pdf->Cell(40, 5, 'PRECIO UNIDAD', 0, 1, 'C');    
                   $pdf->SetXY(140, $top_productos);
                   $pdf->Cell(40, 5, 'DESCUENTO', 0, 1, 'C');
                   $pdf->SetXY(160, $top_productos);
                   $pdf->Cell(40, 5, 'TOTAL', 0, 1, 'C');
     
    $precio_subtotal = 0; 
    $y = 115; 
    $x=0;


    while ($reg2=mysql_fetch_array($paquete2, MYSQL_NUM)) {
        
        
        $pdf->SetFont('Arial','',10);
        if($tipoC=='DOLARES'){
                $pdf->SetXY(5, $y);
                $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                $pdf->SetXY(35, $y);
                $pdf->Cell(45, 5, htmlspecialchars($reg2[1]), 0, 1, 'C');
                $pdf->SetXY(90, $y);
                $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                $pdf->SetXY(110, $y);
                $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                $pdf->SetXY(140, $y);
                $pdf->Cell(40, 5,($reg2[6]/100)*$reg2[5], 0, 1, 'C');
                $pdf->SetXY(160, $y);
                $pdf->Cell(40, 5, number_format($reg2[5]-(($reg2[6]/100)*$reg2[5]),2), 0, 1, 'C');
        }else if($tipoC=='COLONES'){
                $pdf->SetXY(5, $y);
                $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                $pdf->SetXY(35, $y);
                $pdf->Cell(45, 5, $reg2[1], 0, 1, 'C');
                $pdf->SetXY(90, $y);
                $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                $pdf->SetXY(110, $y);
                $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                $pdf->SetXY(140, $y);
                $pdf->Cell(40, 5,($reg2[6]/100)*$reg2[5], 0, 1, 'C');
                $pdf->SetXY(160, $y);
                $pdf->Cell(40, 5, number_format($reg2[5]-(($reg2[6]/100)*$reg2[5]),2), 0, 1, 'C');
        }else{
            echo '<script language="javascript">alert("ERROR");</script>'; 
        }  

        
        $precio_subtotal += $reg2[3] * $reg2[4];
        $descuent+=($reg2[6]/100)*$reg2[5];
        $x++;
        // aumento del top 5 cm
        $y = $y + 4; 

       
    }

   


    //Cálculo del Impuesto
    $add_iva = $precio_subtotal * $iva / 100;

    //Cálculo del precio total
    $total_mas_iva = $precio_subtotal-$descuent+$add_iva;    
    $y = $y + 25; 

    
    $pdf->SetFont('Arial','',10);
    if($tipoC=='DOLARES'){
       
        $pdf->SetXY(150, $y);
        $pdf->Cell(190, 5, "Subtotal:     $/   ".number_format($precio_subtotal,2), 0, 1, "J");
        $pdf->SetXY(150, $y+8);
        $pdf->Cell(190, 5, "I.V.A:           $/   ".$add_iva, 0, 1, "J");
        $pdf->SetXY(150, $y+16);
        $pdf->Cell(190, 5, "Descuento:  $/   ".number_format($descuent,2), 0, 1, "J");
        $pdf->SetFont('Arial','B',12);
        $pdf->SetXY(150, $y+24);
        $pdf->Cell(190, 5, "TOTAL:    $/  ".number_format($total_mas_iva,2), 0, 1, "J");
    }else if($tipoC=='COLONES'){
        $pdf->SetXY(150, $y);
        $pdf->Cell(190, 5, "Subtotal:     C/   ".number_format($precio_subtotal,2), 0, 1, "J");
        $pdf->SetXY(150, $y+8);
        $pdf->Cell(190, 5, "I.V.A:           C/   ".$add_iva, 0, 1, "J");
        $pdf->SetXY(150, $y+16);
        $pdf->Cell(190, 5, "Descuento:  C/   ".number_format($descuent,2), 0, 1, "J");
        $pdf->SetFont('Arial','B',12);
        $pdf->SetXY(150, $y+24);
        $pdf->Cell(190, 5, "TOTAL:    C/  ".number_format($total_mas_iva,2), 0, 1, "J");
    }else{
        echo '<script language="javascript">alert("ERROR");</script>'; 
    }



  


    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(5, 240);
    $pdf->Cell(190, 5, "Recibido por: ______________________  Hecho por: ______________________", 0, 1, "L");
    $pdf->Ln(2);
    
    $pdf->Ln(2);

    $pdf->SetXY(5, 257);
    $pdf->Cell(190, 5,
    "Hacer todos los cheques pagaderos a INTER Soluciones Agropecuarias de CR S.A",0,1,"L");
    $pdf->Cell(190, 5,"Autorizo por administraci�n tributaria seg�n resoluci�n #11-97 de la Gaceta #171 del 12 de agosto de 1997",0,1,"C");
    $pdf->Ln(2);
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(190, 5, "GRACIAS POR SU CONFIANZA EN NOSOTROS", 0, 1, "C");


    ob_end_clean();
    $pdf->Output();

    
    header ("Content-Type: application/download");
    header ("Content-Disposition: attachment; filename=$archivo");
    header("Content-Length: " . filesize("$archivo"));
    $fp = fopen($archivo, "r");
    fpassthru($fp);
    fclose($fp);

    
    unlink($archivo);

}else{


$pdf=new FPDF('P','mm',array(58,150));  

$pdf->AddPage();  




// Datos de la tienda
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(0, 5);
$pdf->MultiCell(55,
"INTER Soluciones Agropecuarias de CR S.A\n".
    "C�dula jur�dica 3-101-196052 \n".
    "San Nicol�s, Cartago, Costa Rica"."\n".
    "Bodega, La Virgen, Sarapiqu�, Heredia"."\n".
    "Tel�fono: +(506) 2761-2200, +(506) 2573-0903"."\n".
    "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
     "C",false);

$pdf->SetFont('Arial','B',6);
$pdf->SetXY(0, 35);
$pdf->Cell(55,5,"FACTURA #: ".$reg1[3],0,1,'C');
$pdf->SetXY(0, 40);
$pdf->Cell(55,5,"FECHA : ".date('d/m/Y H:i:s'),0,1,'C');
$pdf->Ln(2);
$pdf->SetXY(3, 46);

$pdf->SetFont('Arial','',6);
$pdf->MultiCell(
55, 
3, 
"Nombre: ".$reg1[0]."\n".
"Direccion: ".$reg1[1]."\n".
"Telefono: ".$reg1[2]."\n",
0, // bordes 0 = no | 1 = si
"J", // texto justificado
false);

$pdf->Ln(2);

$pdf->SetFont('Arial','',5);
$top_productos = 60;
    $pdf->SetXY(4, $top_productos);
    $pdf->Cell(5, 5, 'CANT', 0, 1, 'L');

    $pdf->SetXY(12, $top_productos);
    $pdf->Cell(5, 5, 'DESC', 0, 1, 'L'); 

    $pdf->SetXY(42, $top_productos);
    $pdf->Cell(5, 5, 'TOTAL', 0, 1, 'L');
    
    
$pdf->SetXY(5, 65);
$pdf->Cell(5, 5, '---------------------------------------------------------------------------', 0, 1, 'L');   

$precio_subtotal = 0; // variable para almacenar el subtotal
$y = 70; 
$x=0;

while ($reg2=mysql_fetch_array($paquete2, MYSQL_NUM)) {
   
    $pdf->SetFont('Arial','',6);
    if($tipoC>0){   
        $pdf->SetXY(3, $y);
        $pdf->Cell(1, 5,$reg2[4], 0, 1);

        $pdf->SetXY(6, $y);
        $pdf->Cell(5, 5, $reg2[1], 0, 1);

        $pdf->SetXY(30, $y);
        $pdf->Cell(3, 5, $reg2[2], 0, 1);

        $pdf->SetXY(40, $y);
        $pdf->Cell(5, 5, number_format($reg2[5],2), 0, 1);
    }else if($tipoC==0){
        $pdf->SetXY(3, $y);
        $pdf->Cell(5, 5,$reg2[4], 0, 1);

        $pdf->SetXY(6, $y);
        $pdf->Cell(5, 5, $reg2[1], 0, 1);

        $pdf->SetXY(30, $y);
        $pdf->Cell(2,5, $reg2[2], 0, 1);

        $pdf->SetXY(40, $y);
        $pdf->Cell(5, 5, number_format($reg2[5],2), 0, 1);
    }else{
        echo '<script language="javascript">alert("ERROR");</script>'; 
    }
    
    $descuent+=($reg2[6]/100)*$reg2[5];
    $precio_subtotal += $reg2[3] * $reg2[4];
    $x++;
      
        // aumento del top 5 cm
    $y = $y + 3;

      $sSQLCant="UPDATE producto Set cantidadExiste={$reg2[7]}-{$reg2[4]} Where id={$reg2[0]}";
        mysql_query($sSQLCant);
}





$add_iva = $precio_subtotal * $iva / 100;

$total_mas_iva = $precio_subtotal-$descuent+$add_iva;

$pdf->Ln(2);


$pdf->SetFont('Arial','',6);

if($tipoC>0){
   
    $pdf->SetXY(25, $y+5);
    $pdf->Cell(40, 5, "Subtotal:    $/  ".number_format($precio_subtotal,2), 0, 1, "J");
    $pdf->SetXY(25, $y+10);
    $pdf->Cell(40, 5, "I.V.A:         $/  ".$add_iva, 0, 1, "J");
    $pdf->SetXY(25, $y+15);
    $pdf->Cell(40, 5, "Descuento: $/  ".number_format($descuent,2), 0, 1, "J");
    $pdf->SetFont('Arial','B',7);
    $pdf->SetXY(25, $y+20);
    $pdf->Cell(40, 5, "TOTAL:  $/   ".number_format($total_mas_iva,2), 0, 1, "J");
}else if($tipoC==0){
   
    $pdf->SetXY(25, $y+5);
    $pdf->Cell(40, 5, "Subtotal:    C/  ".number_format($precio_subtotal,2), 0, 1, "J");
    $pdf->SetXY(25, $y+10);
    $pdf->Cell(40, 5, "I.V.A:         C/  ".$add_iva, 0, 1, "J");
    $pdf->SetXY(25, $y+15);
    $pdf->Cell(40, 5, "Descuento: C/  ".number_format($descuent,2), 0, 1, "J");
    $pdf->SetFont('Arial','B',7);
    $pdf->SetXY(25, $y+20);
    $pdf->Cell(40, 5, "TOTAL:  C/   ".number_format($total_mas_iva,2), 0, 1, "J");
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}




$pdf->SetFont('Arial','',6);

$pdf->Ln(2);

$pdf->SetXY(2,$y+35);
$pdf->MultiCell(55, 3,"Autorizo por administraci�n tributaria"."\n".
                    "seg�n resoluci�n #11-97 de la Gaceta #171"."\n".
                    "del 12 de agosto de 1997",0,"C",false);
$pdf->Ln(2);
$pdf->Ln(2);

$pdf->SetFont('Arial','B',5);
$pdf->Cell(35, 3, "GRACIAS POR SU CONFIANZA EN NOSOTROS", 0, 1, "C");

$pdf->Output();

}

//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<CAMBIO>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

}else{

$numFact=$_POST["comprob_id"];
$cli=$_POST["cliente_id"];
$tPago = $_POST["pago"];
$idPro = $_POST["idProd"];
$Vplazo= $_POST["plazo"];
$iva=$_POST["impuestoV"];
$descuent=0;
$tipoF=$_POST['tipoFact'];
$tipoC=$_POST['tipoCamb'];

date_default_timezone_set('America/Costa_Rica');

$fec=date('d-m-Y');
$fecPlazo=date('d-m-Y',strtotime("+$Vplazo day"));


$c=mysql_connect("localhost","root","123456");
mysql_select_db("facturador");

if($tPago==='Contado'){
    $sSQL="UPDATE comprobante Set Tipo_pago='Contado' Where id='$numFact'";
    mysql_query($sSQL);
}else if($tPago==='Credito'){
    $sSQL="UPDATE comprobante Set Tipo_pago='Credito', fechaVence='$fecPlazo' Where id='$numFact'";
    mysql_query($sSQL);
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}

if($tipoC=='DOLARES'){
    $sSQL="UPDATE comprobante Set Moneda='DOLARES' Where id='$numFact'";
    mysql_query($sSQL);
}else if($tipoC=='COLONES'){
    $sSQL="UPDATE comprobante Set Moneda='COLONES' Where id='$numFact'";
    mysql_query($sSQL);
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}

$sSQLFec="UPDATE comprobante Set fecha='$fec' Where id='$numFact'";
mysql_query($sSQLFec);

$sSQLFec="UPDATE comprobante Set fechaVence='$fecPlazo' Where id='$numFact'";
mysql_query($sSQLFec);

$orden1="SELECT Nombre,Direccion,RUC,comprobante.id
FROM cliente,comprobante
WHERE cliente.id=comprobante.Cliente_id
AND comprobante.id=$numFact";
$paquete1=mysql_query($orden1);
$reg1=mysql_fetch_array($paquete1);

$orden2="SELECT producto_id,nombre,presenta,precioUnitario,cantidad,total,descuento,cantidadExiste
FROM producto,comprobante_detalle
WHERE comprobante_detalle.Producto_id=producto.id
AND comprobante_detalle.Comprobante_id=$numFact";
$paquete2=mysql_query($orden2);

if($tPago==='Credito'){
        $iSQL="INSERT INTO cuenta_cobrar (id_Cliente,id_Comprobante,fecha_Inicio,fecha_Vence,moneda) 
                values($cli,$numFact,'$fec','$fecPlazo','$tipoC')";
        mysql_query($iSQL);
}

//Llamada al script fpdf
require('fpdf.php');

if($tipoF=="normal"){
//variable que guarda el nombre del archivo PDF
    $archivo="factura-$numFact.pdf";

    $archivo_de_salida=$archivo;

    $pdf=new FPDF();  //crea el objeto
    $pdf->AddPage();  


    //logo de la tienda
    $pdf->Image('interAgro.jpg',10,10 );


    // Datos de la tienda
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(50, 10);
    $pdf->MultiCell(100, 
    5, 
    "INTER Soluciones Agropecuarias de CR S.A\n".
    "C�dula jur�dica 3-101-196052 \n".
    "San Nicol�s, Cartago, Costa Rica"."\n".
    "Bodega, La Virgen, Sarapiqu�, Heredia"."\n".
    "Tel�fono: +(506) 2761-2200, +(506) 2573-0903"."\n".
    "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
     "C", // texto justificado 
     false);

    // Encabezado de la factura

    
        $pdf->SetFont('Arial','',12);
        $top_datos=40;
        $pdf->SetXY(80, $top_datos);
        $pdf->Cell(190, 10, "FACTURA", 0, 2, "C");
        $pdf->SetFont('Arial','B',10);
        $pdf->MultiCell(190,5, "Numero de factura: ".$reg1[3]."\n".
            "Fecha: ".$fec."\n".
            "Tipo de Pago: ".$tPago."\n".
            "Moneda: ".$tipoC."\n".
            "Plazo: ". $Vplazo."\n".
            "Fecha de Vence: ".$fecPlazo."\n",
             0, "C", false);
        $pdf->Ln(2);

    
       
    // Datos del cliente
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(20, 60);
    $pdf->Cell(190, 10, "Datos del cliente:", 0, 2);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(
    190, 
    5, 
    "Nombre: ".htmlspecialchars($reg1[0])."\n".
    "Direccion: ".$reg1[1]."\n".
    "Telefono: ".$reg1[2]."\n",
    0, 
    "J", 
    false);
    
    $pdf->Ln(2);


    
    $top_productos = 100;
        $pdf->SetXY(8, $top_productos);
        $pdf->Cell(20, 5, 'CANTIDAD', 0, 1, 'C');
        $pdf->SetXY(38, $top_productos);
        $pdf->Cell(45, 5, 'DESCRIPCION', 0, 1, 'C');
        $pdf->SetXY(80, $top_productos);
        $pdf->Cell(40, 5, 'PRESENTACION', 0, 1, 'C');
        $pdf->SetXY(110, $top_productos);
        $pdf->Cell(40, 5, 'PRECIO UNIDAD', 0, 1, 'C');    
        $pdf->SetXY(140, $top_productos);
        $pdf->Cell(40, 5, 'DESCUENTO', 0, 1, 'C');
        $pdf->SetXY(160, $top_productos);
        $pdf->Cell(40, 5, 'TOTAL', 0, 1, 'C');
     
    $precio_subtotal = 0;
    $y = 115; 
    $x=0;


    while ($reg2=mysql_fetch_array($paquete2, MYSQL_NUM)) {
       
        
        $pdf->SetFont('Arial','',10);
        if($tipoC>0){
                $pdf->SetXY(5, $y);
                $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                $pdf->SetXY(35, $y);
                $pdf->Cell(45, 5, htmlspecialchars($reg2[1]), 0, 1, 'C');
                $pdf->SetXY(90, $y);
                $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                $pdf->SetXY(110, $y);
                $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                $pdf->SetXY(140, $y);
                $pdf->Cell(40, 5,($reg2[6]/100)*$reg2[5], 0, 1, 'C');
                $pdf->SetXY(160, $y);
                $pdf->Cell(40, 5, number_format($reg2[5]-(($reg2[6]/100)*$reg2[5]),2), 0, 1, 'C');
        }else if($tipoC==0){
                $pdf->SetXY(5, $y);
                $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                $pdf->SetXY(35, $y);
                $pdf->Cell(45, 5, $reg2[1], 0, 1, 'C');
                $pdf->SetXY(90, $y);
                $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                $pdf->SetXY(110, $y);
                $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                $pdf->SetXY(140, $y);
                $pdf->Cell(40, 5,($reg2[6]/100)*$reg2[5], 0, 1, 'C');
                $pdf->SetXY(160, $y);
                $pdf->Cell(40, 5, number_format($reg2[5]-(($reg2[6]/100)*$reg2[5]),2), 0, 1, 'C');
        }else{
            echo '<script language="javascript">alert("ERROR");</script>'; 
        }  
       
        $precio_subtotal += $reg2[3] * $reg2[4];
        $descuent+=($reg2[6]/100)*$reg2[5];
        $x++;

        
        $y = $y + 4; 

        $sSQLCant="UPDATE producto Set cantidadExiste={$reg2[7]}-{$reg2[4]} Where id={$reg2[0]}";
        mysql_query($sSQLCant);
       
    }


    
    $add_iva = $precio_subtotal * $iva / 100.0;

    
    $total_mas_iva = $precio_subtotal-$descuent+$add_iva;

    if($tPago=='Credito'){
        $iSQL="UPDATE cuenta_cobrar SET monto_Inicial={$total_mas_iva}, saldo_Factura= {$total_mas_iva}
                WHERE id_Comprobante={$numFact}";

        mysql_query($iSQL);

        $recibo= "SELECT * from cuenta_cobrar where id_cliente= {$cli}";

        $saldoG=0;

        if (mysql_query($recibo)){

            $respuesta=mysql_query($recibo);
            while($cuenta = mysql_fetch_array($respuesta)){
                $saldoG+=$cuenta['saldo_Factura'];
            }

        }

        $iSQL2="UPDATE cuenta_cobrar SET saldo_Global= {$saldoG}
                WHERE id_cliente={$cli}";
                
        mysql_query($iSQL2);
    }

    
     $y = $y + 25; 

    $pdf->SetFont('Arial','',10);
    if($tipoC=='DOLARES'){
        $pdf->SetXY(150, $y);
        $pdf->Cell(190, 5, "Subtotal:     $/   ".number_format($precio_subtotal,2), 0, 1, "J");
        $pdf->SetXY(150, $y+8);
        $pdf->Cell(190, 5, "I.V.A:           $/   ".$add_iva, 0, 1, "J");
        $pdf->SetXY(150, $y+16);
        $pdf->Cell(190, 5, "Descuento:  $/   ".number_format($descuent,2), 0, 1, "J");
        $pdf->SetFont('Arial','B',12);
        $pdf->SetXY(150, $y+24);
        $pdf->Cell(190, 5, "TOTAL:    $/  ".number_format($total_mas_iva,2), 0, 1, "J");
    }else if($tipoC=='COLONES'){
        $pdf->SetXY(150, $y);
        $pdf->Cell(190, 5, "Subtotal:     C/   ".number_format($precio_subtotal,2), 0, 1, "J");
        $pdf->SetXY(150, $y+8);
        $pdf->Cell(190, 5, "I.V.A:           C/   ".$add_iva, 0, 1, "J");
        $pdf->SetXY(150, $y+16);
        $pdf->Cell(190, 5, "Descuento:  C/   ".number_format($descuent,2), 0, 1, "J");
        $pdf->SetFont('Arial','B',12);
        $pdf->SetXY(150, $y+24);
        $pdf->Cell(190, 5, "TOTAL:    C/  ".number_format($total_mas_iva,2), 0, 1, "J");
    }else{
        echo '<script language="javascript">alert("ERROR");</script>'; 
    }


    $pdf->SetFont('Arial','',10);
    $pdf->SetXY(5, 230);
    $pdf->Cell(190, 5, "Recibido por: ______________________  Hecho por: ______________________ ", 0, 1, "L");
    $pdf->Ln(2);

    $pdf->SetXY(5, 255);
    $pdf->Cell(190, 5,
    "Hacer todos los cheques pagaderos a INTER Soluciones Agropecuarias de CR S.A",0,1,"L");
    $pdf->Cell(190, 5,"Autorizo por administraci�n tributaria seg�n resoluci�n #11-97 de la Gaceta #171 del 12 de agosto de 1997",0,1,"C");
    $pdf->Ln(2);
    
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(190, 5, "GRACIAS POR SU CONFIANZA EN NOSOTROS", 0, 1, "C");


    $pdf->Output();

    
    header ("Content-Type: application/download");
    header ("Content-Disposition: attachment; filename=$archivo");
    header("Content-Length: " . filesize("$archivo"));
    $fp = fopen($archivo, "r");
    fpassthru($fp);
    fclose($fp);

    
    unlink($archivo);

}else{


$pdf=new FPDF('P','mm',array(58,150));  //crea el objeto
$pdf->AddPage(); 


// Datos de la tienda
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(0, 5);
$pdf->MultiCell(55, 
5, 
"INTER Soluciones Agropecuarias de CR S.A\n".
    "C�dula jur�dica 3-101-196052 \n".
    "San Nicol�s, Cartago, Costa Rica"."\n".
    "Bodega, La Virgen, Sarapiqu�, Heredia"."\n".
    "Tel�fono: +(506) 2761-2200, +(506) 2573-0903"."\n".
    "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
     "C", false);

$pdf->SetFont('Arial','B',6);
$pdf->SetXY(0, 35);
$pdf->Cell(55,5,"FACTURA #: ".$reg1[3],0,1,'C');
$pdf->SetXY(0, 40);
$pdf->Cell(55,5,"FECHA : ".date('d/m/Y H:i:s'),0,1,'C');
$pdf->Ln(2);
$pdf->SetXY(3, 46);

$pdf->SetFont('Arial','',6);
$pdf->MultiCell(
55, 
3, 
"Nombre: ".htmlspecialchars($reg1[0])."\n".
"Direccion: ".$reg1[1]."\n".
"Telefono: ".$reg1[2]."\n",
0, 
"J", 
false);

$pdf->Ln(2);

$pdf->SetFont('Arial','',5);
$top_productos = 60;
    $pdf->SetXY(4, $top_productos);
    $pdf->Cell(5, 5, 'CANT', 0, 1, 'L');

    $pdf->SetXY(12, $top_productos);
    $pdf->Cell(5, 5, 'DESC', 0, 1, 'L'); 

    $pdf->SetXY(42, $top_productos);
    $pdf->Cell(5, 5, 'TOTAL', 0, 1, 'L');
    
    
$pdf->SetXY(5, 65);
$pdf->Cell(5, 5, '---------------------------------------------------------------------------', 0, 1, 'L');   

$precio_subtotal = 0; 
$y = 70; 
$x=0;

while ($reg2=mysql_fetch_array($paquete2, MYSQL_NUM)) {
    
    $pdf->SetFont('Arial','',6);
    if($tipoC>0){   
        $pdf->SetXY(3, $y);
        $pdf->Cell(1, 5,$reg2[4], 0, 1);

        $pdf->SetXY(6, $y);
        $pdf->Cell(5, 5, $reg2[1], 0, 1);

        $pdf->SetXY(30, $y);
        $pdf->Cell(3, 5, $reg2[2], 0, 1);

        $pdf->SetXY(40, $y);
        $pdf->Cell(5, 5, number_format($reg2[5],2), 0, 1);
    }else if($tipoC==0){
        $pdf->SetXY(3, $y);
        $pdf->Cell(5, 5,$reg2[4], 0, 1);

        $pdf->SetXY(6, $y);
        $pdf->Cell(5, 5, $reg2[1], 0, 1);

        $pdf->SetXY(30, $y);
        $pdf->Cell(2,5, $reg2[2], 0, 1);

        $pdf->SetXY(40, $y);
        $pdf->Cell(5, 5, number_format($reg2[5],2), 0, 1);
    }else{
        echo '<script language="javascript">alert("ERROR");</script>'; 
    }
    
    $descuent+=($reg2[6]/100)*$reg2[5];
    $precio_subtotal += $reg2[3] * $reg2[4];
    $x++;
      
        // aumento del top 5 cm
    $y = $y + 3;

    $sSQLCant="UPDATE producto Set cantidadExiste={$reg2[7]}-{$reg2[4]} Where id={$reg2[0]}";
    mysql_query($sSQLCant);
}






$add_iva = $precio_subtotal * $iva / 100;

$total_mas_iva = $precio_subtotal-$descuent+$add_iva;

$pdf->Ln(2);

$pdf->SetFont('Arial','',6);

if($tiptipoCamboC=='DOLARES'){
   
    $pdf->SetXY(25, $y+5);
    $pdf->Cell(40, 5, "Subtotal:    $/  ".number_format($precio_subtotal,2), 0, 1, "J");
    $pdf->SetXY(25, $y+10);
    $pdf->Cell(40, 5, "I.V.A:         $/  ".$add_iva, 0, 1, "J");
    $pdf->SetXY(25, $y+15);
    $pdf->Cell(40, 5, "Descuento: $/  ".number_format($descuent,2), 0, 1, "J");
    $pdf->SetFont('Arial','B',7);
    $pdf->SetXY(25, $y+20);
    $pdf->Cell(40, 5, "TOTAL:  $/   ".number_format($total_mas_iva,2), 0, 1, "J");
}else if($tipoCamb=='COLONES'){
   
    $pdf->SetXY(25, $y+5);
    $pdf->Cell(40, 5, "Subtotal:    C/  ".number_format($precio_subtotal,2), 0, 1, "J");
    $pdf->SetXY(25, $y+10);
    $pdf->Cell(40, 5, "I.V.A:         C/  ".$add_iva, 0, 1, "J");
    $pdf->SetXY(25, $y+15);
    $pdf->Cell(40, 5, "Descuento: C/  ".number_format($descuent,2), 0, 1, "J");
    $pdf->SetFont('Arial','B',7);
    $pdf->SetXY(25, $y+20);
    $pdf->Cell(40, 5, "TOTAL:  C/   ".number_format($total_mas_iva,2), 0, 1, "J");
}else{
    echo '<script language="javascript">alert("ERROR");</script>'; 
}




$pdf->SetFont('Arial','',6);

$pdf->Ln(2);
$pdf->Ln(2);
$pdf->Ln(2);
$pdf->SetXY(2,$y+35);
$pdf->MultiCell(55, 3,"Autorizo por administraci�n tributaria"."\n".
                    "seg�n resoluci�n #11-97 de la Gaceta #171"."\n".
                    "del 12 de agosto de 1997",0,"C",false);
$pdf->Ln(2);
$pdf->Ln(2);

$pdf->SetFont('Arial','B',5);
$pdf->Cell(35, 3, "GRACIAS POR SU CONFIANZA EN NOSOTROS", 0, 1, "C");


$pdf->Output();

}

}