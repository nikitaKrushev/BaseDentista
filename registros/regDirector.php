<?php
/**
 * Autor: Josu� Casta�eda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 *
 * Descripcion:
 * 	Registra un director en la base de datos, similar al registro de
 *  padres.
 *
 */

include '../accesoDentista.php';

if ($_SESSION['type'] != 6) { //Checamos si hay una session vacia o si ya hay una sesion
	echo("Contenido Restringido");
	switch($_SESSION['type']) {
		case 1: //Dentista
			header("refresh:3, url=../principales/mainDentista2.php");
			break;
				
		case 2: //Padre
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3://Maestro
			header("refresh:3;url=../principales/padrePrincipal.php");
			break;

		case 4://Director
			header("refresh:3;url=../principales/directorPrincipal.php");
			break;

		case 5://Admin
			header("refresh:3;url=../principales/profesionalPrincipal.php");
			break;
	}
	exit;
}

//Aqui consigo los valores de la ciudad
$query = @mysql_query("SELECT Nombre FROM Ciudad");

while ($existe = @mysql_fetch_object($query))
	$escuelas[] = $existe;
$size= count($escuelas);

//Aqui el nombre de los estados
$query = @mysql_query("SELECT Nombre FROM Estado");

while ($existe = @mysql_fetch_object($query))
	$estados[] = $existe;
$size2= count($estados);

if(isset($_POST['posted'])) {
	require_once('../funciones.php');
	conectar($servidor, $user, $pass, $name);

	//Director
	$usuario = strip_tags($_POST['usuario']);
	$nombre = strtoupper(strip_tags($_POST['nombre']));
	$password = strip_tags($_POST['password']);
	$password2 = strip_tags($_POST['password2']);
	$apaterno = strtoupper(strip_tags($_POST['apaterno']));
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);

	//Escuela
	$idnEscuela = strip_tags($_POST['idEscuela']);
	$nombreEsc = strip_tags($_POST['nomEsc']);
	
	//Direccion
	$colonia = strtoupper(strip_tags($_POST['colonia']));
	$calle = strtoupper(strip_tags($_POST['calle']));
	$numPostal = strip_tags($_POST['numPostal']);
	$ciudad =strip_tags($_POST['ciudad']);
	$estado =strip_tags($_POST['estado']);
	
	$to = $correo;
	$nameto = $nombre." ".$apaterno;
	$from = "registro@cartillabucaldigital.org";
	$namefrom = "Registro de cuentas";
	$subject = "Registro exitoso de cartilla bucal digital";
	$message =  $nombre." ".$apaterno." "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!
			\r\n. Tu usuario es: ".$usuario."\r\n Tu contrase�a: ".$password.
			"\r\n. Recuerda escribir en alg�n lugar seguro esta informaci�n, para que no se pierdan tus datos
			\r\n.Si tienes dudas o comentarios no dudes en escribir a contacto@cartillabucaldigital.org"; //Pondremos contrasenia y usuario al usuario	
			
	//Para la validacion
	$fail = validaNombre(trim($nombre));
	$fail .= validaPaterno($apaterno,1);
	$fail .=validaPass($password);
	$fail .= validaEqualPass($password,$password2);
	$fail .= validaCorreo($correo);
	$fail .= validaEqualCorreo($correo,$correo2);

	$fail .= validaEscuela($idnEscuela);
	$fail .= validaNombre(trim($nombreEsc));
	
	$fail .= validaColonia(trim($colonia),1);
	$fail .= validaColonia(trim($calle),1);
	$fail .= validaConsultorio($numPostal);
	//$fail .= validaConsultorio($ciudad);
	//$fail .= validaEstado($estado);

	if($fail == "") { //IF A
		$query = @mysql_query('SELECT * FROM Dentista WHERE Usuario="'.mysql_real_escape_string($usuario).'"');
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			echo $fail;
			header("refresh:3;url=regDirector.php");
		}else{//ELSE F
				
			$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				echo $fail;
				header("refresh:3;url=regDirector.php");
			}
			else { //ELSE E
				$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					echo $fail;
					header("refresh:3;url=regDirector.php");
				}
				else { //ELSE D
					$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						echo $fail;
						header("refresh:3;url=regDirector.php");
					}
					else { //ELSE C
						$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							echo $fail;
							header("refresh:3;url=regDirector.php");
						}
						else { //ELSE B
								
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								echo $fail;
								header("refresh:3;url=regDirector.php");
							}

							else { //ELSE A
								//echo "djdifjdiogjrio";
								$password_enc = sha1($password);

								//Primero obtenemos el identificador del pais
								$cadena = "select * from Estado where Nombre='".mysql_real_escape_string($estado)."'";
								$meter1 = @mysql_query($cadena);
								$idPais = @mysql_fetch_object($meter1);
								
								//Despues el id de la ciudad
								$cadena_ciudad = "select * from Ciudad where Nombre='".mysql_real_escape_string($ciudad)."' && Estado_Nombre='".mysql_real_escape_string($estado)."'";
								// "select * from Ciudad where Nombre='".mysql_real_escape_string($ciudad)."' && Estado_Nombre='".mysql_real_escape_string($estado)."'";
								$meter2 = @mysql_query($cadena_ciudad);
								$idCiuda = @mysql_fetch_object($meter2);
																
								//Despues la direccion

								$meter2 = @mysql_query('INSERT INTO Direccion (Colonia,Calle,Ciudad_idCiudad,Ciudad_Estado_Nombre,Ciudad_Estado_Pais_Nombre,NumeroPostal) VALUES
										("'.mysql_real_escape_string($colonia).'","'.mysql_real_escape_string($calle).'", '.mysql_real_escape_string($idCiuda->idCiudad).',"'.mysql_real_escape_string($estado)
										.'","'.mysql_real_escape_string($idPais->Pais_Nombre).'","'.mysql_real_escape_string($numPostal).'")');								
								
								//Obtengo el identificador de la direccion
								$meter3 = @mysql_query('SELECT * from Direccion where Colonia="'.mysql_real_escape_string($colonia).'"&& Calle ="'.mysql_real_escape_string($calle).'"
										&& Ciudad_idCiudad="'.mysql_real_escape_string($idCiuda->idCiudad).'" && Ciudad_Estado_Nombre="'.mysql_real_escape_string($estado).'"
										&& Ciudad_Estado_Pais_Nombre="'.mysql_real_escape_string($idPais->Pais_Nombre).'" && NumeroPostal ="'.mysql_real_escape_string($numPostal).'"');

								$idDireccion2 = @mysql_fetch_object($meter3);

								//Despues Escuela
									
								$meter4 = @@mysql_query('INSERT INTO Escuela VALUES  ("'.mysql_real_escape_string($idnEscuela).
										'","'.mysql_real_escape_string($nombreEsc).'","'.$idDireccion2->idDireccion.'")');
																							
								//Finalmente meter en el dentista																							
								$meter=@mysql_query('INSERT INTO Director (idDirector,Nombre,Password,ApellidoPaterno,Escuela_idEscuela,Escuela_Direccion_idDireccion,Correo) 
									values ("'.mysql_real_escape_string($usuario).' ","'.mysql_real_escape_string($nombre).'", "'.$password_enc.
										'","'.mysql_real_escape_string($apaterno).'","'.mysql_real_escape_string($idnEscuela).'","'.$idDireccion2->idDireccion.'","'.mysql_real_escape_string($correo).'")');
									
								if($meter){
									echo 'Usuario registrado con exito';
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									echo 'Mail sent!';
									header("refresh:3;url=../principales/adminPage.php");
								}
								else {
									$fail .= 'Hubo un error';
									echo $fail;
								}
							} //ELSE A
						} //ELSE B
					}	//ELSE C
				} //ELSE D
			}//ELSE E
		}//ELSE F
	} //IF A
}
else {
	$nombre = "*Primer Nombre:";
	$apaterno = "*Apellido Paterno:";
	$usuario = "*Usuario:";
	$password = "*Contraseña:";
	$password2 = "Repite contraseña:";
	$correo = "*Correo electrónico";
	$correo2 = "Repite correo electrónico";
	$nombreEsc = "*Nombre del Consultorio:";
	$idnEscuela= "*Identificador Escuela";	
	$colonia = "*Colonia:";
	$calle = "*Calle:";
	$numPostal = "*Numero postal:";
	$ciudad ="*Ciudad:";
	$estado ="*Estado:";
}

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validaEstado($estado) {
	if ($estado =="") return "Favor de llenar el campo Estado.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$estado ))
		return "El campo Estado solo contiene letras.\n";
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

function validaColonia($field,$i) {
	if ($field =="")
		if ($i ==1)
		return "Favor de llenar el campo colonia.\n";
	else
		return "Favor de llenar el campo calle.\n";
}

function validaPass($field) {
	if($field == "") return "Introduce una contraseña.\n";
	else{
		if (strlen($field) < 5)
			return "El tamaño de la contraseña debe ser por lo menos de 5 caracteres.\n";
		else
			if (! preg_match("/[a-z]/",$field) || ! preg_match("/[0-9]/",$field))
			return "La contraseña requiere por lo menos un caracter de [a-z] y [0-9].\n";
	}
	return "";
}

function validaEqualPass($field,$field2) {
	if($field !=$field2) return "Las contraseñas no son iguales.\n";
	return "";
}

function validaCorreo($field) {
	if ($field == "") return "Introduce un correo valido.\n";
	else if (!((strpos($field, ".") > 0) &&
			(strpos($field, "@") > 0))  ||
			preg_match("/[^a-zA-Z0-9.@_-]/",$field))
		return "La dirección de correo electrónico es inválida".$field."\n";
	return "";
}

function validaEqualCorreo($field,$field2){
	if($field !=$field2) return "Los correos no son iguales.\n";
	return "";
}

function validaConsultorio($consultorio) {
	if (! preg_match("/^[0-9]+$/",$consultorio))
		return "El consultorio requiere digitos.\n";
	return "";
}

function validaEscuela($escuela) {
	if ($escuela == "") return "Introduce un identificador de escuela valido.\n";
	return "";	
}

function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message)
{
	//SMTP + Detalles del servidor
	/* * * * Inicia configuraci�n * * * */
	$smtpServer = "mail.cartillabucaldigital.org";
	$port = "25";
	$timeout = "30";
	$username = "registro@cartillabucaldigital.org";
	$password = "l@c0yota719p0r";
	$localhost = "localhost";
	$newLine = "\r\n";
	/* * * * Termina configuraci�n * * * * */

	//Conexi�n al servidor en el puerto espec�fico
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

	//Env�o de usuario
	fputs($smtpConnect, base64_encode($username) . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authusername'] = "$smtpResponse";

	//Env�o de password
	fputs($smtpConnect, base64_encode($password) . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authpassword'] = "$smtpResponse";

	//Saludo a SMTP
	fputs($smtpConnect, "HELO $localhost" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['heloresponse'] = "$smtpResponse";

	//Env�a correo desde
	fputs($smtpConnect, "MAIL FROM: $from" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['mailfromresponse'] = "$smtpResponse";

	//Env�a correo a
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

<body id="home">
	<!-- #home || #page-post || #blog || #portfolio -->

	<!-- Page Start -->
	<div id="page">

		<!-- Main Column Start -->
		<div id="wrap">
			<div id="main-col">
				<!-- Nivo Slider -->

				<!-- Homepage Welcome Text -->
				<div id="homepage-post">
					<h1 class="p-title">
						<a href="#">Bienvenido al sitio de la Cartilla de Salud Bucal
							Digital</a>
					</h1>
					<div class="p-content">
						<p>Perfil epidemiológico de caries dental</p>
						<p>Página de registro de Director</p>
						<p>Los campos marcados como * son obligatorios</p>
						
						<?php 
						if(isset($_POST['posted'])) {

							echo $fail;
						}
						?>
					</div>

					<div id="registra">
						<ul>
							<li>
								<script type="text/javascript">
									function validate(form){
										fail = validateNombre(form.nombre.value);
										fail += validatePaterno(form.apaterno.value,1);
										fail += validatePass(form.password.value);
										fail += validateEqualPass(form.password2.value,form.password.value);
										fail += validateCorreo(form.correo.value);
										fail += validateCorreo(form.correo2.vaue);
										fail += validateEqualCorreo(form.correo.value,form.correo2.value);

										fail += validateEscuela(form.idnEscuela.value);
										fail += validateNombre(form.nomEsc.value);

										fail += validateNombre(form.nombreCons.value);
										fail += validateEscuela(form.colonia.value,1);
										fail += validateColonia(form.calle.value,1);
										fail += validateConsultorio(form.numPostal.value);
										fail += validateConsultorio(form.ciudad.value);
																														
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

									function validateEscuela(field) {
										if (field =="") return "Favor de llenar el campo Escuela.\n";
										return "";
									}																		
									
									function validatePaterno(field,tipo) {
										if (field =="") {
											if(tipo == 1)
												return "Favor de llenar el campo apellido paterno.\n";
											else
												return "Favor de llenar el campo apellido materno.\n";
										}
										else
											if (! /^[a-zA-Z]+$/.test(field) )
												return "Los apellidos contienen solo letras.\n";
										return "";
									}																		
									
									function validatePassword(field){
										if(field == "") return "Introduce una contraseña.\n";
										else
											if (field.length < 5)
												return "El tamaño de la contraseña debe ser por lo menos de 5 caracteres.\n";
											else 
												if (! /[a-z]/.test(field) || ! /[0-9]/.test(field))
													return "La contraseña requiere por lo menos un caracter de [a-z] y [0-9].\n";					
										return "";		
									}
										
									function validatePasswordEqual(field,field2) {
										if(field !=field2) return "Las contraseñas no son iguales.\n";
										return "";
									}
									
									function validateCorreo(field) {
										if(field == "") return "Introduce una contraseña.\n";
										else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field))
											return "La dirección de correo electrónico es inválida.\n"
										return "";
									}
									
									function validateEqualsCorreo(field,field2){
										if(field !=field2) return "Los correos no son iguales.\n";
										return "";
									}
									
									function validateConsultorio(field) {
										if (! /^[0-9]+$/.test(field))
											return "El campo Consultorio requiere digitos.\n";					
										return "";
									}
									
								</script>
								<span style="color:red">Datos del director de la escuela </span>
									
								<form action="regDirector.php" method="post" onsubmit="return validate(this)">
									<input type="text" value="<?php echo $nombre;?>" name="nombre" alt="*Nombre(s): " title="Introduce tu primer nombre" id="nombre" /> 
									<input type="text" value="<?php echo $apaterno;?>" name="apaterno" alt="*Apellido paterno:" title="Introduce tu apellido paterno" id="apaterno" /> 
									<input type="text" value="<?php echo $usuario;?>" name="usuario" alt="*Usuario:" title="Introduce un usuario" id="usuario" /> 
									<input type="password" value="<?php echo $password;?>" name="password" alt="Contraseña:" title="Introduce tu contraseña, de al menos 5 caracteres" id="password" /> 
									<input type="password" value="<?php echo $password2;?>" name="password2" alt="Confirmar Contraseña: " title="Repite la contraseña" id="password2" /> 
									<input type="text" value="<?php echo $correo;?>" name="correo" alt="*Correo electronico: " title="Introduce tu correo electronico" id="correo" /> 
									<input type="text" value="<?php echo $correo2;?>" name="correo2" alt="Confirmar Correo electronico: " title="Repite tu correo electronico" id="correo2" /> 
									<br> </br> <span style="color:red">Escuela </span>
									<input type="text" value="<?php echo $nombreEsc;?>" name="nomEsc" alt="*Nombre de la escuela:" title="Pon el nombre de la escuela" id="nomEsc"/>
									<input type="text" value="<?php echo $idnEscuela;?>" name="idEscuela" alt="*Identificador escuela:" title="Pon el identificador de la escuela" id="idEscuela"/>
									<br> </br> <span style="color:red">Direccion de la Escuela </span>
									<input type="text" value="<?php echo $colonia;?>" name="colonia" alt="*Colonia:" title="Pon la colonia donde se encuentra el consultorio" id="colonia"/>
									<input type="text" value="<?php echo $calle;?>" name="calle" alt="*Calle:" title="Pon la calle donde se encuentra el consultorio" id="calle"/>
									<input type="text" value="<?php echo $numPostal;?>" name="numPostal" alt="*Numero postal:" title="Pon el numero postal donde se encuentra el consultorio" id="numPostal"/>
									<select name="ciudad">
									<?php
									//<input type="text" value="<?php echo $ciudad;?" name="ciudad" alt="*Ciudad:" title="Pon la ciudad donde se encuentra el consultorio" id="ciudad"/>
									//<input type="text" value="<?php echo $ciudad;>" name="ciudad" alt="*Ciudad:" title="Pon la ciudad donde se encuentra el consultorio" id="ciudad"/>
									//<input type="text" value="<?php echo $estado;>" name="estado" alt="*Estado:" title="Pon el estado postal donde se encuentra el consultorio" id="estado"/>
										if(isset($size)) {
										for($i=0; $i<$size; $i++) {
									?>
										<option value="<?php echo $escuelas[$i]->Nombre;?>" ><?php echo $escuelas[$i]->Nombre;?> </option>
									<?php }} 
									?>										
									</select>
									<select name="estado">									
									<?php
									//<input type="text" value="<?php echo $ciudad;?" name="ciudad" alt="*Ciudad:" title="Pon la ciudad donde se encuentra el consultorio" id="ciudad"/>
									//<input type="text" value="<?php echo $estado;>" name="estado" alt="*Estado:" title="Pon el estado postal donde se encuentra el consultorio" id="estado"/>
									 
										if(isset($size2)) {
										for($i=0; $i<$size2; $i++) {
									?>
										<option value="<?php echo $estados[$i]->Nombre;?>" ><?php echo $estados[$i]->Nombre;?> </option>
									<?php }} 
									?>										
									</select>																		
									<br></br>																																																																										
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
			<a href="../principales/adminPage.php" id="logo">Foundation</a>

			<!-- Main Naigation (active - .act) -->
			<div id="main-nav">
				<ul>
                    <li class="act"><a href="../principales/adminPage.php">Inicio</a></li>
                    <li>
                        <a href="../registros/regAdminDent.php">Registro de Dentistas</a>
                    </li>
                    <li>
                        <a href="../registros/regDirector.php">Registro de Directores</a>      
                    </li>
                    <li>
                        <a href="../registros/regNinio.php">Registro de Pacientes</a>      
                    </li>                 
                    
                    <li>
                        <a href="../construccion.html">Solicitudes pendientes</a>      
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
								Usuario:
								<?php echo $_SESSION["uid"];?>
								<input type="submit" value="Fin de sesión" />
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
				<div id="copyright">&copy; 2012 Miguel Alberto Zamudio | UABC</div>
			</div>

			<!-- Footer Widgets -->
			<div id="f-main-col">
				<!-- Links -->
				<div class="widget w-25 w-links"></div>
				<!-- Social -->
				<div class="widget w-25 w-links"></div>
				<!-- Contact Info -->
				<div class="widget w-50 w-text last" id="text-1">
					<h5 class="w-title">Contacto:</h5>
					<div class="w-content">
						<a href="#"><img src="../img/pictures/zamudio.png" alt="Our Building"
							class="alignright" /> </a> Tijuana, B.C., México<br /> Tel.: 664
						400 7866<br /> <a href="#">cartillasaludbucal@gmail.com</a>
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