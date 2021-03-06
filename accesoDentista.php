<?php
/**
 * Autor: Josué Castañeda 
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 * 
 * Descripcion:
 * 	Realiza un filtro dependiendo de la sesión del usuario. Si no esta registrado el usuario, se
 *  escribe un error en pantalla y se pone un enlace a la pagina de acceso al sistema.
 *  
 *  Se realiza una consulta a la base de datos cada vez que se accede a este archivo,la razon es
 *  porque se debe validar cada vez que se quiere acceder a ciertos archivos.
 * 
 * Variables:
 * 		$redireccionar: Sirve para mandar a la pagina de inicio segun sea el tipo de usuario.
 * 		$uid: El identificador del usuario
 * 		$pwd: La contraseña del usuario
 * 		$_SESSION['pwd']: La contraseña guardada en la sesión, esta encriptada
 * 		$sal_dinamica: La parte dinamica de encriptacion, guardada en la base
 * 		$sal_estatica: La parte estatica de encriptacion
 * 		$encript: La contraseña encriptada despues del algoritmo de encriptacion
 */


if(!isset($_SESSION['uid']))
	session_start();

require_once('funciones.php');
conectar($servidor, $user, $pass, $name);

$redireccionar=false;

if(isset($_SESSION['uid']) || isset($_POST['pass'])) {
	$uid = isset($_POST['user']) ? mysql_real_escape_string($_POST['user']) : $_SESSION['uid'];
	$pwd = isset($_POST['pass']) ? mysql_real_escape_string($_POST['pass']) : $_SESSION['pwd'];	
	if(!isset($_SESSION['pwd'])){
		$_SESSION['pwd'] = "*";
	}
}
else { // Si no han sido llamados mediante POST o Ya existia una sesion, negar el paso
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
	//Seleccionar un usuario de la base de datos para saber su sazonado
	$query = "SELECT Sasonado FROM Dentista WHERE Usuario='".$uid."' LIMIT 1";
	$row = mysql_query($query);
	$fila = mysql_fetch_assoc($row);
	
	if(!empty($fila['Sasonado'])){ //Es un dentista
		$sal_dinamica = $fila['Sasonado'];		
	}
	else {
		$query = "SELECT Sasonado FROM Padre WHERE Usuario='".$uid."' LIMIT 1";
		$row = mysql_query($query);
		$fila = mysql_fetch_assoc($row);
		
		if(!empty($fila['Sasonado'])){ //Es un padre
			$sal_dinamica = $fila['Sasonado'];
			
		} 
		else {
			$query = "SELECT Sasonado FROM Maestro WHERE Usuario='".$uid."' LIMIT 1";
			$row = mysql_query($query);
			$fila = mysql_fetch_assoc($row);
				
			if(!empty($fila['Sasonado'])){ //Es un maestro
				$sal_dinamica = $fila['Sasonado'];				
			}
			else {
				$query = "SELECT Sasonado FROM Director WHERE idDirector='".$uid."' LIMIT 1";
				$row = mysql_query($query);
				$fila = mysql_fetch_assoc($row);
				
				if(!empty($fila['Sasonado'])){ //Es un director
					$sal_dinamica = $fila['Sasonado'];					
				}
				else {
					$query = "SELECT Sasonado FROM ProfesionalSalud WHERE Usuario='".$uid."' LIMIT 1";
					$row = mysql_query($query);
					$fila = mysql_fetch_assoc($row);											
					if(!empty($fila['Sasonado'])){ //Es un profesional
						$sal_dinamica = $fila['Sasonado'];						
					}
					else {
						$query = "SELECT Sasonado FROM Administrador WHERE Usuario='".$uid."' LIMIT 1";
						$row = mysql_query($query);
						$fila = mysql_fetch_assoc($row);
						
						if(!empty($fila['Sasonado'])){ //Es un admin
							$sal_dinamica = $fila['Sasonado'];							
						}
					}
				}
			}
		}
	}
	if(!isset($sal_dinamica))
		$sal_dinamica="nohaynada";
	
	$sal_estatica="m@nU3lit0Mart1!n3z";
	$password_length = strlen($pwd);
	$split_at = $password_length / 2;
	$password_array = str_split($pwd, $split_at);
	$cod=$password_array[0] . $sal_estatica . $password_array[1] . $sal_dinamica;
	$cod = trim($cod);
	$encript = sha1($cod);	
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
if(!empty($fila['Usuario'])){ //Es un dentista
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
		click <a href="http://cartillabucaldigital.org/index.php"> aquí </a>.
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
				echo "Bienvenido ".$_SESSION["uid"].".\n";
				echo "En 3 segundos te redireccionaremos a la pagina de inicio";
				$_SESSION['check']="check";

			}
				
			break;
				
		case 2: //Padre
			if($redireccionar ){// || $_SERVER['HTTP_REFERER']=="http://localhost/src/login.html")		{
				header( "refresh:3;url=principales/padrePrincipal.php" ); //Redireccionar a pagina
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