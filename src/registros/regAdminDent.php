<?php
//session_start();
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

if(isset($_POST['posted'])) {
	require_once('../funciones.php');
	conectar($servidor, $user, $pass, $name);
	
	
	echo "TROLAZOZOZOZOZOZOZOZOZO";
	$nombre = strip_tags($_POST['nombre']);
	$apaterno = strip_tags($_POST['apaterno']);
	$amaterno = strip_tags($_POST['amaterno']);
	$cedula = strip_tags($_POST['cedula']);
	$usuario = strip_tags($_POST['usuario']);
	$password = strip_tags($_POST['password']);
	$password2 = strip_tags($_POST['password2']);
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);
	
	$nombreCons = strip_tags($_POST['nombreCons']);
	$horarioAper = strip_tags($_POST['horarioAper']);
	$horarioClau = strip_tags($_POST['horarioClau']);
	$telefono = strip_tags($_POST['telefono']);
	$institucion = strip_tags($_POST['institucion']);
	
	$colonia = strip_tags($_POST['colonia']);
	$calle = strip_tags($_POST['calle']);
	$numPostal = strip_tags($_POST['numPostal']);
	$ciudad =strip_tags($_POST['ciudad']);
	$estado =strip_tags($_POST['estado']);
	
	//Mail
	$name = "Code Assist"; //Nombre del remitente
	$mail_from = "cartillasaludbucal@gmail.com"; //Correo del remitente
	$mail_to = $correo; //Correo receptor
	$mail_body = $nombre." ".$apaterno." ".$amaterno."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!";
	$mail_subject = "Mail from: ".$name;
	$mail_header = "From: ".$name." <".$mail_from.">\r\n";

	//Para la validacion
	$fail = validaNombre(trim($nombre));
	$fail .= validaPaterno($apaterno,1);
	$fail .= validaPaterno($amaterno,2);
	$fail .= validaCedula($cedula);
	$fail .=validaPass($password);
	$fail .= validaEqualPass($password,$password2);
	$fail .= validaCorreo($correo);
	$fail .= validaEqualCorreo($correo,$correo2);
	
	
	$fail .= validaNombre(trim($nombreCons));
	$fail .= validaColonia(trim($colonia),1);
	$fail .= validaColonia(trim($calle),1);
	$fail .= validaConsultorio($numPostal);	
	$fail .= validaCiudad($ciudad);
	$fail .= validaEstado($estado);
	
	if($fail == "") { //IF A
		$query = @mysql_query('SELECT * FROM Dentista WHERE Usuario="'.mysql_real_escape_string($usuario).'"');
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			echo $fail;
			header("refresh:3;url=regAdminDent.php");
		}else{//ELSE F
			
			$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				echo $fail;
				header("refresh:3;url=regAdminDent.php");
			}
			else { //ELSE E
				$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					echo $fail;
					header("refresh:3;url=regAdmin.php");
				}
				else { //ELSE D
					$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						echo $fail;
						header("refresh:3;url=regAdmin.php");
					}
					else { //ELSE C
						$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							echo $fail;
							header("refresh:3;url=regAdmin.php");
						}
						else { //ELSE B
													
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								echo $fail;
								header("refresh:3;url=regAdmin.php");
							}

							else { //ELSE A																					
								$password_enc = sha1($password);

								//Primero obtenemos el identificador del pais
								//$cadena = "select Pais_Nombre from Estado where Nombre='".mysql_real_escape_string($estado)."'";
								$cadena = "select * from Estado where Nombre='".mysql_real_escape_string($estado)."'";								
								$meter1 = @mysql_query($cadena);
								$idPais = @mysql_fetch_object($meter1);
								//echo $idPais->Pais_Nombre;
								
								//Despues la direccion								
																
								$meter2 = @mysql_query('INSERT INTO Direccion (Colonia,Calle,Ciudad_idCiudad,Ciudad_Estado_Nombre,Ciudad_Estado_Pais_Nombre,NumeroPostal) VALUES  
										("'.mysql_real_escape_string($colonia).'","'.mysql_real_escape_string($calle).'", '.mysql_real_escape_string($ciudad).',"'.mysql_real_escape_string($estado)
											.'","'.mysql_real_escape_string($idPais->Pais_Nombre).'","'.mysql_real_escape_string($numPostal).'")');								
																
								//Obtengo el identificador de la direccion
								$meter3 = @mysql_query('SELECT * from Direccion where Colonia="'.mysql_real_escape_string($colonia).'"&& Calle ="'.mysql_real_escape_string($calle).'" 
									&& Ciudad_idCiudad="'.mysql_real_escape_string($ciudad).'" && Ciudad_Estado_Nombre="'.mysql_real_escape_string($estado).'"
										&& Ciudad_Estado_Pais_Nombre="'.mysql_real_escape_string($idPais->Pais_Nombre).'" && NumeroPostal ="'.mysql_real_escape_string($numPostal).'"');
								
								$idDireccion2 = @mysql_fetch_object($meter3);
								
								//echo $idDireccion2->idDireccion;								
																								
								//Despues Consultorio
													
								$meter4 = @@mysql_query('INSERT INTO Consultorio (Nombre, HoraApertura, HoraCerrado, Telefono, Institucion,Direccion_idDireccion) 
											VALUES 	("'.mysql_real_escape_string($nombreCons).'","'.mysql_real_escape_string($horarioAper).'", 
												"'.mysql_real_escape_string($horarioClau).'","'.mysql_real_escape_string($telefono)
													.'","'.mysql_real_escape_string($institucion).'","'.$idDireccion2->idDireccion.'")');								
								
								//Obtengo el identificador del consultorio
								
								$meter5 = @@mysql_query('SELECT idConsultorio from Consultorio where Nombre="'.mysql_real_escape_string($nombreCons).'" && Direccion_idDireccion = "'.mysql_real_escape_string($idDireccion2->idDireccion).'"');																			
								
								$idConsultorio2 = @mysql_fetch_object($meter5);

								//Finalmente meter en el dentista
								$meter=@mysql_query('INSERT INTO Dentista (Cedula, Nombre, ApellidoPaterno, ApellidoMaterno, Password, Usuario, CorreoElectronico, 
										Consultorio_idConsultorio) values ("'.mysql_real_escape_string($cedula).' ","'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
											'","'.mysql_real_escape_string($amaterno).'","'.$password_enc.'","'.mysql_real_escape_string($usuario).'","'.mysql_real_escape_string($correo).'","'
														.mysql_real_escape_string($idConsultorio2->idConsultorio).'")');		

								echo 'INSERT INTO Dentista (Cedula, Nombre, ApellidoPaterno, ApellidoMaterno, Password, Usuario, CorreoElectronico, 
										Consultorio_idConsultorio) values ("'.mysql_real_escape_string($cedula).' ","'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
											'","'.mysql_real_escape_string($amaterno).'","'.$password_enc.'","'.mysql_real_escape_string($usuario).'","'.mysql_real_escape_string($correo).'","'
														.mysql_real_escape_string($idConsultorio2->idConsultorio).'")';
								if($meter){
									echo 'Usuario registrado con exito';
									$sendmail = mail($mail_to,$mail_subject,$mail_body,$mail_header);

									if($sendmail == true) 
										echo 'Mail sent!';									
									else 
										echo 'Mail not sent';
									
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
	$amaterno = "*Apellido Materno";
	$cedula = "*Cedula Profesional:";
	$usuario = "*Usuario:";
	$password = "*Contraseña:";
	$password2 = "Repite contraseña:";
	$correo = "*Correo electrónico";
	$correo2 = "Repite correo electrónico";
	$nombreCons = "*Nombre del Consultorio:";
	$horarioAper = "Horario de Apertura:";
	$horarioClau = "Horario de Clausura:";
	$telefono = "Telefono de contacto:";
	$institucion = "Institucion de procedencia:";
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

function validaCedula($field) {
	if ($field =="") return "Favor de llenar el campo cedula.\n";
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
	return "El numero postal requiere digitos.\n";
return "";
}

function validaCiudad($consultorio) {
	if (! preg_match("/^[0-9]+$/",$consultorio))
		return "La ciudad requiere digitos.\n";
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
						<p>Página de registro de Dentista</p>
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
										fail += validatePaterno(form.amaterno.value,2);
										fail += validateCedula(form.cedula.value);
										fail += validatePass(form.password.value);
										fail += validateEqualPass(form.password2.value,form.password.value);
										fail += validateCorreo(form.correo.value);
										fail += validateCorreo(form.correo2.vaue);
										fail += validateEqualCorreo(form.correo.value,form.correo2.value);

										fail += validateNombre(form.nombreCons.value);
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
								<span style="color:red">Datos personales </span>
									
								<form action="regAdminDent.php" method="post" onsubmit="return validate(this)">
									<input type="text" value="<?php echo $nombre;?>" name="nombre" alt="*Nombre(s): " title="Introduce tu primer nombre" id="nombre" /> 
									<input type="text" value="<?php echo $apaterno;?>" name="apaterno" alt="*Apellido paterno:" title="Introduce tu apellido paterno" id="apaterno" /> 
									<input type="text" value="<?php echo $amaterno;?>" name="amaterno" alt="*Apellido materno:" title="Introduce tu apellido materno" id="amaterno" /> 
									<input type="text" value="<?php echo $cedula;?>" name="cedula" alt="*Cédula:" title="Introduce tu cedula profesional" id="cedula" /> 
									<input type="text" value="<?php echo $usuario;?>" name="usuario" alt="*Usuario:" title="Introduce un usuario" id="usuario" /> 
									<input type="password" value="<?php echo $password;?>" name="password" alt="Contraseña:" title="Introduce tu contraseña, de al menos 5 caracteres" id="password" /> 
									<input type="password" value="<?php echo $password2;?>" name="password2" alt="Confirmar Contraseña: " title="Repite la contraseña" id="password2" /> 
									<input type="text" value="<?php echo $correo;?>" name="correo" alt="*Correo electronico: " title="Introduce tu correo electronico" id="correo" /> 
									<input type="text" value="<?php echo $correo2;?>" name="correo2" alt="Confirmar Correo electronico: " title="Repite tu correo electronico" id="correo2" /> 
									<br> </br> <span style="color:red">Consultorio </span>
									<input type="text" value="<?php echo $nombreCons;?>" name="nombreCons" alt="*Nombre del Consultorio:" title="Pon el nombre del Consultorio" id="nombreCons"/>
									<input type="text" value="<?php echo $horarioAper;?>" name="horarioAper" alt="Horario de Apertura:" title="Pon el horario de consulta" id="horarioAper"/>
									<input type="text" value="<?php echo $horarioClau;?>" name="horarioClau" alt="Horario de Clausura:" title="Pon la hora de clausura" id="horarioClau"/>
									<input type="text" value="<?php echo $telefono;?>" name="telefono" alt="Telefono de contacto:" title="Pon un numero de contacto" id="telefono"/>
									<input type="text" value="<?php echo $institucion;?>" name="institucion" alt="Institucion de procedencia:" title="Si el consultorio tiene una institucion de salud escribela" id="telefono"/>
									<br> </br> <span style="color:red">Direccion del consultorio </span>
									<input type="text" value="<?php echo $colonia;?>" name="colonia" alt="*Colonia:" title="Pon la colonia donde se encuentra el consultorio" id="colonia"/>
									<input type="text" value="<?php echo $calle;?>" name="calle" alt="*Calle:" title="Pon la calle donde se encuentra el consultorio" id="calle"/>
									<input type="text" value="<?php echo $numPostal;?>" name="numPostal" alt="*Numero postal:" title="Pon el numero postal donde se encuentra el consultorio" id="numPostal"/>
									<input type="text" value="<?php echo $ciudad;?>" name="ciudad" alt="*Ciudad:" title="Pon la ciudad donde se encuentra el consultorio" id="ciudad"/>
									<input type="text" value="<?php echo $estado;?>" name="estado" alt="*Estado:" title="Pon el estado postal donde se encuentra el consultorio" id="estado"/>
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