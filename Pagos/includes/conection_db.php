<?php
	require_once("contants.php");
	$conexion=mysql_connect(DB_SERVER,DB_USERNAME,DB_PASWORD);
	if(!$conexion){
		die("Conexión al servidor falló".mysql_error());
	}
	$bd_selec=mysql_select_db(DB_NAME,$conexion);
	if(!$bd_selec){
		die("Conexión Base datos falló".mysql_error());
	}

?>