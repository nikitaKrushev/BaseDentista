<?php
session_start();
include '../accesoDentista.php';
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
	$name = strip_tags($_POST['name']);
	$apaterno = strip_tags($_POST['apaterno']);
	$amaterno = strip_tags($_POST['amaterno']);
	$pass = strip_tags($_POST['password']);
	$pass2 = strip_tags($_POST['pass2']);
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);
	
	//Mail Diferente
	$nombre = "Code Assist"; //Nombre del remitente
	$mail_from = "cartillasaludbucal@gmail.com"; //Correo del remitente
	$mail_to = $correo; //Correo receptor
	$mail_body = $name." ".$apaterno." ".$amaterno."Tu registro ha sido capturado.";
	$mail_subject = "Mail from: ".$nombre;
	$mail_header = "From: ".$nombre." <".$mail_from.">\r\n";
	
	
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
		$pass_enc = sha1($pass);

		$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($user)."'");
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
			echo $fail;
			header("refresh:3;url=regAdmin.php");							
		}else{
			$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($user)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
				echo $fail;
				header("refresh:3;url=regAdmin.php");
			} else {
				$query = @mysql_query("SELECT * FROM Dentista WHERE Usuario='".mysql_real_escape_string($user)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
					echo $fail;
					header("refresh:3;url=regAdmin.php");
				}
				else {
					$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($user)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
						echo $fail;
						header("refresh:3;url=regAdmin.php");
					} 
					else {
						$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($user)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
							echo $fail;
							header("refresh:3;url=regAdmin.php");
						}
						else {
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($user)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$user.' ya existe. Intente otro usuario';
								echo $fail;
								header("refresh:3;url=regAdmin.php");
							}
							
							else {
								$meter=@mysql_query('INSERT INTO Administrador (Usuario, Nombre,ApellidoPaterno,ApellidoMaterno, Password,
										ProfesionalSalud_Usuario, Correo) values ("'.mysql_real_escape_string($user).'","'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($apaterno).'","'.mysql_real_escape_string($amaterno).
										'","'.mysql_real_escape_string($pass_enc).'","'.$_SESSION['uid'].'","'.mysql_real_escape_string($correo).'")');
								
								if($meter){
									echo 'Administrador registrado con exito';
									$sendmail = mail($mail_to,$mail_subject,$mail_body,$mail_header);
								
									if($sendmail == true) {
										echo 'Mail sent!';
									}
									else {
										echo 'Mail not sent';
									}
								
									header("refresh:3;url=../principales/profesionalPrincipal.php");
								}
								else{
									$fail .= 'Hubo un error';
									echo $fail;
								}								
							}
						}
					}
				}
			}			
		}
		exit;
	}

}

else {
	$user = "Usuario:";
	$pass = "Contraseña:";
	$pass2 = "Repite Contraseña:";
	$name = "Nombre:";
	$apaterno = "Apellido Paterno:";
	$amaterno = "Apellido Materno:";
	$correo = "Correo Electronico:";
	$correo2 = "Repite Correo Electronico:";	
}

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validateUser($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Usuario.\n";
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
							<script type="text/javascript">
							function validate(form){								
								fail = validateNombre(form.nombre.value);
								fail += validateUser(form.user.value);
								fail += validatePaterno(form.apaterno.value,1);
								fail += validatePaterno(form.amaterno.value,2);
								fail += validatePass(form.pass.value);
								fail += validateEqualPass(form.pass2.value,form.pass.value);
								fail += validateCorreo(form.correo.value);
								fail += validateEqualCorreo(form.correo.value,form.correo2.value);
								
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

							function validateUser(field) {
								if (field =="") return "Favor de llenar el campo Nombre.\n";
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
							
							</script>
                        
							<form action="regAdmin.php" method="post" onSubmit="return validate(this)">
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
                    <li>
							<a href="../registros/regAdmin.php">Registrar administrador de sitio</a>                     
                    </li>
                    <li>
                        <a href="../construccion.html">Consultar directorio de consultorios</a>
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