<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 17/FEB/2013
 *
 * Descripcion:
 * 	Registro de un pais, mediante una forma de captura en html.
 *
 *
 */

include '../accesoDentista.php';

if ($_SESSION['type'] != 5) { //Checamos si hay una session vacia o si ya hay una sesion
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

		case 6://Administrador
			header("refresh:3;url=../principales/adminPage.php");
			break;
	}
	exit;
}


if(isset($_POST['posted'])) {

	require_once('../funciones.php');
	conectar($servidor, $user, $pass, $name);
	
	//recibe info
	$nombre = strtoupper(strip_tags($_POST['nombre']));

	//Validacion
	$fail = validaNombre(trim($nombre));
	
	if($fail == "") {
		$query = @mysql_query("SELECT * FROM Pais WHERE Nombre='".mysql_real_escape_string($nombre)."'");
		
		if($existe = @mysql_fetch_object($query)){			
			$fail.= 'El pais '.$nombre.' ya existe';
			print '<script type="text/javascript">';
			print 'alert("Ya existe el pais")';
			print '</script>';			
			header("refresh:1;url=regPais.php");
			exit;
			
		}else{
			$meter=@mysql_query('INSERT INTO Pais values ("'.mysql_real_escape_string($nombre).'")');			
		
			if($meter){
				print '<script type="text/javascript">';
				print 'alert("Registro exitoso")';
				print '</script>';
				header("refresh:1;url=../principales/profesionalPrincipal.php");
				exit;									
			}else{
				print '<script type="text/javascript">';
				print 'alert("Hubo un error en el registro")';
				print '</script>';
				header("refresh:1;url=regPais.php");					
			}
		}
		exit;
	}
}

else {
	$nombre = "Nombre:";	
}

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras sin acentos.\n";
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
                        <p>Página de registro de Pa&iacute;s</p>
                        <?php 
                        if(isset($_POST['posted'])) {
                     	
                        	echo $fail;
                        }
                        ?>
                    </div>
                    
                    <div id="registra"	>
                    <ul>
                        <li>
					        
							<form action="regPais.php" method="post" >
								<input type="text" value="<?php echo $nombre;?>" name="nombre" alt="Nombre:" title="Escribre el nombre del pais:" id="nombre"/>
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
                        <a href="#"><img src="img/pictures/zamudio.png" class="alignright" /></a>
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