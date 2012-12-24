<?php

include '../accesoDentista.php';

if(isset($_POST['posted'])) {

	require_once('../funciones.php');
	conectar('localhost', 'monty', 'holygrail', 'BaseDientes');
		
	//recibe info
	$nomesc = strip_tags($_POST['nomesc']);
	$zone = strip_tags($_POST['zone']);
	$clave = strip_tags($_POST['clave']);
	$direccion = strip_tags($_POST['direccion']);
	
	//Para validacion
	$fail = validaNombre(trim($nomesc));
	$fail .=  validaZona($zone);
	
	echo "<html><head><title>Registro Consultorio</title>";
	
	if($fail == "") {
	
		$query = @mysql_query('SELECT * FROM Escuela WHERE NombreEscuela="'.mysql_real_escape_string($nomesc).'');
		
		if($existe = @mysql_fetch_object($query)){
			echo 'esta escuela '.$nomesc.' ya esta registrada';
		}else{
			$meter = @mysql_query('INSERT INTO Escuela (idEscuela,NombreEscuela,Zona_ClaveZona,Direccion_idDireccion) values ("'.mysql_real_escape_string($clave).'","'.mysql_real_escape_string($nomesc).'",'.mysql_real_escape_string($zone).','.mysql_real_escape_string($direccion).')');
		      
			if($meter){
				echo 'escuela registrada con exito';
			}else{
				echo 'Hubo un error';
			}
		}
		exit;
	}
}

else {
	$nomesc = "Nombre";
	$zone = "Zona:";
	$clave = "Clave:";
	$direccion = "Direccion";
	
}

function validaZona($zone) {
	if (! preg_match("/^[0-9]+$/",$zone))
		return "La zona requiere digitos.\n";
	return "";
}

function validaNombre($nombre){
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validaClave($clave) {
	if (! preg_match("/^[0-9]+$/",$clave))
		return "La clave requiere digitos.\n";
	return "";
}

function validaDireccion($direccion) {
	if (! preg_match("/^[0-9]+$/",$direccion))
		return "La direccion requiere digitos.\n";
	return "";
}

/*include '../accesoDentista.php';
 //Checamos si hay una session vacia o si ya hay una sesion
if ($_SESSION['type'] != 3) {
echo("Contenido Restringido");
switch($_SESSION['type']) {

case 1:
header("refresh:3, url=loggeado.php");
break;

case 2:
header( "refresh:3;url=padrePrincipal.php" ); //Redireccionar a pagina
break;

}

}
*/

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
								fail = validateNombre(form.nombre.value);
								fail += validateZona(form.zone.value);
								fail += validateClave(form.clave.value);
								fail += validateDireccion(form.direccion.value);
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
							
							function validateZona(field) {
									if (! /^[0-9]+$/.test(field))
									return "La zona requiere digitos.\n";					
								return "";
							}
							
							function validateClave(field) {
									if (! /^[0-9]+$/.test(field))
									return "La clave requiere digitos.\n";					
								return "";
							}
							
							function validateDireccion(field) {
									if (! /^[0-9]+$/.test(field))
									return "La direccion requiere digitos.\n";					
								return "";
							}
						</script>                        	                        	
							
							<form action="regEscuela.php" method="post" onSubmit="return validate(this)">
								<input type="text" value="<?php echo $clave;?>" alt="Clave:" title="Escribe la clave de la escuela" name="clave" id="clave" />
								<input type="text" value="<?php echo $nomesc;?>" alt ="Nombre:" title="Escribe el nombre de la escuela" name="nomesc" id="nomesc" />
								<input type="text" value="<?php echo $zone;?>" alt="Zona:"  title="Escribe la Zona de la escuela" name="zone" id="zone"/>
								<input type="text" value="<?php echo $direccion;?>" alt="Direccion:" title="Escribe la direccion" name="direccion" id="direccion"/>			
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
