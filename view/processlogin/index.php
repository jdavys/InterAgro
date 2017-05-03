<?php
include('Session.php');
// define('LBROOT',getcwd()); // LegoBox Root ... the server root
$servidor="localhost";
$dbuser="root";
$dbpassword="";
$dbnombre="facturador";

$conex = new mysqli($servidor,$dbuser,$dbpassword,$dbnombre);
if($conex->connect_errno>0){
		die("No se pudo conectar con la base de datos ".$conex->connect_error."");
}else{
	if(Session::getUID()=="") {
	$user = $_POST['mail'];
	$pass = sha1(md5($_POST['password']));



	/*$base = new Database();
	$con = $base->Conectar();*/
	 $sql = "select * from user where (email= \"".$user."\" or username= \"".$user."\") and password= \"".$pass."\" and is_active=1";
	//print $sql;
	$query = $conex->query($sql);
	$found = false;
	$userid = null;
	while($r = $query->fetch_array()){
		$found = true ;
		$userid = $r['id'];
	}

	if($found==true) {
		session_start();
	//	print $userid;
		$_SESSION['user_id']=$userid ;
	//	setcookie('userid',$userid);
	//	print $_SESSION['userid'];
		print "Cargando ... $user";
		print "<script>window.location='?c=comprobante';</script>";
		var_dump($sql);
	}else {
		print "<script>window.location='index.php?view=login';</script>";
		var_dump($sql);
	}

	}else{
		print "<script>window.location='?c=comprobante';</script>";
		var_dump($sql);
	}
}


?>