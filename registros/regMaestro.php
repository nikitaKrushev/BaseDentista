<?php
session_start();
include '../accesoDentista.php';
//Checamos si hay una session vacia o si ya hay una sesion
if ($_SESSION['type'] != 4 && $_SESSION['type'] != 6) {
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 1: //Dentista
			header("refresh:3, url=loggeado.php");
			break;

		case 2: //Padre
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3://Maestro
			header("refresh:3;url=../principales/MainMaestro.php");
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
	
	//recibe info
	$nombre = strip_tags($_POST['nombre']);
	$apaterno = strip_tags($_POST['apaterno']);
	$pass = strip_tags($_POST['password']);
	$pass2 = strip_tags($_POST['password2']);
	$correo = strip_tags($_POST['correo']);
	$correo2 = strip_tags($_POST['correo2']);
	$escuela = strip_tags($_POST['escuela']);
	$usuario = strip_tags($_POST['usuario']);
		
	/***
	 * Codigo necesario para mandar correo de confirmacion
	*/
	
	$to = $correo;
	$nameto = $nombre." ".$apaterno;
	$from = "registro@cartillabucaldigital.org";
	$namefrom = "Registro de cuentas";
	$subject = "Asunto";
	$message =  $nombre." ".$apaterno." "."Tu registro ha sido capturado. Ya puedes utilizar la pagina. Bienvenido!"; //Pondremos contrasenia y usuario al usuario
		
	//Para la validacion
	$fail = validaNombre(trim($nombre));
	$fail .= validaPaterno(trim($apaterno));
	$fail .=validaPass($pass);
	$fail .= validaEqualPass($pass,$pass2);
	$fail .= validaCorreo($correo);
	$fail .= validaEqualCorreo($correo,$correo2);
	$fail .= validaEscuela($escuela);
	
	if($fail == "") {
	
		$query = @mysql_query("SELECT * FROM Maestro WHERE idMaestro='".mysql_real_escape_string($usuario)."'");		
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$clave.' ya existe. Intente otro usuario';
			echo $fail;
			header("refresh:3;url=regMaestro.php");							
	
		}else{
			$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($usuario)."'");
			if($existe = @mysql_fetch_object($query)){
				$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
				echo $fail;
				header("refresh:3;url=regAdmin.php");
			}else {
				$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($usuario)."'");
				if($existe = @mysql_fetch_object($query)){
					$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
					echo $fail;
					header("refresh:3;url=regAdmin.php");
				}
				else {
					$query = @mysql_query("SELECT * FROM Dentista WHERE Usuario='".mysql_real_escape_string($usuario)."'");
					if($existe = @mysql_fetch_object($query)){
						$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
						echo $fail;
						header("refresh:3;url=regAdmin.php");
					}
					else {
						$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($usuario)."'");
						if($existe = @mysql_fetch_object($query)){
							$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
							echo $fail;
							header("refresh:3;url=regAdmin.php");
						}
						else {
							$query = @mysql_query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($usuario)."'");
							if($existe = @mysql_fetch_object($query)){
								$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
								echo $fail;
								header("refresh:3;url=regAdmin.php");
							}
							
							else {
								$pass_enc = sha1($pass);
								
								//Obtener direccion de escuela
								$meter3 = @mysql_query('SELECT * from Escuela where idEscuela="'.mysql_real_escape_string($escuela).'"');
								
								$idEscuela = @mysql_fetch_object($meter3);
								
								$meter=@mysql_query('INSERT INTO Maestro (Nombre, ApellidoPaterno,Password,Usuario, Escuela_idEscuela,Escuela_Direccion_idDireccion,Correo) 
										values ("'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($apaterno).'","'.mysql_real_escape_string($pass_enc).'","'
											.mysql_real_escape_string($usuario).'","'.mysql_real_escape_string($idEscuela->idEscuela).'","'.mysql_real_escape_string($idEscuela->Direccion_idDireccion).'","'.mysql_real_escape_string($correo).'")');
								if($meter){
									echo 'Administrador registrado con exito';
									$sendmail = mail($mail_to,$mail_subject,$mail_body,$mail_header);
									
									//if($sendmail == true) {
										echo 'Mail sent!';
									//}
									//else {
									//	echo 'Mail not sent';
									//}
									authSendEmail ($from, $namefrom, $to, $nameto, $subject, $message);
									if($_SESSION['type'] == 4)
										header("refresh:3;url=../principales/directorPrincipal.php");
									else 
										header("refresh:3;url=../principales/adminPage.php");
								}else{
									echo 'Hubo un error';
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
	$nombre = "Nombre";
	$apaterno = "Apellido Paterno";
	$pass = "Contrasenia";
	$pass2 = "Repite contrasenia";
	$correo = "Correo electronico";
	$correo2 = "Repite correo";
	$escuela = "Escuela";
	$clave = "Clave";
	
}

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre )) return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validaPaterno($nombre) {
	if ($nombre =="") {
		return "Favor de llenar el campo apellido paterno.\n";
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

function validaEscuela($escuela) {
	if($escuela == "") return "Llena el campo de escuela";
}

function validaClave($clave) {
	if (! preg_match("/^[0-9]+$/",$clave))
		return "La clave requiere digitos.\n";
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
                        <p>Página de registro de Maestros</p>
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
					fail += validatePaterno(form.apaterno.value);
					fail += validatePass(form.pass.value);
					fail += validateEqualPass(form.pass2.value,form.pass.value);
					fail += validateCorreo(form.correo.value);
					fail += validateEqualCorreo(form.correo.value,form.correo2.value);
					fail += validateEscuela(form.escuela.value);
					
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
							return "El campo Nombre Ht solo contiene letras.\n";
					return "";
				}
				
				function validatePaterno(field) {
					if (field =="") {
							return "Favor de llenar el campo apellido paterno.\n";
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
				
				function validateEscuela(field) {
					if (! /^[0-9]+$/.test(field))
						return "El campo Escuela requiere digitos.\n";					
					return "";
				}
					
				
			</script>               	                        								
							<form action="regMaestro.php" method="post" onSubmit="return validate(this)">
								<input type="text" value="<?php echo $nombre;?>" name="nombre" alt="Nombre: " title="Introduce el nombre del maestro" id="nombre"/>
								<input type="text" value="<?php echo $apaterno;?>" name="apaterno" alt="Apellido paterno: " title="Introduce tu apellido paterno" id="apaterno"/>			
								<input type="text" value="<?php echo $usuario;?>" name="usuario" alt="Usuario asignado: " title="Introduce un usuario" id="usuario" />
								<input type="password" value="<?php echo $pass;?>" name="password" alt="Contraseña:" title="Introduce la contrasenia"   id="password" />				
								<input type="password" value="<?php echo $pass2;?>" name="password2" alt="Confirmar Contraseña: " title="Repite la contrasenia"  id="password2" />
								<input type="text" value="<?php echo $correo;?>" name="correo" alt="Correo electronico: " title="Escribe un correo electronico valido" id="correo" />
								<input type="text" value="<?php echo $correo2;?>" name="correo2" alt="Confirmar Correo electronico: " title="Repite el correo electronico"  id="correo2"/>			
								<input type="text" value="<?php echo $escuela;?>" name="escuela" alt="Escuela: " title="Indica tu escuela de procedencia"  id="escuela"  />
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
            <a href="directorPrincipal.php" id="logo">Foundation</a>
            
            <!-- Main Naigation (active - .act) -->
            <div id="main-nav">
                <ul>    
                    <li class="act"><a href="directorPrincipal.php">Inicio</a></li>
                    <li>
                        <a href="construccion.html">Consulta tu escuela	</a>
                    </li>
                    <li>
                        <a href="../registros/regMaestro.php">Registra maestro</a>      
                    </li>
                    <li>
                        <a href="construccion.html">Registra grupo</a>      
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
                        <a href="#"><img src="../img/pictures/zamudio.png" alt="Our Building" class="alignright" /></a>
                        Tijuana, B.C., México<br />
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