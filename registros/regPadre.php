<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 *
 * Descripcion:
 * 	Registro de los padres. Primero se verifica que la información introducida sea
 * 	correctamente formateada. Después se verifica la unicidad del usuario, en caso de 
 *  repetición, el registro no puede ser completado, de otra forma, se da de alta al 
 *  usuario y se envia un correo a la dirección que fue proporcionada.
 *
 */

if(isset($_POST['posted'])) {

	require_once('../funciones.php');
	conectar($servidor, $user, $pass, $name);
	
	//recibe info
	$usuario = strip_tags($_POST['usuario']);
	$password = strip_tags($_POST['password']);
	$password2 = strip_tags($_POST['password2']);
	$nombre = strtoupper(strip_tags($_POST['name']));
	$apellidoPat = strtoupper(strip_tags($_POST['apellidoP']));
	$apellidoMat = strtoupper(strip_tags($_POST['apellidoM']));
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);
	$telefono = strip_tags($_POST['telefono']);
	
	$to = $correo;
	$nameto = $nombre." ".$apellidoPat." ".$apellidoMat;
	$from = "registro@cartillabucaldigital.org";
	$namefrom = "Registro de cuentas";
	$subject = "Registro exitoso de cartilla bucal digital";
	$message =  $nombre." ".$apellidoPat.".$apellidoMat. "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!
			\r\n Tu usuario es: ".$usuario."\r\n  Tu contraseña: ".$password.
			"\r\n Recuerda escribir en algún lugar seguro esta información, para que no se pierdan tus datos
			\r\nSi tienes dudas o comentarios no dudes en escribir a contacto@cartillabucaldigital.org"; //Pondremos contrasenia y usuario al usuario		
	
	//Validacion
	$fail = validaUser($usuario);
	$fail .= validaPass($password);
	$fail .= validaEqualPass($password,$password2);
	$fail .= validaNombre(trim($name));
	$fail .= validaPaterno(trim($apellidoPat),1);
	$fail .= validaPaterno(trim($apellidoMat),2);
	$fail .= validaCorreo($correo);
	$fail .= validaEqualCorreo($correo,$correo2);
	
	if($fail == "") {
	
		$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($usuario)."'");
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			echo $fail;
			header("refresh:3;url=regPadre.php");
		}else{
			$query = @mysql_query('SELECT * FROM Dentista WHERE Usuario="'.mysql_real_escape_string($usuario).'"');
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				echo $fail;
				header("refresh:3;url=regPadre.php");
			}else {
				$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					echo $fail;
					header("refresh:3;url=regPadre.php");
				}
				else {
					$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						echo $fail;
						header("refresh:3;url=regPadre.php");
					}
					else {
						$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							echo $fail;
							header("refresh:3;url=regPadre.php");
						}
						else {
							$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								echo $fail;
								header("refresh:3;url=regPadre.php");
							}
							else {
								$pass_enc = sha1($password);
																
								$meter=@mysql_query('INSERT INTO Padre(Nombre,ApellidoPaterno,ApellidoMaterno,Usuario,Password,Telefono,Correo)  
										values ("'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($apellidoPat).'","'.mysql_real_escape_string($apellidoMat).
											'","'.mysql_real_escape_string($usuario).'","'.mysql_real_escape_string($pass_enc).'","'
												.mysql_real_escape_string($telefono).'","'.mysql_real_escape_string($correo).'")');
								
								if($meter){
									echo 'Usuario registrado con &eacute;xito. Revisa tu bandeja de correo';
									//$sendmail = mail($mail_to,$mail_subject,$mail_body,$mail_header);
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);									
									header("refresh:3;url=../index.php");
										
								}else{
									$fail .= 'Hubo un error';
									echo $fail;
									
								}
								
							}
								
						}
					}
				}
			}												
		}
	}
}
else {
	if(isset($fail))
		echo $fail;
	$usuario = "Usuario:";
	$password = "Contrasenia";
	$password2 = "Repite contrasenia:";
	$name = "Nombre:";
	$apellidoPat = "Apellido paterno:";
	$apellidoMat = "Apellido materno:";
	$correo = "Correo electronico:";
	$correo2 = "Repite correo electronico:";
	$telefono = "Telefono:";	
}

function validaUser($usuario) {
	if ($usuario =="") return "Favor de llenar el campo Identificador.\n";
	return "";
}

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validaPaterno($nombre,$tipo) {
	if ($nombre =="") {
		if($tipo ==1)
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

/**
 * Función proporcionada por el servidor donde estamos haciendo host.
 * No tocar.
 */

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
                        <p>PÃ¡gina de registro de Padres</p>
                        <?php 
                        if(isset($_POST['posted'])) {
                     	
                        	echo $fail;
                        }
                        ?>
                    </div>
                    
                    <div id="registra"	>
                    <ul>
                        <li>
							<script type="text/javascript">
								function validate(form){
									fail = validateNombre(form.name.value);
									fail += validatePaterno(form.apellidoP.value,1);
									fail += validatePaterno(form.apellidoM.value,2);		
									fail += validatePass(form.password.value);
									fail += validateEqualPass(form.password2.value,form.password.value);
									fail += validateCorreo(form.correo.value);
									fail += validateEqualCorreo(form.correo.value,form.correo2.value);
									fail += validateTelefono(form.telefono.value);
									if (fail =="") return true;
									else {
										alert(fail);
										return false;
									}
								}
							
								function validateNombre(field) {
									if (field =="") return "Favor de llenar el campo Nombre.\n";
									else
										if (! /^[a-zA-Z]+$/.test(field) )
											return "El campo Nombre solo contiene letras.\n";
									return "";
								}
								
								function validatePaterno(field,tipo) {
									if (field =="") {
										if (tipo == 1)
											return "Favor de llenar el campo apellido paterno.\n";
										else
											return "Favor de llenar el campo apellido materno.\n";
									else
										if (! /^[a-zA-Z]+$/.test(field) )
											return "Los apellidos contienen solo letras.\n";
									return "";
								}
								
								function validatePassword(field){
									if(field == "") return "Introduce una contraseÃ±a.\n";
									else
										if (field.length < 5)
											return "El tamaÃ±o de la contraseÃ±a debe ser por lo menos de 5 caracteres.\n";
										else 
											if (! /[a-z]/.test(field) || ! /[0-9]/.test(field))
												return "La contraseÃ±a requiere por lo menos un caracter de [a-z] y [0-9].\n";					
									return "";		
								}
									
								function validatePasswordEqual(field,field2) {
									if(field !=field2) return "Las contraseÃ±as no son iguales.\n";
									return "";
								}
								
								function validateCorreo(field) {
									if(field == "") return "Introduce una contraseÃ±a.\n";
									else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field))
										return "La direcciÃ³n de correo electrÃ³nico es invÃ¡lida.\n"
									return "";
								}
								
								function validateEqualsCorreo(field,field2){
									if(field !=field2) return "Los correos no son iguales.\n";
									return "";
								}
														
							</script>                        	
							
							<form action="regPadre.php" method="post" onsubmit="return validate(this)">
								<input type="text"  value="<?php echo $usuario;?>" name="usuario" alt="Usuario:" title="Escribe su usuario" id="usuario" />																				
								<input type="text" value="<?php echo $name;?>" name="name" alt="Nombre:" title="Escriba su nombre" id="name"  />									
								<input type="text" value="<?php echo $apellidoPat;?>" name="apellidoP" alt="Apellido Paterno: " title="Escriba su apellido paterno" id="apellidoP" />									
								<input type="text" value="<?php echo $apellidoMat;?>" name="apellidoM" alt="Apellido Materno:" title="Escriba su apellido materno" id="apellidoM" />
								<input type="text" value="<?php echo $telefono;?>" name="telefono" alt="Telefono:" title="Escriba su telefono de contacto" id="telefono" />										
								<input type="password" value="<?php echo $password;?>" name="password" alt="ContraseÃ±a: " title="Escriba su contrasenia" id="password" />								
								<input type="password" value="<?php echo $password2;?>" name="password2" alt="Confirmar ContraseÃ±a: " title="Repita contrasenia" id="password2" />						
								<input type="text" value="<?php echo $correo;?>" name="correo" alt="Correo electronico: " title="Escriba un correo electronico valido"  id="correo"  />						
								<input type="text" value="<?php echo $correo2;?>" name="correo2" alt="Confirmar Correo electronico: " title="Repita correo electronico" id="correo2"/>								
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
            <a href="index.html" id="logo">Foundation</a>
            
            <!-- Main Naigation (active - .act) -->
            <div id="main-nav">
                 <ul>
                    <li class="act"><a href="index.php">Inicio</a></li>
                    <li>
                        <a href="ProfesionalSaludPrincipal.php">Profesional de Salud</a>                        
                    </li>
                    <li>
                        <a href="padrePrincipal.php">Padres de familia</a>                      
                    </li>
                    <li><a href="escuelaPrincipal.php">Escuelas</a></li>                    
                </ul>
            </div>
            
            <!-- News Widget -->
            <div class="widget w-news">
                
                <div class="w-content">
                    
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
                        <a href="#"><img src="../img/pictures/zamudio.png" alt="Our Building" class="alignright" /></a>
                        Tijuana, B.C., M&eacute;xico<br />
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
