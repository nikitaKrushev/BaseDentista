<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 17/FEB/2013
 *
 * Descripcion:
 * 	Se registra a los administradores semejante al registro de padres.
 *
 */

session_start();
include '../accesoDentista.php';
//Checamos si hay una session vacia o si ya hay una sesion
if ($_SESSION['type'] != 5) {
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 1: //Dentista
			header("refresh:3, url=loggeado.php");
			break;

		case 2: //Padre
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3://Maestro
			header("refresh:3;url=../principales/mainMaestro.php");
			break;

		case 4://Director
			header("refresh:3;url=../principales/directorPrincipal.php");
			break;
			
		case 6://Admin
				header("refresh:3;url=../principales/adminPage.php");
		break;
		

	}
exit;
}

if(isset($_POST['posted'])) {
	require_once('../funciones.php');
	conectar($servidor, $user, $pass, $name);
	
	//code to take action as user submitted the data
	//recibe info
	$user = strip_tags($_POST['usuario']);
	$name = strtoupper(strip_tags($_POST['name']));
	$apaterno = strtoupper(strip_tags($_POST['apaterno']));
	$amaterno = strtoupper(strip_tags($_POST['amaterno']));
	$pass = strip_tags($_POST['password']);
	$pass2 = strip_tags($_POST['pass2']);
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);

	
	/***
	 * Codigo necesario para mandar correo de confirmacion
	*/
	
	$to = $correo;
	$nameto = $name." ".$apaterno;
	$from = "registro@cartillabucaldigital.org";
	$namefrom = "Registro de cuentas";
	$subject = "Registro exitoso de cartilla bucal digital";
	$message =  $name." ".$apaterno.".$apaterno. "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!
		\r\n. Tu usuario es: ".$user."\r\n Tu contraseña: ".$pass.
		"\r\n. Recuerda escribir en algún lugar seguro esta información, para que no se pierdan tus datos
		\r\n. Si tienes dudas o comentarios no dudes en escribir a contacto@cartillabucaldigital.org"; //Pondremos contrasenia y usuario al usuario			
	
	//Para la validacion
	$fail = validateUser(trim($user));
	$fail .= validaNombre(trim($name));
	$fail .= validaPaterno($apaterno,1);
	$fail .= validaPaterno($amaterno,2);
	$fail .= validaPass($pass);
	$fail .= validaEqualPass($pass,$pass2);
	$fail .= validaCorreo($correo);
	$fail .= validaEqualCorreo($correo,$correo2);	
	
	if($fail == "") {
		//Encriptamos contrasenia
		$pass_enc = sha1($pass);

		$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($user)."'");
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
			//echo $fail;
			header("refresh:1;url=regAdmin.php");
			print '<script type="text/javascript">';
			print 'alert("Usuario existente en la base")';
			print '</script>';
		}else{
			$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($user)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
				//echo $fail;
				header("refresh:1;url=regAdmin.php");
				print '<script type="text/javascript">';
				print 'alert("Usuario existente en la base")';
				print '</script>';
			} else {
				$query = @mysql_query("SELECT * FROM Dentista WHERE Usuario='".mysql_real_escape_string($user)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
					//echo $fail;
					header("refresh:1;url=regAdmin.php");
					print '<script type="text/javascript">';
					print 'alert("Usuario existente en la base")';
					print '</script>';
				}
				else {
					$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($user)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
						//echo $fail;
						print '<script type="text/javascript">';
						print 'alert("Usuario existente en la base")';
						print '</script>';
						header("refresh:1;url=regAdmin.php");
					} 
					else {
						$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($user)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
							//echo $fail;
							print '<script type="text/javascript">';
							print 'alert("Usuario existente en la base")';
							print '</script>';
							header("refresh:1;url=regAdmin.php");
						}
						else {
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($user)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
								print '<script type="text/javascript">';
								print 'alert("Usuario existente en la base")';
								print '</script>';
								header("refresh:1;url=regAdmin.php");
							}
							
							else {
								$meter=@mysql_query('INSERT INTO Administrador (Usuario, Nombre,ApellidoPaterno,ApellidoMaterno, Password,
										ProfesionalSalud_Usuario, Correo) values ("'.mysql_real_escape_string($user).'","'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($apaterno).'","'.mysql_real_escape_string($amaterno).
										'","'.mysql_real_escape_string($pass_enc).'","'.$_SESSION['uid'].'","'.mysql_real_escape_string($correo).'")');
								
								if($meter){
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									print '<script type="text/javascript">';
									print 'alert("Registro exitoso de Administrador. Revisa tu bandeja de correo")';
									print '</script>';
									header("refresh:1;url=../principales/profesionalPrincipal.php");
									exit;
								}
								else{
									$fail .= 'Hubo un error';
									print '<script type="text/javascript">';
									print 'alert("Hubo un error en el registro")';
									print '</script>';									
								}								
							}
						}
					}
				}
			}			
		}
		exit;
	}
	else {		
		print '<script type="text/javascript">';
		print 'alert("Error en el registro")';
		print '</script>';
	}

}

else {
	if(isset($fail))
		echo $fail;
	$user = "Usuario:";
	$pass = "ContraseÃ±a:";
	$pass2 = "Repite ContraseÃ±a:";
	$name = "Nombre:";
	$apaterno = "Apellido Paterno:";
	$amaterno = "Apellido Materno:";
	$correo = "Correo Electronico:";
	$correo2 = "Repite Correo Electronico:";	
}

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validateUser($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Usuario.\n";
	return "";
}

function validaPaterno($nombre,$tipo) {
	if ($nombre =="") {
		if($tipo == 1)
			return "Favor de llenar el campo apellido paterno.\n";
		else
			return "Favor de llenar el campo apellido materno.\n";
	}
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "Los apellidos solo contienen letras.\n";
	return "";
}

function validaPass($field) {

	if($field == "") return "Introduce una contraseÃ±a.\n";
	else{

		if (strlen($field) < 5)
			return "El tamaÃ±o de la contraseÃ±a debe ser por lo menos de 5 caracteres.\n";

		else
			if (! preg_match("/[a-z]/",$field) || ! preg_match("/[0-9]/",$field))
			return "La contraseÃ±a requiere por lo menos un caracter de [a-z] y [0-9].\n";
	}
	return "";
}

function validaEqualPass($field,$field2) {
	if($field !=$field2) return "Las contraseÃ±as no son iguales.\n";
	return "";
}

function validaCorreo($field) {
	if ($field == "") return "Introduce una contraseÃ±a.\n";
	else if (!((strpos($field, ".") > 0) &&
			(strpos($field, "@") > 0))  ||
			preg_match("/[^a-zA-Z0-9.@_-]/",$field))
		return "La direcciÃ³n de correo electrÃ³nico es invÃ¡lida".$field."\n";
	return "";
}

function validaEqualCorreo($field,$field2){
	if($field !=$field2) return "Los correos no son iguales.\n";
	return "";
}

function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message)
{
	//SMTP + Detalles del servidor
	/* * * * Inicia configuración * * * */
	$smtpServer = "mail.cartillabucaldigital.org";
	$port = "25";
	$timeout = "30";
	$username = "registro@cartillabucaldigital.org";
	$password = "l@c0yota719p0r";
	$localhost = "localhost";
	$newLine = "\r\n";
	/* * * * Termina configuración * * * * */

	//Conexión al servidor en el puerto específico
	$smtpConnect = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
	$smtpResponse = fgets($smtpConnect, 515);
	if(empty($smtpConnect))
	{
		$output = "Failed to connect: $smtpResponse";
		return $output;
	}
	else
	{
		$logArray['connection'] = "Connected: $smtpResponse";
	}

	//Solicitud de logueo
	fputs($smtpConnect,"AUTH LOGIN" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authrequest'] = "$smtpResponse";

	//Envío de usuario
	fputs($smtpConnect, base64_encode($username) . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authusername'] = "$smtpResponse";

	//Envío de password
	fputs($smtpConnect, base64_encode($password) . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authpassword'] = "$smtpResponse";

	//Saludo a SMTP
	fputs($smtpConnect, "HELO $localhost" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['heloresponse'] = "$smtpResponse";

	//Envía correo desde
	fputs($smtpConnect, "MAIL FROM: $from" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['mailfromresponse'] = "$smtpResponse";

	//Envía correo a
	fputs($smtpConnect, "RCPT TO: $to" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['mailtoresponse'] = "$smtpResponse";

	//Cuerpo del mensaje
	fputs($smtpConnect, "DATA" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['data1response'] = "$smtpResponse";

	//Construyendo encabezados
	$headers = "MIME-Version: 1.0" . $newLine;
	$headers .= "Content-type: text/html; charset=iso-8859-1" . $newLine;
	$headers .= "To: $nameto <$to>" . $newLine;
	$headers .= "From: $namefrom <$from>" . $newLine;

	fputs($smtpConnect, "To: $to\nFrom: $from\nSubject: $subject\n$headers\n\n$message\n.\n");
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['data2response'] = "$smtpResponse";

	//Despedida a SMTP
	fputs($smtpConnect,"QUIT" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['quitresponse'] = "$smtpResponse";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
    <title>Principal | Cartilla de Salud Bucal</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
    
    <!-- JavaScript -->
    <script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="../js/superfish.js"></script>
    <script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
    <script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="../js/jquery.prettySociable.js"></script>
    <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="../js/main.js"></script>
    
</head>

<body id="home"><!-- #home || #page-post || #blog || #portfolio -->

    <!-- Page Start -->
    <div id="page">
        
        <!-- Main Column Start -->
        <div id="wrap">
            <div id="main-col"><!-- Nivo Slider -->
                
                <!-- Homepage Welcome Text -->
                <div id="homepage-post">
                <h1 class="p-title"><a href="#">Bienvenido al sitio de la Cartilla de Salud Bucal Digital</a></h1>
                    <div class="p-content">
                        <p>Perfil epidemiolÃ³gico de caries dental</p>
                        <p>PÃ¡gina de registro de Administradores</p>
                        <?php 
                        if(isset($_POST['posted'])) {
                     	
                        	echo $fail;
                        }
                        ?>
                    </div>
                    
                    <div id="registra"	>
                    <ul>
                        <li>						                        
							<form action="regAdmin.php" method="post">
								<input type="text" value="<?php echo $user;?>" alt="Usuario:" title="Escribe tu usuario" name="usuario" id="usuario" />														
								<input type="text"  value="<?php echo $name;?>" alt="Nombre:" title="Pon tu nombre" name="name" id="name" />
								<input type="text"  value="<?php echo $apaterno;?>" alt="Apellido Paterno:" title="Pon el apellido paterno" name="apaterno" id="apaterno" />									
								<input type="text"  value="<?php echo $amaterno;?>" alt="Apellido Materno:" title="Pon el apellido materno" name="amaterno" id="amaterno" />									
								<input type="password" value="<?php echo $pass;?>" name="password" alt="ContraseÃ±a:" title="Introduce tu contraseÃ±a, de al menos 5 caracteres" id="password" /> 
								<input type="password" value="<?php echo $pass2;?>" name="pass2" alt="Confirmar ContraseÃ±a: " title="Repite la contraseÃ±a" id="pass2" /> 
								<input type="text" value="<?php echo $correo;?>" name="correo" alt="Correo electronico: " title="Introduce tu correo electronico" id="correo" /> 
								<input type="text" value="<?php echo $correo2;?>" name="correo2" alt="Confirmar Correo electronico: " title="Repite tu correo electronico" id="correo2" /> 																				
								<input type="submit" value="Registrar" />
								<input type="hidden" name="posted" value="yes" />								
							</form>
							
                       </li>
                    </ul>
                    </div>
                </div>                                              
                <!-- Homepage Teasers End -->
    
            </div>
        </div>
        <!-- Main Column End -->
        
        <!-- Left Column Start -->
        <div id="left-col">
        
            <!-- Logo -->
            <a href="../principales/profesionalPrincipal.php" id="logo">Foundation</a>
            
            <!-- Main Naigation (active - .act) -->
            <div id="main-nav">
                <ul>
                  <li class="act"><a href="../principales/profesionalPrincipal.php">Inicio</a></li>
                    <li>
                        <a href="../registros/regAdmin.php">Registrar administrador de sitio</a>
                        
                    </li>
                    <li>
                        <a href="../registros/regPais.php">Registrar Pais</a>
                        
                    </li>
                    
                    <li>
                        <a href="../registros/regEstado.php">Registrar Estado</a>
                        
                    </li>
                    
                    
                    <li>
                        <a href="../registros/regCiudad.php">Registrar Ciudad</a>
                        
                    </li>
                    
                    <li>
                        <a href="../construccion.html">Consultar directorio de consultorios</a>                      
                    </li>
                </ul>
            </div>
            
            <!-- News Widget -->
            <div class="widget w-news">
                <h4 class="w-title title-light">Cerrar sesion.</h4>
                <div class="w-content">
                    <ul>
                        <li>
                        	<form action="../finSesion.php" method="post">
                                Usuario: <?php echo $_SESSION["uid"];?> 
                                <input type="submit" value="Fin de sesiÃ³n" />
                        	</form>                        	                            
                        </li>
                    </ul>
                </div>
            </div>
            
        </div>
        <!-- Left Column End -->
    
        <div class="clear clear-wrap"></div>
    
        <!-- Footer Start -->
        <div id="footer">
        
            <!-- Subscribe Form and Copyright Text -->
            <div id="f-left-col">
                <div id="copyright">&copy; 2012 Miguel Alberto Zamudio | UABC </div>
            </div>
            
            <!-- Footer Widgets -->
            <div id="f-main-col">
                <!-- Links -->
                <div class="widget w-25 w-links">                
                </div>
                <!-- Social -->
                <div class="widget w-25 w-links">
                   
                </div>
                <!-- Contact Info -->
                <div class="widget w-50 w-text last" id="text-1">
                    <h5 class="w-title">Contacto:</h5>
                    <div class="w-content">
                        <a href="#"><img src="img/pictures/zamudio.png" alt="Our Building" class="alignright" /></a>
                        Tijuana, B.C., Mï¿½xico<br />
                        Tel.: 664 400 7866<br />
                        <a href="#">cartillasaludbucal@gmail.com</a>
                    </div>
                </div>
            </div>
            
            <div class="clear"></div>
        </div>
        <!-- Footer End -->
        
    </div>
    <!-- Page End -->

</body>
</html>