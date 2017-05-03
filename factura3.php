
<?php

$format=$_POST['formato'];
if($format<1){ // YA EXISTE LA FACTURA

$numFact=$_POST["comprob_id"];
$cli=$_POST["cliente_id"];
$tPago = $_POST["p"];
$idPro = $_POST["idProd"];
$Vplazo= $_POST["pz"];
$iva=$_POST["iv"];
$descuent=0;
$tipoF=$_POST['tipoFact'];
$tipoC=$_POST['moneda'];
//$fec=$_POST['fecH'];
//$fecPlazo=$_POST['fecV'];
$error = false;

$c=mysql_connect("localhost","root","");
mysql_select_db("facturador");

date_default_timezone_set('America/Costa_Rica');

$fec=date('d-m-Y');
$fecPlazo=date('d-m-Y',strtotime("+$Vplazo day"));

//Llamada al script fpdf
require('fpdf.php');

if($tipoF=="normal"){
//variable que guarda el nombre del archivo PDF

   $iSQL="UPDATE  facturador.prefactura set estado='FACTURADO' where id={$numFact}";
   mysql_query($iSQL);
   
   $iSQL2="INSERT into facturador.comprobante select * from prefactura where id={$numFact}";
    mysql_query($iSQL2);

    $sSQLFec="UPDATE comprobante Set fecha='$fec' Where id={$numFact}";
    mysql_query($sSQLFec);

    $sSQLFec="UPDATE comprobante Set fechaVence='$fecPlazo' Where id={$numFact}";
    mysql_query($sSQLFec);

     $iSQL3="INSERT into facturador.comprobante_detalle select * from detalle_prefactura where comprobante_id={$numFact}";
    mysql_query($iSQL3);

    /// FACTURA FINAL 
    $orden3="SELECT name,address1,phone1,comprobante.id
    FROM person,comprobante
    WHERE person.id=comprobante.Cliente_id
    AND comprobante.id=$numFact";
    $paquete3=mysql_query($orden3);
    $reg2=mysql_fetch_array($paquete3);

//,cantidadExiste,cantidadSobre
    $orden4="SELECT producto_id,name,comprobante_detalle.presenta,precioUnitario,cantidad,total,descuento
    FROM product,comprobante_detalle
    WHERE comprobante_detalle.Producto_id=product.id
    AND comprobante_detalle.Comprobante_id=$numFact";
    $paquete4=mysql_query($orden4);


    $orden5="SELECT *
    FROM operation_type";
    $operationType=mysql_query($orden5);

    if($Vplazo==0){
        $tPago='Contado';
        $iSQL="INSERT INTO cuenta_cobrar (id_Cliente,id_Comprobante,fecha_Inicio,fecha_Vence,moneda,tipo_pago) 
           values({$cli},{$numFact},'{$fec}','{$fecPlazo}','{$tipoC}','Credito')";
    }else{
        $iSQL="INSERT INTO cuenta_cobrar (id_Cliente,id_Comprobante,fecha_Inicio,fecha_Vence,moneda,tipo_pago) 
           values({$cli},{$numFact},'{$fec}','{$fecPlazo}','{$tipoC}','{$tPago}')";
    }
    
    mysql_query($iSQL);
    

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

    
    $pdf->SetFont('Arial','',12);
    $top_datos=40;
    $pdf->SetXY(85, $top_datos+4);
    $pdf->Cell(190, 10, "FACTURA", 0, 2, "C");
    $pdf->SetFont('Arial','B',10);
    $pdf->MultiCell(190,5, "Numero de factura: ".$reg2[3]."\n".
        "Fecha: ".$fec."\n".
        "Moneda: ".$tipoC."\n".
        "Tipo de Pago: ".$tPago."\n".
        "   Plazo: ". $Vplazo."\n".
        "Fecha de Vence: ".$fecPlazo."\n",
         0, "C", false);
    $pdf->Ln(2);

    // Datos del cliente
    $pdf->SetFont('Arial','B',12);
    $pdf->SetXY(10, 60);
    $pdf->Cell(190, 10, "Datos del cliente:", 0, 2);
    $pdf->SetFont('Arial','',9);
    $pdf->MultiCell(
    190, 
    5, 
    "Nombre: ".utf8_decode($reg2[0])."\n".
    "Direccion: ".utf8_decode($reg2[1])."\n".
    "Telefono: ".$reg2[2]."\n",
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


    while ($reg2=mysql_fetch_array($paquete4, MYSQL_NUM)) {
        
        
        $pdf->SetFont('Arial','',10);
        if($tipoC=='DOLARES'){
                $pdf->SetXY(5, $y);
                $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                $pdf->SetXY(35, $y);
                $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C');
                $pdf->SetXY(90, $y);
                $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                $pdf->SetXY(110, $y);
                $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                $pdf->SetXY(140, $y);
                $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                $pdf->SetXY(160, $y);
                $pdf->Cell(40, 5, number_format(($reg2[3]*$reg2[4])-(($reg2[6]/100)*($reg2[3]*$reg2[4])),2), 0, 1, 'C');
        }else if($tipoC=='COLONES'){
                $pdf->SetXY(5, $y);
                $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                $pdf->SetXY(35, $y);
                $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C');
                $pdf->SetXY(90, $y);
                $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                $pdf->SetXY(110, $y);
                $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                $pdf->SetXY(140, $y);
                $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                $pdf->SetXY(160, $y);
                $pdf->Cell(40, 5, number_format(($reg2[3]*$reg2[4])-(($reg2[6]/100)*($reg2[3]*$reg2[4])),2), 0, 1, 'C');
        }else{
            echo '<script language="javascript">alert("ERROR");</script>'; 
        }  
        $orden6="SELECT *
            FROM operation
            WHERE product_id={$reg2[0]}
            ORDER BY created_at";
            $product=mysql_query($orden6);
        
        $precio_subtotal += $reg2[3] * $reg2[4];
        $descuent+=($reg2[6]/100)*($reg2[3] * $reg2[4]);
        $x++;
        // aumento del top 5 cm
        $y = $y + 4; 

            $q=0;
                  

            while ($regOpe=mysql_fetch_array($product, MYSQL_NUM)) {

                if($regOpe[3]==1){ $q+=$regOpe[2]; }
                        else if($regOpe[3]==2){ $q+=(-$regOpe[2]); }
            }
                $sSQLOpe="INSERT into operation (product_id,q,operation_type_id,bodega_id)
                                VALUES({$reg2[0]},{$reg2[4]},2,1)";
                    mysql_query($sSQLOpe);

            if($q > 0){
                $canT=$q - $reg2[4];
                if($canT<0){

                    $sSQLCant="UPDATE producto Set cantidadExiste=0 Where id={$reg2[0]}";
                    mysql_query($sSQLCant);
                    $cantS=$canT * -1;
                    $sSQLCant="UPDATE producto Set cantidadSobre={$cantS} Where id={$reg2[0]}";
                    mysql_query($sSQLCant);
                }else{
                    $sSQLCant="UPDATE producto Set cantidadExiste={$canT} Where id={$reg2[0]}";
                    mysql_query($sSQLCant);
                    $sSQLOpe="INSERT into operation (product_id,q,operation_type,bodega_id)
                                VALUES({reg[0]},{$reg[4]},2,1)";
                    mysql_query($sSQLOpe);

                }
                
                
            }else{
                $sSQLCant="UPDATE producto Set cantidadSobre={$reg2[8]}+{$reg2[4]} Where id={$reg2[0]}";
                mysql_query($sSQLCant);
                            
            }

           
        }       

   


    //CÃ¡lculo del Impuesto
    $add_iva = $precio_subtotal * $iva / 100;

    //CÃ¡lculo del precio total
    $total_mas_iva = $precio_subtotal-$descuent+$add_iva;

    /*if($tPago=='Credito'){
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
    }*/

    $iSQL="UPDATE cuenta_cobrar SET monto_Inicial={$total_mas_iva}, saldo_Factura= {$total_mas_iva}
            WHERE id_Comprobante={$numFact}";
    mysql_query($iSQL);

    $reciboC= "SELECT * from cuenta_cobrar where id_cliente= {$cli} and tipo_pago='Credito' and moneda='COLONES'";

    $reciboD= "SELECT * from cuenta_cobrar where id_cliente= {$cli} and tipo_pago='Credito' and moneda='DOLARES'";
            
    $saldoG=0;
    $saldoD=0;

    if (mysql_query($reciboC)){

        $respuesta=mysql_query($reciboC);
        while($cuenta = mysql_fetch_array($respuesta)){
            $saldoG += $cuenta['saldo_Factura'];
        }

        $iSQL2="UPDATE cuenta_cobrar SET saldo_Global = {$saldoG}
                WHERE id_cliente={$cli}";
        mysql_query($iSQL2);

    }

    if (mysql_query($reciboD)){
               
        $respuesta=mysql_query($reciboD);
        while($cuenta = mysql_fetch_array($respuesta)){
            $saldoD+=$cuenta['saldo_Factura'];
        }

        $iSQL2="UPDATE cuenta_cobrar SET saldo_global_dolares = {$saldoD}
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
    $pdf->SetXY(5, 240);
    $pdf->Cell(190, 5, "Recibido por: ______________________  Hecho por: ______________________", 0, 1, "L");
    $pdf->Ln(2);
    
    $pdf->Ln(2);

    $pdf->SetXY(5, 257);
    $pdf->Cell(190, 5,
    "Hacer todos los cheques pagaderos a INTER Soluciones Agropecuarias de CR S.A",0,1,"L");
    $pdf->Cell(190, 5,"Autorizo por administración tributaria según resolución #11-97 de la Gaceta #171 del 12 de agosto de 1997",0,1,"C");
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

}else if ($tipoF=="ticket"){  ///FACTURA TICKET

    $iSQL2="INSERT into facturador.comprobante select * from prefactura where id={$numFact}";
    mysql_query($iSQL2);

     $iSQL3="INSERT into facturador.comprobante_detalle select * from detalle_prefactura where comprobante_id={$numFact}";
    mysql_query($iSQL3);


    /// FACTURA FINAL 
    $orden3="SELECT name,address1,phone1,comprobante.id
    FROM person,comprobante
    WHERE person.id=comprobante.Cliente_id
    AND comprobante.id=$numFact";
    $paquete3=mysql_query($orden3);
    $reg2=mysql_fetch_array($paquete3);


    $orden4="SELECT producto_id,name,presenta,precioUnitario,cantidad,total,descuento,cantidadExiste
    FROM product,comprobante_detalle
    WHERE comprobante_detalle.Producto_id=producto.id
    AND comprobante_detalle.Comprobante_id=$numFact";
    $paquete4=mysql_query($orden4);

$pdf=new FPDF('P','mm',array(58,150));  

$pdf->AddPage();  




// Datos de la tienda
$pdf->SetFont('Arial','B',6);
$pdf->SetXY(0, 5);
$pdf->MultiCell(55,
"INTER Soluciones Agropecuarias de CR S.A\n".
    "Cédula jurídica 3-101-196052 \n".
    "San Nicolás, Cartago, Costa Rica"."\n".
    "Bodega, La Virgen, Sarapiquí, Heredia"."\n".
    "Teléfono: +(506) 2761-2200, +(506) 2573-0903"."\n".
    "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
     "C",false);

$pdf->SetFont('Arial','B',6);
$pdf->SetXY(0, 35);
$pdf->Cell(55,5,"FACTURA #: ".$reg2[3],0,1,'C');
$pdf->SetXY(0, 40);
$pdf->Cell(55,5,"FECHA : ".date('d/m/Y H:i:s'),0,1,'C');
$pdf->Ln(2);
$pdf->SetXY(3, 46);

$pdf->SetFont('Arial','',6);
$pdf->MultiCell(
55, 
3, 
"Nombre: ".$reg2[0]."\n".
"Direccion: ".$reg2[1]."\n".
"Telefono: ".$reg2[2]."\n",
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

while ($reg2=mysql_fetch_array($paquete4, MYSQL_NUM)) {
   
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
$pdf->MultiCell(55, 3,"Autorizo por administración tributaria"."\n".
                    "según resolución #11-97 de la Gaceta #171"."\n".
                    "del 12 de agosto de 1997",0,"C",false);
$pdf->Ln(2);
$pdf->Ln(2);

$pdf->SetFont('Arial','B',5);
$pdf->Cell(35, 3, "GRACIAS POR SU CONFIANZA EN NOSOTROS", 0, 1, "C");

$pdf->Output();

}else{ if ($tipoF=="prefact"){ //FACTURA PROFORMA

    $fec=$_POST['fecH'];
    $fecPlazo=$_POST['fecV'];

    $orden1="SELECT name,address1,phone1,prefactura.id
    FROM person,prefactura
    WHERE person.id=prefactura.Cliente_id
    AND prefactura.id=$numFact";
    $paquete1=mysql_query($orden1);
    $reg1=mysql_fetch_array($paquete1);

    
 $orden2="SELECT producto_id,name,pre.presenta,precioUnitario,cantidad,total,descuento
    FROM product,detalle_prefactura pre
    WHERE pre.Producto_id=product.id
    AND pre.Comprobante_id=$numFact";
    $paquete2=mysql_query($orden2);
    
    $paquete2=mysql_query($orden2);

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
        "Cédula jurídica 3-101-196052 \n".
        "San Nicolás, Cartago, Costa Rica"."\n".
        "Bodega, La Virgen, Sarapiquí, Heredia"."\n".
        "Teléfono: +(506) 2761-2200, +(506) 2573-0903"."\n".
        "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
         "C", // texto justificado 
         false);

        if($Vplazo==0){
            $tPago='Contado';
        }
        $pdf->SetFont('Arial','',12);
        $top_datos=40;
        $pdf->SetXY(80, $top_datos);
        $pdf->Cell(190, 10, "FACTURA PROFORMA", 0, 2, "C");
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
        "Nombre: ".utf8_decode($reg1[0])."\n".
        "Direccion: ".utf8_decode($reg1[1])."\n".
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
                    $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C');
                    $pdf->SetXY(90, $y);
                    $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                    $pdf->SetXY(110, $y);
                    $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                    $pdf->SetXY(140, $y);
                    $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                    $pdf->SetXY(160, $y);
                    $pdf->Cell(40, 5, number_format(($reg2[3]*$reg2[4])-(($reg2[6]/100)*($reg2[3]*$reg2[4])),2), 0, 1, 'C');
            }else if($tipoC=='COLONES'){
                    $pdf->SetXY(5, $y);
                    $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                    $pdf->SetXY(35, $y);
                    $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C');
                    $pdf->SetXY(90, $y);
                    $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                    $pdf->SetXY(110, $y);
                    $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                    $pdf->SetXY(140, $y);
                    $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                    $pdf->SetXY(160, $y);
                    $pdf->Cell(40, 5, number_format(($reg2[3]*$reg2[4])-(($reg2[6]/100)*($reg2[3]*$reg2[4])),2), 0, 1, 'C');
            }else{
                echo '<script language="javascript">alert("ERROR");</script>'; 
            }  

            
            $precio_subtotal += $reg2[3] * $reg2[4];
            $descuent+=($reg2[6]/100)*($reg2[3] * $reg2[4]);
            $x++;
            // aumento del top 5 cm
            $y = $y + 4; 

           
        }

       


        //CÃ¡lculo del Impuesto
        $add_iva = $precio_subtotal * $iva / 100;

        //CÃ¡lculo del precio total
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
        $pdf->Cell(190, 5,"Autorizo por administración tributaria según resolución #11-97 de la Gaceta #171 del 12 de agosto de 1997",0,1,"C");
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

        $fec=$_POST['fecH'];
        $fecPlazo=$_POST['fecV'];
        $orden1="SELECT name,address1,phone1,comprobante.id,total
        FROM person,comprobante
        WHERE person.id=comprobante.Cliente_id
        AND comprobante.id=$numFact";
        $paquete1=mysql_query($orden1);
        $reg1=mysql_fetch_array($paquete1);

        
    $orden2="SELECT producto_id,name,pre.presenta,precioUnitario,cantidad,total,descuento
    FROM product,detalle_prefactura pre
    WHERE pre.Producto_id=product.id
    AND pre.Comprobante_id=$numFact";
    $paquete2=mysql_query($orden2);
        
        $paquete2=mysql_query($orden2);

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
            "Cédula jurídica 3-101-196052 \n".
            "San Nicolás, Cartago, Costa Rica"."\n".
            "Bodega, La Virgen, Sarapiquí, Heredia"."\n".
            "Teléfono: +(506) 2761-2200, +(506) 2573-0903"."\n".
            "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
             "C", // texto justificado 
             false);

            if($Vplazo==0){
                $tPago='Contado';
            }
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
            "Nombre: ".utf8_decode($reg1[0])."\n".
            "Direccion: ".utf8_decode($reg1[1])."\n".
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
            $total;


            while ($reg2=mysql_fetch_array($paquete2, MYSQL_NUM)) {
                
                
                $pdf->SetFont('Arial','',10);
                if($tipoC=='DOLARES'){
                        $pdf->SetXY(5, $y);
                        $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                        $pdf->SetXY(35, $y);
                        $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C');
                        $pdf->SetXY(90, $y);
                        $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                        $pdf->SetXY(110, $y);
                        $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                        $pdf->SetXY(140, $y);
                        $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                        $pdf->SetXY(160, $y);
                        $pdf->Cell(40, 5, number_format(($reg2[3]*$reg2[4])-(($reg2[6]/100)*($reg2[3]*$reg2[4])),2), 0, 1, 'C');
                }else if($tipoC=='COLONES'){
                        $pdf->SetXY(5, $y);
                        $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C'); //6
                        $pdf->SetXY(35, $y);
                        $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C'); //name
                        $pdf->SetXY(90, $y);
                        $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');// presenta
                        $pdf->SetXY(110, $y);
                        $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');//precio
                        $pdf->SetXY(140, $y);
                        $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                        $pdf->SetXY(160, $y);
                        $pdf->Cell(40, 5, number_format($reg2[5],2), 0, 1, 'C');
                }else{
                    echo '<script language="javascript">alert("ERROR");</script>'; 
                }  

                
                $precio_subtotal += $reg2[3]*$reg2[4];
                $descuent+=($reg2[6]/100)*($reg2[3] * $reg2[4]);
                $x++;
                $total+=$reg2[5];
                // aumento del top 5 cm
                $y = $y + 4; 

               
            }

           


            //CÃ¡lculo del Impuesto
            $add_iva = $precio_subtotal * $iva / 100;

            //CÃ¡lculo del precio total
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
                $pdf->Cell(190, 5, "TOTAL:    $/  ".number_format($total,2), 0, 1, "J");
            }else if($tipoC=='COLONES'){
                $pdf->SetXY(150, $y);
                $pdf->Cell(190, 5, "Subtotal:     C/   ".number_format($precio_subtotal,2), 0, 1, "J");
                $pdf->SetXY(150, $y+8);
                $pdf->Cell(190, 5, "I.V.A:           C/   ".$add_iva, 0, 1, "J");
                $pdf->SetXY(150, $y+16);
                $pdf->Cell(190, 5, "Descuento:  C/   ".number_format($descuent,2), 0, 1, "J");
                $pdf->SetFont('Arial','B',12);
                $pdf->SetXY(150, $y+24);
                $pdf->Cell(190, 5, "TOTAL:    C/  ".number_format($total,2), 0, 1, "J");
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
            $pdf->Cell(190, 5,"Autorizo por administración tributaria según resolución #11-97 de la Gaceta #171 del 12 de agosto de 1997",0,1,"C");
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

    }

}

//<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<CAMBIO>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>  NO EXISTE

}else{

$numFact=$_POST["comprob_id"];
$cli=$_POST["cliente_id"];
$tPago = $_POST["pago"];
$idPro = $_POST["idProd"];
$Vplazo= $_POST["plazo"];
$iva=$_POST["impuestoV"];
$descuent=0;
$tipoF=$_POST['tipoFact'];
$tipoC=$_POST['moneda'];

date_default_timezone_set('America/Costa_Rica');

$fec=date('d-m-Y');
$fecPlazo=date('d-m-Y',strtotime("+$Vplazo day"));


$c=mysql_connect("localhost","root","");
mysql_select_db("facturador");

// PRE- FACTURA
$orden1="SELECT name,address1,phone1,prefactura.id
FROM person,prefactura
WHERE person.id=prefactura.Cliente_id
AND prefactura.id=$numFact";
$paquete1=mysql_query($orden1);
$reg1=mysql_fetch_array($paquete1);

 $orden2="SELECT producto_id,name,pre.presenta,precioUnitario,cantidad,total,descuento
    FROM product,detalle_prefactura pre
    WHERE pre.Producto_id=product.id
    AND pre.Comprobante_id=$numFact";
    $paquete2=mysql_query($orden2);


//Llamada al script fpdf
require('fpdf.php');

  
    if($tPago=='Contado'){
        $sSQL="UPDATE prefactura Set Tipo_pago='Contado' Where id='$numFact'";
        mysql_query($sSQL);
    }else if($tPago=='Credito'){
        $sSQL="UPDATE prefactura Set Tipo_pago='Credito', fechaVence='$fecPlazo' Where id='$numFact'";
        mysql_query($sSQL);
    }else{
        echo '<script language="javascript">alert("ERROR");</script>'; 
    }

    if($tipoC=='DOLARES'){
        $sSQL="UPDATE prefactura Set Moneda='DOLARES' Where id='$numFact'";
        mysql_query($sSQL);
    }else if($tipoC=='COLONES'){
        $sSQL="UPDATE prefactura Set Moneda='COLONES' Where id='$numFact'";
        mysql_query($sSQL);
    }else{
        echo '<script language="javascript">alert("ERROR");</script>'; 
    }

    $sSQLFec="UPDATE prefactura Set fecha='$fec' Where id='$numFact'";
    mysql_query($sSQLFec);

    $sSQLFec="UPDATE prefactura Set fechaVence='$fecPlazo' Where id='$numFact'";
    mysql_query($sSQLFec);

    
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
        "Cédula jurídica 3-101-196052 \n".
        "San Nicolás, Cartago, Costa Rica"."\n".
        "Bodega, La Virgen, Sarapiquí, Heredia"."\n".
        "Teléfono: +(506) 2761-2200, +(506) 2573-0903"."\n".
        "Correo: interagrocr@gmail.com", 0, // bordes 0 = no | 1 = si
         "C", // texto justificado 
         false);

        
        $pdf->SetFont('Arial','',12);
        $top_datos=40;
        $pdf->SetXY(80, $top_datos);
        $pdf->Cell(190, 10, "FACTURA PROFORMA", 0, 2, "C");
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
        "Nombre: ".utf8_decode($reg1[0])."\n".
        "Direccion: ".utf8_decode($reg1[1])."\n".
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
                    $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C');
                    $pdf->SetXY(90, $y);
                    $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                    $pdf->SetXY(110, $y);
                    $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                    $pdf->SetXY(140, $y);
                    $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                    $pdf->SetXY(160, $y);
                    $pdf->Cell(40, 5, number_format(($reg2[3]*$reg2[4])-(($reg2[6]/100)*($reg2[3]*$reg2[4])),2), 0, 1, 'C');
            }else if($tipoC=='COLONES'){
                    $pdf->SetXY(5, $y);
                    $pdf->Cell(20, 5, $reg2[4], 0, 1, 'C');
                    $pdf->SetXY(35, $y);
                    $pdf->Cell(45, 5, utf8_decode($reg2[1]), 0, 1, 'C');
                    $pdf->SetXY(90, $y);
                    $pdf->Cell(20, 5, $reg2[2], 0, 1, 'C');
                    $pdf->SetXY(110, $y);
                    $pdf->Cell(40, 5, number_format($reg2[3],2), 0, 1, 'C');
                    $pdf->SetXY(140, $y);
                    $pdf->Cell(40, 5,($reg2[6]/100)*($reg2[3]*$reg2[4]), 0, 1, 'C');
                    $pdf->SetXY(160, $y);
                    $pdf->Cell(40, 5, number_format(($reg2[3]*$reg2[4])-(($reg2[6]/100)*($reg2[3]*$reg2[4])),2), 0, 1, 'C');
            }else{
                echo '<script language="javascript">alert("ERROR");</script>'; 
            }  

            
            $precio_subtotal += $reg2[3] * $reg2[4];
            $descuent+=($reg2[6]/100)*$precio_subtotal;
            $x++;
            // aumento del top 5 cm
            $y = $y + 4; 

           
        }

       


        //CÃ¡lculo del Impuesto
        $add_iva = $precio_subtotal * $iva / 100;

        //CÃ¡lculo del precio total
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
        $pdf->Cell(190, 5,"Autorizo por administración tributaria según resolución #11-97 de la Gaceta #171 del 12 de agosto de 1997",0,1,"C");
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


}

