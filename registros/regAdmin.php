<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 17/FEB/2013
 *
 * Descripcion:
 * 	Se registra a los administradores semejante al registro de padres.
 * 
 * 	Variables
 * 		$fail: Para la validacion, originalmente es una cadena vacia
 * 		$sal_estatica: La parte estatica de la encriptacion.
 * 		$sal_dinamica: La parte dinamica de la encriptacion, generada con la funcion mt
 * 		$pass_enc: La contraseña ya encriptada despues del algoritmo.
 * 		$message: El mensaje que se envia a la bandeja de correo.
 */

session_start();
include '../accesoDentista.php';
include '../validaciones.php';
include '../enviarMail.php';

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
								//$pass_enc = sha1($pass);
								
								//Creacion de password
								$sal_estatica="m@nU3lit0Mart1!n3z";
								$sal_dinamica=mt_rand(); //genera un entero de forma aleatoria
								$password_length = strlen($pass);
								$split_at = $password_length / 2;
								$password_array = str_split($pass, $split_at);
								$pass_enc = sha1($password_array[0] . $sal_estatica . $password_array[1] . $sal_dinamica);
								
								$meter=@mysql_query('INSERT INTO Administrador (Usuario, Nombre,ApellidoPaterno,ApellidoMaterno, Password,
										ProfesionalSalud_Usuario, Correo, Sasonado) values ("'.mysql_real_escape_string($user).'","'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($apaterno).'","'.mysql_real_escape_string($amaterno).
										'","'.mysql_real_escape_string($pass_enc).'","'.$_SESSION['uid'].'","'.mysql_real_escape_string($correo).'","'.$sal_dinamica.' ")');
								
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
	$pass = "Contraseña:";
	$pass2 = "Repite Contraseña:";
	$name = "Nombre:";
	$apaterno = "Apellido Paterno:";
	$amaterno = "Apellido Materno:";
	$correo = "Correo Electronico:";
	$correo2 = "Repite Correo Electronico:";	
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
                        <p>Perfil epidemiológico de caries dental</p>
                        <p>Página de registro de Administradores</p>
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
								<input type="password" value="<?php echo $pass;?>" name="password" alt="Contraseña:" title="Introduce tu contraseña, de al menos 5 caracteres" id="password" /> 
								<input type="password" value="<?php echo $pass2;?>" name="pass2" alt="Confirmar Contraseña: " title="Repite la contraseña" id="pass2" /> 
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
                    <li> <a href="../registros/regAdmin.php">Registrar administrador de sitio</a> </li>
                    <li> <a href="../registros/regPais.php">Registrar Pais</a> </li>                    
                    <li> <a href="../registros/regEstado.php">Registrar Estado</a> </li>                    
                    <li> <a href="../registros/regCiudad.php">Registrar Ciudad</a> </li>                    
                    <li> <a href="../construccion.html">Consultar directorio de consultorios</a> </li>
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
                        <img src="img/pictures/zamudio.png" class="alignright" />
                       
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