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
require_once('../funciones.php');
include '../validaciones.php';
include '../enviarMail.php';

conectar($servidor, $user, $pass, $name);

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

//Creacion de las variables de sesion para los campos
if(!isset($_SESSION['campos'])) {
	$_SESSION['campos']['nombre']='';
	$_SESSION['campos']['apat']='';
	$_SESSION['campos']['amat']='';
	$_SESSION['campos']['ced']='';
	$_SESSION['campos']['use']='';
	$_SESSION['campos']['pass']='';
	$_SESSION['campos']['corr']='';
	$_SESSION['campos']['corr2']='';
	$_SESSION['campos']['consNom']='';
	$_SESSION['campos']['tel']='';
	$_SESSION['campos']['col']='';
	$_SESSION['campos']['calle']='';
	$_SESSION['campos']['numPostal']='';
}
if(!isset($_SESSION['error'])) {
	$_SESSION['error']['nombre']='hidden';
	$_SESSION['error']['apat']='hidden';
	$_SESSION['error']['amat']='hidden';
	$_SESSION['error']['ced']='hidden';
	$_SESSION['error']['use']='hidden';
	$_SESSION['error']['pass']='hidden';
	$_SESSION['error']['corr']='hidden';
	$_SESSION['error']['corr2']='hidden';
	$_SESSION['error']['consNom']='hidden';
	$_SESSION['error']['tel']='hidden';
	$_SESSION['error']['col']='hidden';
	$_SESSION['error']['calle']='hidden';
	$_SESSION['error']['numPostal']='hidden';
}
//Ahora los errores

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
		\r\n. Tu usuario es: ".$usuario."\r\n tTu contraseña: ".$password.
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
			header("refresh:1;url=regDentistaTemporal.php");
		}else{//ELSE F
			
			$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				print '<script type="text/javascript">';
				print 'alert("Usuario existente en la base")';
				print '</script>';
				header("refresh:1;url=regDentistaTemporal.php");
			}
			else { //ELSE E
				$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					print '<script type="text/javascript">';
					print 'alert("Usuario existente en la base")';
					print '</script>';
					header("refresh:1;url=regDentistaTemporal.php");
				}
				else { //ELSE D
					$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						print '<script type="text/javascript">';
						print 'alert("Usuario existente en la base")';
						print '</script>';
						header("refresh:1;url=regDentistaTemporal.php");
					}
					else { //ELSE C
						$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							print '<script type="text/javascript">';
							print 'alert("Usuario existente en la base")';
							print '</script>';
							header("refresh:1;url=regDentistaTemporal.php");
						}
						else { //ELSE B
													
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								print '<script type="text/javascript">';
								print 'alert("Usuario existente en la base")';
								print '</script>';
								header("refresh:1;url=regDentistaTemporal.php");
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

								$meter4 = @@mysql_query('INSERT INTO Consultorio (Nombre,Telefono,Direccion_idDireccion) 
											VALUES 	("'.mysql_real_escape_string($nombreCons).'","'.mysql_real_escape_string($telefono).'","'.$idDireccion2->idDireccion.'")');								
								
								//Obtengo el identificador del consultorio
								
								$meter5 = @@mysql_query('SELECT idConsultorio from Consultorio where Nombre="'.mysql_real_escape_string($nombreCons).'" && Direccion_idDireccion = "'.mysql_real_escape_string($idDireccion2->idDireccion).'"');																			
								
								$idConsultorio2 = @mysql_fetch_object($meter5);

								//Finalmente meter en el dentista
								
								//$password_enc = sha1($password);
								$sal_estatica="m@nU3lit0Mart1!n3z";
								$sal_dinamica=mt_rand(); //genera un entero de forma aleatoria
								$password_length = strlen($password);
								$split_at = $password_length / 2;
								$password_array = str_split($password, $split_at);
								$password_enc = sha1($password_array[0] . $sal_estatica . $password_array[1] . $sal_dinamica);
								
								$meter=@mysql_query('INSERT INTO Dentista (Cedula, Nombre, ApellidoPaterno, ApellidoMaterno, Password, Usuario, CorreoElectronico,
										Consultorio_idConsultorio,Sasonado) values ("'.mysql_real_escape_string($cedula).' ","'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
										'","'.mysql_real_escape_string($amaterno).'","'.$password_enc.'","'.mysql_real_escape_string($usuario).'","'.mysql_real_escape_string($correo).'","'
										.mysql_real_escape_string($idConsultorio2->idConsultorio).'","'.$sal_dinamica.'")');

								if($meter){
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									print '<script type="text/javascript">';
									print 'alert("Registro exitoso de Dentista. Revisa tu bandeja de correo")';
									print '</script>';
									header("refresh:1;url=../index.php");	
									unset($_SESSION['campos']);
									unset($_SESSION['error']);
									exit;								
								}
								else {
									$fail .= 'Hubo un error';
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
	/*
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
	$telefono = "Telefono de contacto:";	
	$colonia = "*Colonia:";
	$calle = "*Calle:";
	$numPostal = "*Numero postal:";*/
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Principal | Cartilla de Salud Bucal</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<!-- Styles -->
<link rel="stylesheet" type="text/css" href="../css/style2.css" />

<!-- JavaScript -->
<script type="text/javascript" src="../js/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="../js/superfish.js"></script>
<script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="../js/jquery.prettySociable.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/validacionCampos.js"></script>

</head>

<body id="home" onload="setFocus();">
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

					<div id="registra" align="left" >						
							<fieldset>																
								<form action="regDentistaTemporal.php" method="post">
                                	<label for="nombre" >* Nombre(s): </label>
									<input type="text" value="<?php echo $_SESSION['campos']['nombre'];?>" name="nombre" title="Introduce tu primer nombre" id="nombre" onblur="validate(this.value,this.id)" />
									<span id="nombreFail" class="<?php echo $_SESSION['error']['nombre'];?>" >Nombre solo con letras, sin acentos o ñ. Longitud máxima 30 caracteres. </span>
									<br/>
									
									<label for="apaterno">* Apellido Paterno:</label> 
									<input type="text" value="<?php echo $_SESSION['campos']['apat'];?>" name="apaterno" title="Introduce tu apellido paterno" id="apaterno" onblur="validate(this.value,this.id)" />
									<span id="apaternoFail" class="<?php echo $_SESSION['error']['apat'];?>" >Apellido paterno solo con letras, sin acentos o ñ. Longitud máxima 30 caracteres. </span>									
									<br/>
									
									<label for="amaterno">* Apellido Materno:</label>
									<input type="text" value="<?php echo $_SESSION['campos']['amat'];?>" name="amaterno" title="Introduce tu apellido materno" id="amaterno" onblur="validate(this.value,this.id)" />
									<span id="amaternoFail" class="<?php echo $_SESSION['error']['amat'];?>" >Apellido materno solo con letras, sin acentos o ñ. Longitud máxima 30 caracteres. </span>																		
									<br/>
									
									<label for="cedula">* C&eacute;dula:</label>									 
									<input type="text" value="<?php echo $_SESSION['campos']['ced'];?>" name="cedula" title="Introduce tu cedula profesional" id="cedula" onblur="validate(this.value,this.id)" />
									<span id="cedulaFail" class="<?php echo $_SESSION['error']['ced'];?>" >Favor de llenar el campo cedula. </span>
									<br/>
									
									<label for="usuario">* Usuario:</label>									
									<input type="text" value="<?php echo $_SESSION['campos']['use'];?>" name="usuario" title="Introduce un usuario" id="usuario" onblur="validate(this.value,this.id)" />
									<span id="usuarioFail" class="<?php echo $_SESSION['error']['use'];?>" >Usuario existente. Longitud máxima 20 caracteres </span>									
									<br/>
									
									<label for="password">* Contrase&ntilde;a:</label>									
									<input type="password" value="<?php echo $_SESSION['campos']['pass'];?>" name="password" title="Introduce tu contraseña, de al menos 5 caracteres" id="password" onblur="validate(this.value,this.id)"/>
									<span id="passwordFail" class="<?php echo $_SESSION['error']['pass'];?>" >El tamaño de la contraseña debe ser por lo menos de 5 caracteres.Requiere al menos una letra </span>																		
									<br/>
									
									<label for="password2">Repite tu Contrase&ntilde;a:</label>
									<input type="password" value="<?php echo $_SESSION['campos']['pass'];?>" name="password2" title="Repite la contraseña" id="password2"  />
									<span id="password2Fail" class="<?php echo $_SESSION['error']['pass'];?>" >Las contrase&ntildte;as no son iguales. </span>
									<br/>
									
									<label for="correo"> Correo electr&oacute;nico:</label> 
									<input type="text" value="<?php echo $_SESSION['campos']['corr'];?>" name="correo" title="Introduce tu correo electronico" id="correo" onblur="validate(this.value,this.id)" />
									<span id="correoFail" class="<?php echo $_SESSION['error']['corr'];?>" >La dirección de correo electrónico es inválida. </span>
									<br/>
									
									<label for="correo2"> Repite tu correo electr&oacute;nico:</label> 									
									<input type="text" value="<?php echo $_SESSION['campos']['corr2'];?>" name="correo2" title="Repite tu correo electronico" id="correo2" />
									<span id="correo2Fail" class="<?php echo $_SESSION['error']['corr2'];?>" >Los correos no son iguales. </span>
									<br/> 
									
									<label for="nombreCons">* Nombre de tu consultorio:</label> 																		
									<input type="text" value="<?php echo $_SESSION['campos']['consNom'];?>" name="nombreCons" title="Pon el nombre del Consultorio" id="nombreCons" onblur="validate(this.value,this.id)"/>
									<span id="nombreConsFail" class="<?php echo $_SESSION['error']['consNom'];?>" >Favor de llenar el nombre del consultorio. Longitud máxima 30 caracteres. </span>
									<br/>
									
									<label for="telefono">* Tel&eacute;fono de tu consultorio:</label> 																											
									<input type="text" value="<?php echo $_SESSION['campos']['tel'];?>" name="telefono" title="Pon un numero de contacto" id="telefono" onblur="validate(this.value,this.id)"/>
									<span id="telefonoFail" class="<?php echo $_SESSION['error']['tel'];?>" >El telefono requiere solo dígitos. </span>
									<br/>
									
									<label for="colonia">* Colonia de tu consultorio:</label>
									<input type="text" value="<?php echo $_SESSION['campos']['col'];?>" name="colonia" title="Pon la colonia donde se encuentra el consultorio" id="colonia" onblur="validate(this.value,this.id)"/>
									<span id="coloniaFail" class="<?php echo $_SESSION['error']['col'];?>" >Favor de llenar el campo colonia. Longitud máxima 30 caracteres. </span>
									<br/>
									
									<label for="calle">* Calle de tu consultorio:</label>
									<input type="text" value="<?php echo $_SESSION['campos']['calle'];?>" name="calle" title="Pon la calle donde se encuentra el consultorio" id="calle" onblur="validate(this.value,this.id)"/>
									<span id="calleFail" class="<?php echo $_SESSION['error']['calle'];?>" >Favor de llenar el campo calle. Longitud máxima 30 caracteres.</span>
									<br/>
									
									<label for="numpostal">* N&uacute;mero postal:</label>
									<input type="text" value="<?php echo $_SESSION['campos']['numPostal'];?>" name="numPostal" title="Pon el numero postal donde se encuentra el consultorio" id="numPostal" onblur="validate(this.value,this.id)"/>
									<span id="numpostalFail" class="<?php echo $_SESSION['error']['numPostal'];?>" >El numero postal requiere digitos. </span>
									<br/>
									
									<label for="ciudad">* Ciudad:</label>
									<select name="ciudad">
									<?php
									echo $size;
										if(isset($size)) {
										for($i=0; $i<$size; $i++) {
									?>
										<option value="<?php echo $escuelas[$i]->Nombre;?>" ><?php echo $escuelas[$i]->Nombre;?> </option>
									<?php }} 
									?>										
									</select>
									<br/>
									
									<label for="estado">* Estado(Provincia):</label>
									<select name="estado">									
									<?php
									 
									echo $size2;
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
						</fieldset>
						
					</div>
				</div>
				<!-- Homepage Teasers End -->

			</div>
		</div>
		<!-- Main Column End -->

		<!-- Left Column Start -->
		<div id="left-col">

			<!-- Logo -->
			<a href="../index.php" id="logo">Foundation</a>

			<!-- Main Naigation (active - .act) -->
			<div id="main-nav">
				<ul>
                    <li class="act"><a href="../index.php">Inicio</a></li>
                    <li> <a href="../ProfesionalSaludPrincipal.php">Profesional de Salud</a> </li>
                    <li> <a href="../padrePrincipal.php">Padres de familia</a> </li>
                    <li> <a href="../escuelaPrincipal.php">Escuelas</a> </li>          
				</ul>
			</div>

			<!-- News Widget -->
			<div class="widget w-news">
				<h4 class="w-title title-light"></h4>
				<div class="w-content">
					<ul>
						<li>
							
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