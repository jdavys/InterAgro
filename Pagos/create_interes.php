<?php require_once("includes/conection_db.php");?>
<?php require_once("includes/function.php");?>
<?php
$cliente   = preparar_consulta($_POST['cliente']);
$factura   = preparar_consulta($_POST['factura']);
$monto = preparar_consulta($_POST['montoAbono']/100);


date_default_timezone_set('America/Costa_Rica');

$recibo= "SELECT * from cuenta_cobrar where id_comprobante={$factura}";


if (mysql_query($recibo,$conexion)){
	
	$respuesta=mysql_query($recibo,$conexion);
	$cuenta = mysql_fetch_array($respuesta);
	$interes=$cuenta['saldo_Factura'] * $monto;
	$saldoF=$cuenta['saldo_Factura']+$interes;
	
	$modifica="UPDATE cuenta_cobrar SET saldo_Factura={$saldoF} , interes={$interes} where id_comprobante={$factura}" ;
	if (mysql_query($modifica,$conexion)){

		$global= "SELECT * from cuenta_cobrar where id_cliente= {$cliente}";

        $saldoG=0;

        if (mysql_query($global,$conexion)){

            $respuesta=mysql_query($global,$conexion);
            while($cuenta = mysql_fetch_array($respuesta)){
                $saldoG+=$cuenta['saldo_Factura'];
            }

        }

        $iSQL2="UPDATE cuenta_cobrar SET saldo_Global= {$saldoG}
                WHERE id_cliente={$cliente}";
                
        if(mysql_query($iSQL2,$conexion)){
        	header("location: content.php");
			exit();
        }else{
			echo "Interes NO aplicado ! ".mysql_error();
		}
	}else{
		echo "Interes NO aplicado ! ".mysql_error();
	}
	
}else{
	echo "Interes NO aplicado ! ".mysql_error();
}
?>

<?php
 mysql_close($conexion);
 ?>