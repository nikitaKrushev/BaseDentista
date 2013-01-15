<?php
if(!isset($_SESSION['uid']))
	session_start();

require_once('funciones.php');
conectar($servidor, $user, $pass, $name);

//echo phpversion();
$redireccionar=false;

if(isset($_SESSION['uid']) || isset($_POST['pass'])) {
	$uid = isset($_POST['user']) ? mysql_real_escape_string($_POST['user']) : $_SESSION['uid'];
	$pwd = isset($_POST['pass']) ? mysql_real_escape_string($_POST['pass']) : $_SESSION['pwd'];
	if(!isset($_SESSION['pwd'])){
		$_SESSION['pwd'] = "*";
	}
}
else {
	$uid ="";
	$pwd = "";
	$_SESSION['pwd']="_";
}
if(!isset($uid)) {
	?>
<?php

}

//Revisar si son validos los datos

if($pwd != $_SESSION['pwd']) {
	$encript = sha1($pwd);
	$redireccionar=true;
}
else {
	$encript = $_SESSION['pwd'];
}

$_SESSION['uid'] = $uid;
$_SESSION['pwd'] = $encript;
$loginEncontrado = false;
$query = "SELECT Usuario FROM Dentista WHERE Usuario='".$uid."' AND Password='".$encript."' LIMIT 1";
$row = mysql_query($query);

if (!$row) {
	error('Un error ocurrió mientras revisabamos sus'.
			'credenciales.\\nSi este error continua por favor'.
			'mande un correo a cartillasaludbucal@gmail.com');
}

$fila = mysql_fetch_assoc($row);

//Informacion no valida
if(!empty($fila['Usuario'])){ //No es un dentista
	$loginEncontrado = true;
	$_SESSION['type'] = 1; //Dentista
}

else {
	//Padre
	$query = "SELECT Usuario FROM Padre WHERE Usuario='".$uid."' AND Password='".$encript."' LIMIT 1";
	$row = mysql_query($query);
	$fila = mysql_fetch_assoc($row);
		
	if(!empty($fila['Usuario'])) {
		$loginEncontrado = true;
		$_SESSION['type'] = 2; //Padre

	}
	else {
		$query = "SELECT Usuario FROM Maestro WHERE Usuario='".$uid."' AND Password='".$encript."' LIMIT 1";
		$row = mysql_query($query);

		if( mysql_num_rows($row) != 0) {
			$loginEncontrado = true;
			$_SESSION['type'] = 3; //Maestro
		}
		
		else { //Director
			$query = "SELECT idDirector FROM Director WHERE idDirector='".$uid."' AND Password='".$encript."' LIMIT 1";
			$row = mysql_query($query);
		
			if( mysql_num_rows($row) != 0) {
				$loginEncontrado = true;
				$_SESSION['type'] = 4;
			}
			
			else { //Profesional Salud
				$query = "SELECT Usuario FROM ProfesionalSalud WHERE Usuario='".$uid."' AND Password='".$encript."' LIMIT 1";
				$row = mysql_query($query);
			
				if( mysql_num_rows($row) != 0) {
					$loginEncontrado = true;
					$_SESSION['type'] = 5;		
				}
				
				else { //Profesional Salud
					$query = "SELECT Usuario FROM Administrador WHERE Usuario='".$uid."' AND Password='".$encript."' LIMIT 1";
					$row = mysql_query($query);
						
					if( mysql_num_rows($row) != 0) {
						$loginEncontrado = true;
						$_SESSION['type'] = 6; 
					}
						
				}
					
			}
			
		}		
	}
}

if(!$loginEncontrado) {
	unset($_SESSION['uid']);
	unset($_SESSION['pwd']);
	unset($_SESSION['type']);
	unset($_SESSION['check']);
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Acceso Denegado</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
	<h1>Acceso Denegado</h1>
	<p>
		El usuario o contraseña son incorrectos, o no eres un usuario
		registrado en esete sito. Intenta acceder de nuevo al sistema, has
		click <a href="/index.php">aquí.
	</p>
</body>
</html>
<?php
exit;
}

else{
	switch($_SESSION['type']) {

		case 1: //Dentista
			if($redireccionar ){//|| $_SERVER['HTTP_REFERER']=="http://localhost/src/login.html") {
				header( "refresh:3;url=principales/mainDentista2.php" ); //Redireccionar a pagina
				//if(empty($_SESSION["check"])) {
				echo "Bienvenido ".$_SESSION["uid"].".\n";
				echo "En 3 segundos te redireccionaremos a la pagina de inicio";
				$_SESSION['check']="check";

			}
				
			break;
				
		case 2: //Padre
			if($redireccionar ){// || $_SERVER['HTTP_REFERER']=="http://localhost/src/login.html")		{
				header( "refresh:3;url=principales/padrePrincipal.php" ); //Redireccionar a pagina
				//if(empty($_SESSION["check"])) {
				echo "Bienvenido ".$_SESSION["uid"].".\n";
				echo "En 3 segundos te redireccionaremos a la pagina de inicio";
				$_SESSION['check']="check";
			}
			break;
				
		case 3://Maestro
			if($redireccionar){// || $_SERVER['HTTP_REFERER']=="http://localhost/src/login.html") {
				header( "refresh:3;url=principales/mainMaestro.php" ); //Redireccionar a pagina
				echo "Bienvenido ".$_SESSION["uid"].".\n";
				echo "En 3 segundos te redireccionaremos a la pagina de inicio";
				$_SESSION['check']="check";
			}
				
			break;
			
		case 4://Director
			if($redireccionar ){//|| $_SERVER['HTTP_REFERER']=="http://localhost/src/login.html") {			
				header( "refresh:3;url=principales/directorPrincipal.php" ); //Redireccionar a pagina
				echo "Bienvenido ".$_SESSION["uid"].".\n";
				echo "En 3 segundos te redireccionaremos a la pagina de inicio";
				$_SESSION['check']="check";
			}
			break;

		case 5://Profesional de salud
				if($redireccionar){// || $_SERVER['HTTP_REFERER']=="http://localhost/src/login.html") {
					header( "refresh:3;url=principales/profesionalPrincipal.php" ); //Redireccionar a pagina
					echo "Bienvenido ".$_SESSION["uid"].".\n";
					echo "En 3 segundos te redireccionaremos a la pagina de inicio";
					$_SESSION['check']="check";
				}
		break;
		
		case 6://Administrador
			if($redireccionar){// || $_SERVER['HTTP_REFERER']=="http://localhost/src/login.html") {
				header( "refresh:3;url=principales/adminPage.php" ); //Redireccionar a pagina
				echo "Bienvenido ".$_SESSION["uid"].".\n";
				echo "En 3 segundos te redireccionaremos a la pagina de inicio";
				$_SESSION['check']="check";
			}
		break;
		
	}
		
}
?>