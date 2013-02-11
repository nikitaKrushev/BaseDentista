<?php
/**
 * Autor: JosuÈ CastaÒeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 *
 * Descripcion:
 * 	La p·gina principal del director.
 * 
 */

session_start();
include '../accesoDentista.php';

//Checamos si hay una session vacia o si ya hay una sesion
if ($_SESSION['type'] != 4) {
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 5: //Profesional
			header("refresh:3;url=../principales/profesionalPrincipal.php");
			break;

		case 3: //Padre
			header("refresh:3;url=../principales/padrePrincipal.php");
			break;

		case 1://Dentista
			header("refresh:3, url=../principales/mainDentista2.php");
			break;

		case 2://Director
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina					
			break;

		case 6://Admin
			header("refresh:3;url=../principales/adminPage.php");
			break;


	}
	exit;
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
    <script type="text/javascript" src="js/jquery-1.6.2.min.js"></script>
    <script type="text/javascript" src="js/superfish.js"></script>
    <script type="text/javascript" src="js/jquery.nivo.slider.pack.js"></script>
    <script type="text/javascript" src="js/jquery.prettyPhoto.js"></script>
    <script type="text/javascript" src="js/jquery.prettySociable.js"></script>
    <script type="text/javascript" src="js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="js/main.js"></script>
    
</head>

<body id="home"><!-- #home || #page-post || #blog || #portfolio -->

    <!-- Page Start -->
    <div id="page">
        
        <!-- Main Column Start -->
        <div id="wrap">
            <div id="main-col"><!-- Nivo Slider -->
                <div id="slider-container">
                    <div id="big-slider"><!-- Add Slides Here -->
                        <a href="#"><img src="../img/pictures/slide-1.jpg" alt="" title="ÔøΩUnete al programa!" /></a>
                        <a href="#"><img src="../img/pictures/slide-2.png" alt="" title="ÔøΩRegistrate hoy!" /></a>
                        <a href="#"><img src="../img/pictures/slide-3.png" alt="" title="ÔøΩEmpezar a cuidar tus dientes es muy fÔøΩcil!" /></a>
                    </div>
                </div>
                
                <!-- Homepage Welcome Text -->
                <div id="homepage-post">
                <h1 class="p-title"><a href="#">Bienvenido al sitio de la Cartilla de Salud Bucal Digital</a></h1>
                    <div class="p-content">
                        <p>Perfil epidemiol√≥gico de caries dental</p>
                    </div>
                </div>
                
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
                                <input type="submit" value="Fin de sesi√≥n" />
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
                        Tijuana, B.C., M√©xico<br />
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