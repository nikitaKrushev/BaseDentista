<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 *
 * Descripcion:
 * 	Registra un director en la base de datos, similar al registro de
 *  padres.
 *
 */

/**
 * Variables:
 * 		$fail: Para la validacion, originalmente es una cadena vacia
 * 		$sal_estatica: La parte estatica de la encriptacion.
 * 		$sal_dinamica: La parte dinamica de la encriptacion, generada con la funcion mt
 * 		$password_enc: La contraseña ya encriptada despues del algoritmo.
 */

include '../accesoDentista.php';
include '../validaciones.php';
include '../enviarMail.php';

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
	$message =  $nombre." ".$apaterno.".$apaterno. "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!
		\r\n. Tu usuario es: ".$user."\r\n Tu contraseña: ".$password.
		"\r\n. Recuerda escribir en algún lugar seguro esta información, para que no se pierdan tus datos
		\r\n. Si tienes dudas o comentarios no dudes en escribir a contacto@cartillabucaldigital.org"; //Pondremos contrasenia y usuario al usuario			
	
			
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
	
	if($fail == "") { //IF A
		$query = @mysql_query('SELECT * FROM Dentista WHERE Usuario="'.mysql_real_escape_string($usuario).'"');
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			print '<script type="text/javascript">';
			print 'alert("Usuario existente en la base")';
			print '</script>';
			header("refresh:1;url=regDirector.php");
		}else{//ELSE F
				
			$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				print '<script type="text/javascript">';
				print 'alert("Usuario existente en la base")';
				print '</script>';
				header("refresh:1;url=regDirector.php");
			}
			else { //ELSE E
				$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					print '<script type="text/javascript">';
					print 'alert("Usuario existente en la base")';
					print '</script>';
					header("refresh:1;url=regDirector.php");
				}
				else { //ELSE D
					$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						header("refresh:1;url=regDirector.php");
						print '<script type="text/javascript">';
						print 'alert("Usuario existente en la base")';
						print '</script>';
					}
					else { //ELSE C
						$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							print '<script type="text/javascript">';
							print 'alert("Usuario existente en la base")';
							print '</script>';
							header("refresh:1;url=regDirector.php");
						}
						else { //ELSE B
								
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								print '<script type="text/javascript">';
								print 'alert("Usuario existente en la base")';
								print '</script>';
								header("refresh:1;url=regDirector.php");
							}

							else { //ELSE A
								
								//Primero obtenemos el identificador del pais
								$cadena = "select * from Estado where Nombre='".mysql_real_escape_string($estado)."'";
								$meter1 = @mysql_query($cadena);
								$idPais = @mysql_fetch_object($meter1);
								
								//Despues el id de la ciudad
								$cadena_ciudad = "select * from Ciudad where Nombre='".mysql_real_escape_string($ciudad)."' && Estado_Nombre='".mysql_real_escape_string($estado)."'";
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
								//$password_enc = sha1($password);
								$sal_estatica="m@nU3lit0Mart1!n3z";
								$sal_dinamica=mt_rand(); //genera un entero de forma aleatoria
								$password_length = strlen($password);
								$split_at = $password_length / 2;
								$password_array = str_split($password, $split_at);
								$password_enc = sha1($password_array[0] . $sal_estatica . $password_array[1] . $sal_dinamica);
								
								$meter=@mysql_query('INSERT INTO Director (idDirector,Nombre,Password,ApellidoPaterno,Escuela_idEscuela,Escuela_Direccion_idDireccion,Correo,Sasonado) 
									values ("'.mysql_real_escape_string($usuario).' ","'.mysql_real_escape_string($nombre).'", "'.$password_enc.
										'","'.mysql_real_escape_string($apaterno).'","'.mysql_real_escape_string($idnEscuela).'","'.$idDireccion2->idDireccion.
											'","'.mysql_real_escape_string($correo).'","'.$sal_dinamica.'")');
									
								if($meter){
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									print '<script type="text/javascript">';
									print 'alert("Registro exitoso de Director. Revisa tu bandeja de correo")';
									print '</script>';
									header("refresh:1;url=../principales/adminPage.php");
									exit;
								}
								else {
									$fail .= 'Hubo un error en el registro del usuario';
									print '<script type="text/javascript">';
									print 'alert("Hubo un error en el registro")';
									print '</script>';		
								}
							} //ELSE A
						} //ELSE B
					}	//ELSE C
				} //ELSE D
			}//ELSE E
		}//ELSE F
	} //IF A
	else {
		print '<script type="text/javascript">';
		print 'alert("Hubo un error en el registro")';
		print '</script>';
	}
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
								<span style="color:red">Datos del director de la escuela </span>
									
								<form action="regDirector.php" method="post" >
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
										if(isset($size)) {
										for($i=0; $i<$size; $i++) {
									?>
										<option value="<?php echo $escuelas[$i]->Nombre;?>" ><?php echo $escuelas[$i]->Nombre;?> </option>
									<?php }} 
									?>										
									</select>
									<select name="estado">									
									<?php
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
                    <li> <a href="../registros/regAdminDent.php">Registro de Dentistas</a> </li>
                    <li> <a href="../registros/regDirector.php">Registro de Directores</a> </li>
                    <li> <a href="../registros/regNinio.php">Registro de Pacientes</a> </li>                                     
                    <li> <a href="../construccion.html">Solicitudes pendientes</a> </li>				
				</ul>
			</div>

			<!-- News Widget -->
			<div class="widget w-news">
				<h4 class="w-title title-light">Cerrar sesi&oacute;n.</h4>
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
				<div id="copyright"> </div>
			</div>

			<!-- Footer Widgets -->
			<div id="f-main-col">
				<!-- Contact Info -->
				<div class="widget w-50 w-text last" id="text-1">
					<div class="w-content">
						<img src="../img/pictures/zamudio.png" class="alignright" /> 
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