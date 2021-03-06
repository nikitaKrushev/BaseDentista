<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 17/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 *
 * Descripcion:
 * 	Registro de dentistas. Primero se validan los datos introducidos por el usuario.Después se verifica
 *  la unicidad del usuario, si es único se registra primero consultorio, después dirección, finalmente
 *  dentista. Se envía un correo de registro al sistema. 
 *
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
			
	$nombre = strtoupper(strip_tags($_POST['nombre']));
	$apaterno = strtoupper(strip_tags($_POST['apaterno']));
	$amaterno = strtoupper(strip_tags($_POST['amaterno']));
	$cedula = strip_tags($_POST['cedula']);
	$usuario = strip_tags($_POST['usuario']);
	$password = strip_tags($_POST['password']);
	$password2 = strip_tags($_POST['password2']);
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);	
	$nombreCons = strtoupper(strip_tags($_POST['nombreCons']));	
	$telefono = strip_tags($_POST['telefono']);		
	$colonia = strtoupper(strip_tags($_POST['colonia']));
	$calle = strtoupper(strip_tags($_POST['calle']));
	$numPostal = strip_tags($_POST['numPostal']);
	$ciudad =strip_tags($_POST['ciudad']);
	$estado =strip_tags($_POST['estado']);
	
	/***
	 * Codigo necesario para mandar correo de confirmacion
	*/
	
	$to = $correo;
	$nameto = $nombre." ".$apaterno;
	$from = "registro@cartillabucaldigital.org";
	$namefrom = "Registro de cuentas";
	$subject = "Registro exitoso de cartilla bucal digital";
	$message =  $nombre." ".$apaterno.".$apaterno. "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!
		\r\n. Tu usuario es: ".$usuario."\r\n Tu contraseña: ".$password.
		"\r\n. Recuerda escribir en algún lugar seguro esta información, para que no se pierdan tus datos
		\r\n. Si tienes dudas o comentarios no dudes en escribir a contacto@cartillabucaldigital.org"; //Pondremos contrasenia y usuario al usuario			
	
	//Para la validacion
	$fail = validaNombre(trim($nombre));
	$fail .= validaPaterno($apaterno,1);
	$fail .= validaPaterno($amaterno,2);
	$fail .= validaCedula($cedula);
	$fail .=validaPass($password);
	$fail .= validaEqualPass($password,$password2);
	$fail .= validaCorreo($correo);
	$fail .= validaEqualCorreo($correo,$correo2);
	
	
	$fail .= validaNombreConsultorio(trim($nombreCons));
	$fail .= validaColonia(trim($colonia),1);
	$fail .= validaColonia(trim($calle),1);
	$fail .= validaConsultorio($numPostal);	
	
	if($fail == "") { //IF A
		//echo "Todo bien validado";
		$query = @mysql_query('SELECT * FROM Dentista WHERE Usuario="'.mysql_real_escape_string($usuario).'"');
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			print '<script type="text/javascript">';
			print 'alert("Usuario existente en la base")';
			print '</script>';
			header("refresh:1;url=regAdminDent.php");
		}else{//ELSE F
			
			$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				print '<script type="text/javascript">';
				print 'alert("Usuario existente en la base")';
				print '</script>';
				header("refresh:1;url=regAdminDent.php");
			}
			else { //ELSE E
				$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					print '<script type="text/javascript">';
					print 'alert("Usuario existente en la base")';
					print '</script>';
					header("refresh:1;url=regAdminDent.php");
				}
				else { //ELSE D
					$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						print '<script type="text/javascript">';
						print 'alert("Usuario existente en la base")';
						print '</script>';
						header("refresh:1;url=regAdminDent.php");
					}
					else { //ELSE C
						$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							print '<script type="text/javascript">';
							print 'alert("Usuario existente en la base")';
							print '</script>';
							header("refresh:1;url=regAdminDent.php");
						}
						else { //ELSE B
													
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								print '<script type="text/javascript">';
								print 'alert("Usuario existente en la base")';
								print '</script>';
								header("refresh:1;url=regAdminDent.php");
							}

							else { //ELSE A						
								//echo "Entro a insertar dentista";															
								
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
										("'.mysql_real_escape_string($colonia).'","'.mysql_real_escape_string($calle).'",'.mysql_real_escape_string($idCiuda->idCiudad).',"'.mysql_real_escape_string($estado)
											.'","'.mysql_real_escape_string($idPais->Pais_Nombre).'","'.mysql_real_escape_string($numPostal).'")');
								
								//Obtengo el identificador de la direccion
								$meter3 = @mysql_query('SELECT * from Direccion where Colonia="'.mysql_real_escape_string($colonia).'"&& Calle ="'.mysql_real_escape_string($calle).'" 
									&& Ciudad_idCiudad="'.mysql_real_escape_string($idCiuda->idCiudad).'" && Ciudad_Estado_Nombre="'.mysql_real_escape_string($estado).'"
										&& Ciudad_Estado_Pais_Nombre="'.mysql_real_escape_string($idPais->Pais_Nombre).'" && NumeroPostal ="'.mysql_real_escape_string($numPostal).'"');
																
								$idDireccion2 = @mysql_fetch_object($meter3);
								
								//Despues Consultorio

								$meter4 = @@mysql_query('INSERT INTO Consultorio (Nombre, Telefono,Direccion_idDireccion) 
											VALUES 	("'.mysql_real_escape_string($nombreCons).'","'.mysql_real_escape_string($telefono).'","'.$idDireccion2->idDireccion.'")');								
								
								//Obtengo el identificador del consultorio
								
								$meter5 = @@mysql_query('SELECT idConsultorio from Consultorio where Nombre="'.mysql_real_escape_string($nombreCons).'" && Direccion_idDireccion = "'.mysql_real_escape_string($idDireccion2->idDireccion).'"');																			
								
								$idConsultorio2 = @mysql_fetch_object($meter5);
								
								//$password_enc = sha1($password);
								$sal_estatica="m@nU3lit0Mart1!n3z";
								$sal_dinamica=mt_rand(); //genera un entero de forma aleatoria
								$password_length = strlen($password);
								$split_at = $password_length / 2;
								$password_array = str_split($password, $split_at);
								$password_enc = sha1($password_array[0] . $sal_estatica . $password_array[1] . $sal_dinamica);
							
								//Finalmente meter en el dentista							
								$meter=@mysql_query('INSERT INTO Dentista (Cedula, Nombre, ApellidoPaterno, ApellidoMaterno, Password, Usuario, CorreoElectronico, 
										Consultorio_idConsultorio,Sasonado) values ("'.mysql_real_escape_string($cedula).' ","'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
											'","'.mysql_real_escape_string($amaterno).'","'.$password_enc.'","'.mysql_real_escape_string($usuario).'","'.mysql_real_escape_string($correo).'","'
														.mysql_real_escape_string($idConsultorio2->idConsultorio).'","'.$sal_dinamica.'")');		

								if($meter){
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									print '<script type="text/javascript">';
									print 'alert("Registro exitoso de Dentista. Revisa tu bandeja de correo")';
									print '</script>';
									header("refresh:1;url=../principales/adminPage.php");
									exit;									
								}
								else {
									$fail .= 'Hubo un error';
									//echo $fail;
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
	if(isset($fail))
		echo $fail;
	/*$nombre = "*Primer Nombre:";
	$apaterno = "*Apellido Paterno:";
	$amaterno = "*Apellido Materno";
	$cedula = "*Cedula Profesional:";
	$usuario = "*Usuario:";
	$password = "*Contraseña:";
	$password2 = "Repite contraseña:";
	$correo = "*Correo electrónico";
	$correo2 = "Repite correo electrónico";
	$nombreCons = "*Nombre del Consultorio:";	
	$telefono = "Telefono de contacto:";	
	$colonia = "*Colonia:";
	$calle = "*Calle:";
	$numPostal = "*Numero postal:";	*/
}


?>
<!DOCTYPE html>
<head>
<title>Principal | Cartilla de Salud Bucal</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="../css/style.css" />


<!-- JavaScript -->
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../js/superfish.js"></script>
<script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="../js/jquery.prettySociable.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script>
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
						<h1>Perfil epidemiológico de caries dental</h1>
						<h2>Página de registro de Dentista</h2>
						<h3>Los campos marcados como * son obligatorios</h3>
						
						<?php 
						if(isset($_POST['posted'])) {

							echo $fail;
						}
						?>
					</div>

					<div id="registra">
						<ul>
							<li>						
									
								<form class="form-horizontal" action="regAdminDent.php" method="post">
								
								<fieldset>

									<legend>Datos personales</legend>
									
								</fieldset>
								
									<div class="control-group">
										<label class="control-label" for="nombre">(*) Nombre:</label>
								  		<div class="controls">
											<input type="text" autofocus class="pull-left input-xlarge" data-trigger="hover" required value="<?php echo $nombre;?>" name="nombre" title="Introduce tu primer nombre" id="nombre" /> 								
										</div>									
									</div>

									<div class="control-group">
										<label class="control-label" for="apaterno">(*) Apellido Paterno:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $apaterno;?>" name="apaterno" title="Introduce tu apellido paterno" id="apaterno" /> 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="apaterno">(*) Apellido Materno:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $amaterno;?>" name="amaterno" title="Introduce tu apellido materno" id="amaterno" /> 
									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="cedula">(*)Cédula:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $cedula;?>" name="cedula" title="Introduce tu cedula profesional" id="cedula" /> 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="usuario">(*)Usuario:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $usuario;?>" name="usuario" title="Introduce un usuario" id="usuario" />		 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="password">(*)Contraseña:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="password" value="<?php echo $password;?>" name="password" title="Introduce tu contraseña, de al menos 5 caracteres" id="password" /> 		 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="password2">(*)Confirmar Contraseña:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="password" value="<?php echo $password2;?>" name="password2" title="Repite la contraseña" id="password2" /> 									 		 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="correo">(*)Correo electrónico:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required  type="email" value="<?php echo $correo;?>" name="correo" title="Introduce tu correo electronico" id="correo" /> 									 									 		 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="correo2">(*)Confirmar Correo electrónico:</label>
								  		<div class="controls">
											 <input class="pull-left input-xlarge" data-trigger="hover" required type="email" value="<?php echo $correo2;?>" name="correo2" title="Repite tu correo electronico" id="correo2" />								 									 		 									 									 								 								
										</div>									
									</div>	
																																				 
																		
									<legend>Datos del consultorio</legend>
									 
									<div class="control-group">
										<label class="control-label" for="nombreCons">(*)Nombre del Consultorio:</label>
								  		<div class="controls">
											 <input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $nombreCons;?>" name="nombreCons" title="Pon el nombre del Consultorio" id="nombreCons"/>								 									 		 									 									 								 								
										</div>									
									</div>									
									
									<div class="control-group">
										<label class="control-label" for="telefono">Telefono de contacto:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="tel" value="<?php echo $telefono;?>" name="telefono" title="Pon un numero de contacto" id="telefono"/>								 									 		 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="colonia">(*)Colonia:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $colonia;?>" name="colonia" title="Pon la colonia donde se encuentra el consultorio" id="colonia"/>								 									 		 									 									 								 								
										</div>									
									</div>
								
									<div class="control-group">
										<label class="control-label" for="calle">(*)Calle:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $calle;?>" name="calle" title="Pon la calle donde se encuentra el consultorio" id="calle"/>																	 									 		 									 									 								 								
										</div>									
									</div>						
									
									<div class="control-group">
										<label class="control-label" for="numPostal">(*)Numero postal:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $numPostal;?>" name="numPostal" title="Pon el numero postal donde se encuentra el consultorio" id="numPostal"/>																										 									 		 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="numPostal">(*)Ciudad:</label>
								  		<div class="controls">
												<select class="pull-left" name="ciudad">
									<?php
										if(isset($size)) {
										for($i=0; $i<$size; $i++) {
									?>
										<option value="<?php echo $escuelas[$i]->Nombre;?>" ><?php echo $escuelas[$i]->Nombre;?> </option>
									<?php }} 
									?>										
									</select>																										 									 		 									 									 								 								
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="numPostal">(*)Numero postal:</label>
								  		<div class="controls">
											<select class="pull-left" name="estado">									
									<?php
									 
										if(isset($size2)) {
										for($i=0; $i<$size2; $i++) {
									?>
										<option value="<?php echo $estados[$i]->Nombre;?>" ><?php echo $estados[$i]->Nombre;?> </option>
									<?php }} 
									?>										
									</select>									
																																			 									 		 									 									 								 								
										</div>									
									</div>										
									
									<div class="control-group">
										
								  		<div class="controls">
											<input class="pull-left" type="submit" value="Registrar" />																	 									 		 									 									 								 								
										</div>									
									</div>
									
																																																																																		
									 
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
<script type="text/javascript">
$(function () {
	$('#nombre').popover({
		title: 'Test',
		content: 'El nombre solo contiene letras',
		placement: 'right'
	});
	$('#usuario').popover({
		title: 'Test',
		content: '',
		placement: 'right'
	});
	$('#apaterno').popover({
		title: 'Test',
		content: 'Los apellidos llevan solo letras',
		placement: 'right'
	});
	$('#amaterno').popover({
		title: 'Test',
		content: 'Los apellidos llevan solo letras',
		placement: 'right'
	});
	$('#cedula').popover({
		title: 'Test',
		content: 'Formato alfanumérico',
		placement: 'right'
	});
		$('#password').popover({
		title: 'Test',
		content: 'Las contraseñas deben concordar',
		placement: 'right'
	});
	$('#pass2').popover({
		title: 'Test',
		content: 'Las contraseñas deben concordar',
		placement: 'right'
	});
	$('#correo').popover({
		title: 'Test',
		content: 'Los correos deben ser los mismos',
		placement: 'right'
	});
	$('#correo2').popover({
		title: 'Test',
		content: 'Los correos deben ser los mismos',
		placement: 'right'
	});
	$('#nombreCons').popover({
		title: 'Test',
		content: 'El nombre del consultorio no contiene acentos',
		placement: 'right'
	});
	$('#telefono').popover({
		title: 'Test',
		content: 'Solo se aceptan números',
		placement: 'right'
	});
	$('#numPostal').popover({
		title: 'Test',
		content: 'Solo se aceptan números',
		placement: 'right'
	});
});
</script>
</html>