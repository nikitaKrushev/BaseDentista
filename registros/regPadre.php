<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 17/FEB/2013
 *
 * Descripcion:
 * 	Registro de los padres. Primero se verifica que la informaci�n introducida sea
 * 	correctamente formateada. Despu�s se verifica la unicidad del usuario, en caso de 
 *  repetici�n, el registro no puede ser completado, de otra forma, se da de alta al 
 *  usuario y se envia un correo a la direcci�n que fue proporcionada.
 *  
 * Variables:
 * 		$fail: Para la validacion, originalmente es una cadena vacia
 * 		$sal_estatica: La parte estatica de la encriptacion.
 * 		$sal_dinamica: La parte dinamica de la encriptacion, generada con la funcion mt
 * 		$pass_enc: La contraseña ya encriptada despues del algoritmo.
 * 		$message: El mensaje que se envia a la bandeja de correo.
 */

if(isset($_POST['posted'])) {

	require_once('../funciones.php');
	include '../validaciones.php';
	include '../enviarMail.php';
	
	conectar($servidor, $user, $pass, $name);
	
	//recibe info
	$usuario = strip_tags($_POST['usuario']);
	$password = strip_tags($_POST['password']);
	$password2 = strip_tags($_POST['password2']);
	$name = strtoupper(strip_tags($_POST['name']));
	$apellidoPat = strtoupper(strip_tags($_POST['apellidoP']));
	$apellidoMat = strtoupper(strip_tags($_POST['apellidoM']));
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);
	$telefono = strip_tags($_POST['telefono']);
	
	$to = $correo;
	$nameto = $name." ".$apellidoPat." ".$apellidoMat;
	$from = "registro@cartillabucaldigital.org";
	$namefrom = "Registro de cuentas";
	$subject = "Registro exitoso de cartilla bucal digital";
	$message =  $name." ".$apellidoPat.".$apellidoMat. "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!
		\r\n. Tu usuario es: ".$usuario."\r\n Tu contraseña: ".$password.
		"\r\n. Recuerda escribir en algún lugar seguro esta información, para que no se pierdan tus datos
		\r\n. Si tienes dudas o comentarios no dudes en escribir a contacto@cartillabucaldigital.org"; //Pondremos contrasenia y usuario al usuario			
			
	
	//Validacion
	$fail = validateUser($usuario);
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
			//echo $fail;
			print '<script type="text/javascript">';
			print 'alert("Usuario existente en la base")';
			print '</script>';
			header("refresh:3;url=regPadre.php");
		}else{
			$query = @mysql_query('SELECT * FROM Dentista WHERE Usuario="'.mysql_real_escape_string($usuario).'"');
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				print '<script type="text/javascript">';
				print 'alert("Usuario existente en la base")';
				print '</script>';
				header("refresh:3;url=regPadre.php");
			}else {
				$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					//echo $fail;
					print '<script type="text/javascript">';
					print 'alert("Usuario existente en la base")';
					print '</script>';
					header("refresh:1;url=regPadre.php");
				}
				else {
					$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						//echo $fail;
						print '<script type="text/javascript">';
						print 'alert("Usuario existente en la base")';
						print '</script>';
						header("refresh:3;url=regPadre.php");
					}
					else {
						$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							//echo $fail;
							print '<script type="text/javascript">';
							print 'alert("Usuario existente en la base")';
							print '</script>';
							header("refresh:3;url=regPadre.php");
						}
						else {
							$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								//echo $fail;
								print '<script type="text/javascript">';
								print 'alert("Usuario existente en la base")';
								print '</script>';
								header("refresh:3;url=regPadre.php");
							}
							else {
								$sal_estatica="m@nU3lit0Mart1!n3z";
								$sal_dinamica=mt_rand(); //genera un entero de forma aleatoria
								$password_length = strlen($password);
								$split_at = $password_length / 2;
								$password_array = str_split($password, $split_at);
								$pass_enc = sha1($password_array[0] . $sal_estatica . $password_array[1] . $sal_dinamica);
								 						
								$meter=@mysql_query('INSERT INTO Padre(Nombre,ApellidoPaterno,ApellidoMaterno,Usuario,Password,Telefono,Correo,Sasonado)  
										values ("'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($apellidoPat).'","'.mysql_real_escape_string($apellidoMat).
											'","'.mysql_real_escape_string($usuario).'","'.mysql_real_escape_string($pass_enc).'","'
												.mysql_real_escape_string($telefono).'","'.mysql_real_escape_string($correo).'","'.$sal_dinamica.'")');
								
								if($meter){									
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									print '<script type="text/javascript">';
									print 'alert("Registro exitoso de usuario. Revisa tu bandeja de correo")';
									print '</script>';									
									header("refresh:1;url=../index.php");	
									exit;
									
								}else{
									$fail .= 'Hubo un error al enviar el correo electronico';
									print '<script type="text/javascript">';
									print 'alert("Error al enviar el correo electronico")';
									print '</script>';
									//echo $fail;
									
								}
								
							}
								
						}
					}
				}
			}												
		}
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
    <script type="text/javascript" src="../js/dialogos.js"></script>
    
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
                        <p>Página de registro de Padres</p>
                        <?php 
                        if(isset($_POST['posted'])) {
                     	
                        	echo $fail;
                        }
                        ?>
                    </div>
                    
                    <div id="registra"	>
                    <ul>
                        <li>							
							<form action="regPadre.php" method="post" >
								<input type="text"  value="<?php echo $usuario;?>" name="usuario" alt="Usuario:" title="Escribe su usuario" id="usuario" />																				
								<input type="text" value="<?php echo $name;?>" name="name" alt="Nombre:" title="Escriba su nombre" id="name"  />									
								<input type="text" value="<?php echo $apellidoPat;?>" name="apellidoP" alt="Apellido Paterno: " title="Escriba su apellido paterno" id="apellidoP" />									
								<input type="text" value="<?php echo $apellidoMat;?>" name="apellidoM" alt="Apellido Materno:" title="Escriba su apellido materno" id="apellidoM" />
								<input type="text" value="<?php echo $telefono;?>" name="telefono" alt="Telefono:" title="Escriba su telefono de contacto" id="telefono" />										
								<input type="password" value="<?php echo $password;?>" name="password" alt="Contraseña: " title="Escriba su contrasenia" id="password" />								
								<input type="password" value="<?php echo $password2;?>" name="password2" alt="Confirmar Contraseña: " title="Repita contrasenia" id="password2" />						
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
            <a href="../index.php" id="logo">Foundation</a>
            
            <!-- Main Naigation (active - .act) -->
            <div id="main-nav">
                 <ul>
                    <li class="act"><a href="../index.php">Inicio</a></li>
                    <li> <a href="../ProfesionalSaludPrincipal.php">Profesional de Salud</a> </li>
                    <li> <a href="../padrePrincipal.php">Padres de familia</a> </li>
                    <li> <a href="../escuelaPrincipal.php">Escuelas</a></li>   
                    <li> <a href="../contacto.php">Contacto</a></li>  
                    <li> <a href="../creditos.php">Créditos</a></li>                   
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
                <div id="copyright"> </div>
            </div>
            
            <!-- Footer Widgets -->
            <div id="f-main-col">
                <!-- Contact Info -->
                <div class="widget w-50 w-text last" id="text-1">
                    <div class="w-content">
                        <a href="#"><img src="../img/pictures/zamudio.png" alt="Our Building" class="alignright" /></a>                      
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
