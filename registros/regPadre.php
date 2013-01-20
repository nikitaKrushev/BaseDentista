<?php
if(isset($_POST['posted'])) {

	require_once('../funciones.php');
	conectar($servidor, $user, $pass, $name);
	
	//recibe info
	$usuario = strip_tags($_POST['usuario']);
	$password = strip_tags($_POST['password']);
	$password2 = strip_tags($_POST['password2']);
	$nombre = strip_tags($_POST['name']);
	$apellidoPat = strip_tags($_POST['apellidoP']);
	$apellidoMat = strip_tags($_POST['apellidoM']);
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);
	$telefono = strip_tags($_POST['telefono']);
	
	$to = $correo;
	$nameto = $nombre." ".$apaterno;
	$from = "registro@cartillabucaldigital.org";
	$namefrom = "Registro de cuentas";
	$subject = "Registro exitoso de cartilla bucal digital";
	$message =  $nombre." ".$apellidoPat.".$apellidoMat. "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!
			\r\n Tu usuario es: ".$usuario."\r\n  Tu contrase�a: ".$password.
			"\r\n Recuerda escribir en alg�n lugar seguro esta informaci�n, para que no se pierdan tus datos
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
	$fail .= validaTelefono($telefono);
	
	echo "<html><head><title>Registro Padre</title>";
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
									echo 'Usuario registrado con exito';
									//$sendmail = mail($mail_to,$mail_subject,$mail_body,$mail_header);
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									echo 'Mail sent!';
									//if($sendmail == true)
									//	echo 'Mail sent!';
									//else
									//	echo 'Mail not sent';
										
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

function validaTelefono($telefono) {
	if (! preg_match("/^[0-9]+$/",$telefono))
		return "El telefono requiere solo digitos.\n";
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
							
								function validateTelefono(field) {
									if (! /^[0-9]+$/.test(field))
										return "El campo Consultorio requiere digitos.\n";					
									return "";
								}
							</script>                        	
							
							<form action="regPadre.php" method="post" onsubmit="return validate(this)">
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
            <a href="index.html" id="logo">Foundation</a>
            
            <!-- Main Naigation (active - .act) -->
            <div id="main-nav">
                <ul>
                    <li class="act"><a href="index.html">Inicio</a></li>
                    <li>
							<a href="contruccion.html">Consultorio</a>                     
                    </li>
                    <li>
                        <a href="construccion.html">Dentista	</a>
                    </li>
                    <li>
                        <a href="construccion.html">Escuela</a>      
                    </li>
                    <li>
                        <a href="construccion.html">Paciente</a>      
                    </li>                 
                    
                    <li>
                        <a href="construccion.html">Solicitudes pendientes</a>      
                    </li>
                    
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
                <div id="sidebar-end">
                    <form action="#" id="subscribe">
                        <input type="text" value="" alt="Recibe las �ltimas noticias!" title="Escribe tu correo" />
                        <input type="submit" value="" title="Subscribe" />
                    </form>
                </div>
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
                        Tijuana, B.C., M�xico<br />
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
