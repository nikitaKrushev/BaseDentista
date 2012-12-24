<?php
session_start();
if(isset($_POST['posted'])) {

	require_once('../funciones.php');
	conectar('localhost', 'monty', 'holygrail', 'BaseDientes');
	$mensaje = "Registro no exitoso, se encontraron los siguientes<br />
	errores en el formulario:";

	//recibe info
	$nombre = strip_tags($_POST['nombre']);
	$nombre2 = strip_tags($_POST['nombre2']);
	$apaterno = strip_tags($_POST['apaterno']);
	$amaterno = strip_tags($_POST['amaterno']);
	$cedula = strip_tags($_POST['cedula']);
	$user = strip_tags($_POST['user']);
	$pass = strip_tags($_POST['pass']);
	$pass2 = strip_tags($_POST['pass2']);
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);
	$consultorio = strip_tags($_POST['consultorio']);

	//Mail
	$name = "Code Assist"; //Nombre del remitente
	$mail_from = "baseDentista@gmail.com"; //Correo del remitente
	$mail_to = $correo; //Correo receptor
	$mail_body = "Tu registro ha sido capturado. Dentro de poco recibiras un correo de confirmacion, para que puedas
	comenzar a utilizar el servicio";
	$mail_subject = "Mail from: ".$name;
	$mail_header = "From: ".$name." <".$mail_from.">\r\n";

	//Para la validacion
	$fail = validaNombre(trim($nombre));
	$fail .= validaNombre2(trim($nombre2));
	$fail .= validaPaterno($apaterno,1);
	$fail .= validaPaterno($amaterno,2);
	$fail .= validaCedula($cedula);
	$fail .=validaPass($pass);
	$fail .= validaEqualPass($pass,$pass2);
	$fail .= validaCorreo($correo);
	$fail .= validaEqualCorreo($correo,$correo2);
	$fail .= validaConsultorio($consultorio);

	echo "<html><head><title>Registro Clinica</title>";
	if($fail == "") {

		$query = @mysql_query('SELECT * FROM Dentista WHERE user="'.mysql_real_escape_string($user).'"');
		if($existe = @mysql_fetch_object($query)){
			echo 'Este usuario '.$user.' ya existe';
		}else{
			$queryDir = @mysql_query('SELECT Direccion_idDireccion FROM Consultorio WHERE idConsultorio ="'.$consultorio.'"');
			$idDir = @mysql_fetch_object($queryDir);

			//Encriptamos contrasenia
			$pass_enc = sha1($pass);

			$meter=@mysql_query('INSERT INTO Dentista (Nombre, ApellidoPaterno, ApellidoMaterno, Cedula, Password, Usuario, CorreoElectronico, Consultorio_idConsultorio, Consultorio_Direccion_idDireccion, 2d0_Nombre) values ("'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($apaterno).'", "'.mysql_real_escape_string($amaterno).'","'.mysql_real_escape_string($cedula).'","'.$pass_enc.'","'.mysql_real_escape_string($user).'","'.mysql_real_escape_string($correo).'","'.mysql_real_escape_string($consultorio).'","'.mysql_real_escape_string($idDir).'","'.mysql_real_escape_string($nombre2).'")');
			echo 'INSERT INTO Dentista (Nombre, ApellidoPaterno, ApellidoMaterno, Cedula, Password, Usuario, CorreoElectronico, Consultorio_idConsultorio, Consultorio_Direccion_idDireccion, 2d0_Nombre) values ("'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($apaterno).'", "'.mysql_real_escape_string($amaterno).'","'.mysql_real_escape_string($cedula).'","'.$pass_enc.'","'.mysql_real_escape_string($user).'","'.mysql_real_escape_string($correo).'","'.mysql_real_escape_string($consultorio).'","'.mysql_real_escape_string($idDir).'","'.mysql_real_escape_string($nombre2).'")';
			if($meter){
				echo 'Usuario registrado con exito';
				//Enviar correo

				//Optional si tengo SMTP.

				//ini_set("SMTP",smtp.rdslink.ro) //El proovedor de internet

				//Send Message

				$sendmail = mail($mail_to,$mail_subject,$mail_body,$mail_header);

				if($sendmail == true) {
					echo 'Mail sent!';
				}
				else {
					echo 'Mail not sent';
				}


			}else{
				echo 'Hubo un error';
			}
		}
		exit;
	}
}

else {
	$nombre = "Primer Nombre:";
	$nombre2 = "Segundo Nombre:";
	$apaterno = "Apellido Paterno:";
	$amaterno = "Apellido Materno";
	$cedula = "Cedula Profesional:";
	$user = "Usuario:";
	$pass = "Contraseña:";
	$pass2 = "Repite contraseña:";
	$correo = "Correo electrónico";
	$correo2 = "Repite correo electrónico";
	$consultorio = "Consultorio";
}

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validaNombre2($nombre2) {
	if ($nombre !=""){
		if (! preg_match("/^[a-zA-Z]+$/",$nombre2 ))
			return "El campo Nombre solo contiene letras Nombre.\n";
	}
	return "";
}

function validaPaterno($nombre,$tipo) {
	if ($field =="") {
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

function validaCedula($field) {
	if ($field =="") return "Favor de llenar el campo cedula.\n";
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
	if ($field == "") return "Introduce una contraseña.\n";
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
						<p>Página de registro de Capturista</p>
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
										fail += validateNombre2(form.nombre2.value);
										fail += validatePaterno(form.apaterno.value,1);
										fail += validatePaterno(form.amaterno.value,2);
										fail += validateCedula(form.cedula.value);
										fail += validatePass(form.pass.value);
										fail += validatePass(form.pass2.value);
										fail += validateEqualPass(form.pass2.value,form.pass.value);
										fail += validateCorreo(form.correo.value);
										fail += validateCorreo(form.correo2.vaue);
										fail += validateEqualCorreo(form.correo.value,form.correo2.value);
										fail += validateConsultorio(form.consultorio.value);
										
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
									
									function validateNombre2(field) {
										if (! /^[a-zA-Z]+$/.test(field) )
												return "El campo Nombre solo contiene letras.\n";
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
									
									function validateCedula(field) {
										if (field =="") return "Favor de llenar el campo cedula.\n";
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
								<form action="regDentista.php" method="post" onSubmit="return validate(this)">
									<input type="text" value="<?php echo $nombre;?>" name="nombre" alt="Primer Nombre: " title="Introduce tu primer nombre" id="nombre" /> 
									<input type="text" value="<?php echo $nombre2;?>" alt="Segundo Nombre: " title="Introduce tu segundo nombre" name="nombre2" size="20" id="nombre2" /> 
									<input type="text" value="<?php echo $apaterno;?>" name="apaterno" alt="Apellido paterno:" title="Introduce tu apellido paterno" id="apaterno" /> 
									<input type="text" value="<?php echo $amaterno;?>" name="amaterno" alt="Apellido materno:" title="Introduce tu apellido materno" id="amaterno" /> 
									<input type="text" value="<?php echo $cedula;?>" name="cedula" alt="Cédula:" title="Introduce tu cedula profesional" id="cedula" /> 
									<input type="text" value="<?php echo $user;?>" name="user" alt="Usuario:" title="Introduce un usuario" id="user" /> 
									<input type="password" value="<?php echo $pass;?>" name="pass" alt="Contraseña:" title="Introduce tu contraseña, de al menos 5 caracteres" id="pass" /> 
									<input type="password" value="<?php echo $pass2;?>" name="pass2" alt="Confirmar Contraseña: " title="Repite la contraseña" id="pass2" /> 
									<input type="text" value="<?php echo $correo;?>" name="correo" alt="Correo electronico: " title="Introduce tu correo electronico" id="correo" /> 
									<input type="text" value="<?php echo $correo2;?>" name="correo2" alt="Confirmar Correo electronico: " title="Repite tu correo electronico" id="correo2" /> 
									<input type="text" value="<?php echo $consultorio;?>" name="consultorio" alt="Consultorio:" title="Introduce tu consultorio" id="consultorio" /> 
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
					<li class="act"><a href="index.html">Inicio</a></li>
					<li><a href="contruccion.html">Consultorio</a>
					</li>
					<li><a href="construccion.html">Dentista </a>
					</li>
					<li><a href="construccion.html">Escuela</a>
					</li>
					<li><a href="construccion.html">Paciente</a>
					</li>

					<li><a href="construccion.html">Solicitudes pendientes</a>
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
				<div id="sidebar-end">
					<form action="#" id="subscribe">
						<input type="text" value="" alt="Recibe las �ltimas noticias!"
							title="Escribe tu correo" /> <input type="submit" value=""
							title="Subscribe" />
					</form>
				</div>
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
						<a href="#"><img src="img/pictures/zamudio.png" alt="Our Building"
							class="alignright" /> </a> Tijuana, B.C., M�xico<br /> Tel.: 664
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