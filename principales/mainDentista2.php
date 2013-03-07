 <?php 
 /**
  * Autor: Josu� Casta�eda
  * Escrito: 2/FEB/2013
  * Ultima actualizacion: 2/FEB/2013
  *
  * Descripcion:
  *  La p�gina principal de los dentistas.
  *
  */
 
 session_start();
 include '../accesoDentista.php';
 
 //Checamos si hay una session vacia o si ya hay una sesion
 if ($_SESSION['type'] != 1) {
 	echo("Contenido Restringido");
 	switch($_SESSION['type']) {
 
 		case 5: //Dentista
 			header("refresh:3;url=../principales/profesionalPrincipal.php");
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
                    </div>
                </div>
                
                <!-- Homepage Teasers Start -->
 
                                
                <!-- Homepage Teasers End -->
    
            </div>
        </div>
        <!-- Main Column End -->
        
        <!-- Left Column Start -->
        <div id="left-col">
        
            <!-- Logo -->
            <a href="mainDetista2.php" id="logo">Foundation</a>
            
            <!-- Main Naigation (active - .act) -->
            <div id="main-nav">
                <ul>
                    <li class="act"><a href="mainDentista2.php">Inicio</a></li>
                    <li> <a href="../registros/regDenPaciente.php">Registrar paciente</a> </li>
                    <li> <a href="../consulta/consultaSaludBucal.php">Consulta historia dental</a> </li>
                    <li> <a href="../consulta/revisionTrimestral.php">Revisión trimestral</a> </li>
                    <li> <a href="../consulta/incrementarDentadura.php">Añadir dientes a paciente</a> </li>
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
                    
                </div>
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