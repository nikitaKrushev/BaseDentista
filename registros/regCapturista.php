<?php

include '../accesoDentista.php';
 //Checamos si hay una session vacia o si ya hay una sesion
if ($_SESSION['type'] != 3) {
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 1: //Dentista
			header("refresh:3, url=loggeado.php");
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
		
		}

}



if(isset($_POST['posted'])) {
	require_once('../funciones.php');
	conectar('localhost', 'monty', 'holygrail', 'BaseDientes');
	$mensaje = "Registro no exitoso, se encontraron los siguientes<br />errores en el formulario:";

	//code to take action as user submitted the data
	//recibe info
	
	$user = strip_tags($_POST['user']);
	$pass = strip_tags($_POST['pass']);
	$pass2 = strip_tags($_POST['pass2']);
	$name = strip_tags($_POST['name']);
	$apellido = strip_tags($_POST['apellido']);	

	//Para la validacion
	$fail = validate_user($user);
	$fail .= validate_pass($pass);
	$fail .= validate_name(trim($name));
	$fail .= validate_apellido($apellido);
	$fail .= validate_equal_pass($pass,$pass2);

	if($fail == "") {
		//Encriptamos contrasenia
		$pass_enc = sha1($pass);

		$query = @mysql_query("SELECT * FROM Capturista WHERE Usuario='".mysql_real_escape_string($user)."' AND Password= '".mysql_real_escape_string($pass)."'");
		if($existe = @mysql_fetch_object($query)){
			echo 'este usuario '.$user.' ya existe';
		}else{
			$meter=@mysql_query('INSERT INTO Capturista (Usuario, Password,Nombre,ApellidoPaterno) values ("'.mysql_real_escape_string($user).'","'.mysql_real_escape_string($pass_enc).'","'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($apellido).'")');

			if($meter){
				echo 'Capturista registrado con exito';
			}else{
				echo 'Hubo un error';
			}
		}
		exit;
	}
	
}

else {
	$user = "Usuario";
	$pass = "Contraseña";
	$pass2 = "Repite Contraseña";
	$name = "Nombre";
	$apellido = "Apellido";
	
}

function validate_user($field) {
	if ($field=="") return "El campo Usuario esta vacio.\n";
	return "";
}

function validate_name($field) {
	if ($field=="") {
		return "El campo Nombre esta vacio.\n";		
	}
	return "";
}

function validate_apellido($field) {
	if ($field=="") return "El campo Apellido esta vacio.\n";
	return "";
}

function validate_equal_pass($field,$field2) {
	if($field !=$field2) return "Las contraseñas no son iguales.\n";
	return "";
}

function validate_pass($field) {

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
                        <p>Página de registro de Capturista</p>
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
										fail = validateUser(form.name.value,1);
										fail += validatePassword(form.pass.value);
										fail += validateUser(form.nombre.value,2);
										fail += validateUser(form.apellido.value,3);		
										fail += validatePasswordEqual(form.pass.value,form.pass2.value);
										if (fail =="") return true;
										else {
											alert(fail);
											return false;
										}
									}
									
									function validateUser(field,tipo) {
										if (field=="") { 
											switch(tipo) {
											case 1:
												return "El campo Usuario esta vacio.\n";
												break;
												
											case 2:
												return "El campo Nombre esta vacio.\n";
												break;
												
											case 3:
												return "El campo Apellido esta vacio.\n";
												break;
												
												default:
													return "Un campo esta vacio.\n";
												break;
											}	
											
										}
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
							</script>
                        	                        	
							<form action="regCapturista.php" method="post" onSubmit="return validate(this)">		
								<input type="text"  value="<?php echo $name;?>" alt="Nombre:" title="Pon tu nombre" name="name" id="nombre" />									
								<input type="text" value="<?php echo $apellido;?>" alt="Apellido Paterno:" title="Escribe tu apellido" name="apellido" id="apellido" />										
								<input type="text" value="<?php echo $user;?>" alt="Usuario:" title="Escribe tu usuario" name="user" id="name" />					
								<input type="password" alt="Contraseña:" title="Pon tu contraseña:" name="pass" id="pass" />					
								<input type="password" alt="Repite la Contraseña:" title="Repite la Contraseña:" name="pass2" id="pass2"/>								
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


<?php 
/*

if(isset($_POST['posted'])) {
	require_once('../funciones.php');
	conectar('localhost', 'monty', 'holygrail', 'BaseDientes');
	$mensaje = "Registro no exitoso, se encontraron los siguientes<br />errores en el formulario:";
	
	//code to take action as user submitted the data
	//recibe info
	$user = strip_tags($_POST['user']);
	$pass = strip_tags($_POST['pass']);
	$pass2 = strip_tags($_POST['pass2']);
	$name = strip_tags($_POST['name']);
	$apellido = strip_tags($_POST['apellido']);
	
	
	//Para la validacion
	$fail = validate_user($user);
	$fail .= validate_pass($pass);
	$fail .= validate_name(trim($name));
	$fail .= validate_apellido($apellido);
	$fail .= validate_equal_pass($pass,$pass2);
	
	echo "<html><head><title>Registro Capturista</title>";
	
	if($fail == "") {
		echo "</head><body>	Datos registrados con exito!</body></html>";
		//Encriptamos contrasenia
		$pass_enc = sha1($pass);
	
		$query = @mysql_query("SELECT * FROM Capturista WHERE Usuario='".mysql_real_escape_string($user)."' AND Password= '".mysql_real_escape_string($pass)."'");
		if($existe = @mysql_fetch_object($query)){
			echo 'este usuario '.$user.' ya existe';
		}else{
			$meter=@mysql_query('INSERT INTO Capturista (Usuario, Password,Nombre,ApellidoPaterno) values ("'.mysql_real_escape_string($user).'","'.mysql_real_escape_string($pass_enc).'","'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($apellido).'")');
	
			if($meter){
				echo 'Capturista registrado con exito';
			}else{
				echo 'Hubo un error';
			}
		}
		exit;
	}
	
}

//Output HTML y JavaScript

echo <<<_END
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Seccion HTML -->
<style>.signup { border: 1px solid #999999;
font: normal 14px helvetica; color:#444444; }</style>
<script type="text/javascript">
function validate(form){
		fail = validateUser(form.name.value,1);
		fail += validatePassword(form.pass.value);
		fail += validateUser(form.nombre.value,2);
		fail += validateUser(form.apellido.value,3);		
		fail += validatePasswordEqual(form.pass.value,form.pass2.value);
		if (fail =="") return true;
		else {
			alert(fail);
			return false;
		}
	}
	
	function validateUser(field,tipo) {
		if (field=="") { 
			switch(tipo) {
			case 1:
				return "El campo Usuario esta vacio.\n";
				break;
				
			case 2:
				return "El campo Nombre esta vacio.\n";
				break;
				
			case 3:
				return "El campo Apellido esta vacio.\n";
				break;
				
				default:
					return "Un campo esta vacio.\n";
				break;
			}	
			
		}
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
</script></head><body>
<table class="signup" border="0" cellpadding="2"
	cellspacing="5" bgcolor="#eeeeee">
<th colspan="2" align="center">Formulario de inscripción</th> 
<tr><td colspan="2">$mensaje <p><font color=red size=1><i>$fail</i></font></p>
</td></tr>	
<form action="regCapturista.php" method="post" onSubmit="return validate(this)">		
			<tr><td>Nombre: </td><td><input type="text" name="name" size="20" id="nombre" value="$name" />
				
			<tr><td>Apellido Paterno: </td><td><input type="text" name="apellido" size="20" id="apellido" value="$apellido" />			
		
			<tr><td>Usuario: </td><td><input type="text" name="user" size="20" id="name" value="$user" />

			<tr><td>Contraseña:</td><td><input type="password" name="pass" size="20" id="pass" value="$pass" />

			<tr><td>Repite la Contraseña:</td><td><input type="password" name="pass2" size="20" id="pass2" value="$pass2" />
			
			<tr><td><input type="submit" value="Registrar" /></td><td>
			<input type="hidden" name="posted" value="yes" />
			
	</form>
_END;
	
//PHP FUNCIONES
function validate_user($field) {
	if (field=="") return "El campo Usuario esta vacio.\n";
	return "";				 			
}

function validate_name($field) {
	if (field=="") return "El campo Nombre esta vacio.\n";
	return "";
}

function validate_apellido($field) {
	if (field=="") return "El campo Apellido esta vacio.\n";
	return "";
}

function validate_equal_pass($field,$field2) {
	if($field !=$field2) return "Las contraseñas no son iguales.\n";
	return "";
}

function validate_pass($field) {
	
	if(field == "") return "Introduce una contraseña.\n";
	else{ 
		
		if (strlen($field) < 5)
			return "El tamaño de la contraseña debe ser por lo menos de 5 caracteres.\n";
	
		else
			if (! preg_match("/[a-z]/",$field) || ! preg_match("/[0-9]/",$field))
				return "La contraseña requiere por lo menos un caracter de [a-z] y [0-9].\n";
	}
	return "";
}
*/
?>
