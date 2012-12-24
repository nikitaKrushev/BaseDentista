 <?php 
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
                <div id="slider-container">
                    <div id="big-slider"><!-- Add Slides Here -->
                        <a href="#"><img src="../img/pictures/slide-1.jpg" alt="" title="�Unete al programa!" /></a>
                        <a href="#"><img src="../img/pictures/slide-2.png" alt="" title="�Registrate hoy!" /></a>
                        <a href="#"><img src="../img/pictures/slide-3.png" alt="" title="�Empezar a cuidar tus dientes es muy f�cil!" /></a>
                    </div>
                </div>
                
                <!-- Homepage Welcome Text -->
                <div id="homepage-post">
                <h1 class="p-title"><a href="#">Bienvenido al sitio de la Cartilla de Salud Bucal Digital</a></h1>
                    <div class="p-content">
                        <p>Perfil epidemiológico de caries dental</p>
                    </div>
                </div>
                
                <!-- Homepage Teasers Start -->
 <div class="full-page-text">
                
                    <br/><br/><p>En M�xico unos de los aspectos de salud que m�s abandono han tenido ha  sido la Salud bucodental de la poblaci�n; hist�ricamente, este derecho le ha  sido negado a un gran porcentaje de usuarios de los servicios de salud  incluy�ndose, dentro de estos, a los de Baja California.</p>
<p><a href="../img/pictures/niniafeliz.png" title="Una ni�a muy feliz en su consulta"><img src="../img/pictures/niniafeliz.png" alt="Paciente" class="alignleft" /></a>La  Organizaci�n Mundial de la Salud, de acuerdo con su clasificaci�n  internacional, coloca a M�xico entre los pa�ses de alto rango de frecuencia en  afecciones bucales, dentro de ellos la caries dental que afecta a m�s del 90%  de la poblaci�n; tambi�n ha establecido que son la caries dental y la  enfermedad periodontal las enfermedades con mayor prevalencia a nivel mundial.</p>
                    <p>Hoy en d�a se desconoce la prevalencia de caries en B.C. y a pesar de  que la �ltima encuesta fue elaborada hace m�s de cinco a�os no existen datos  que nos indique que el �ndice de prevalencia de caries haya cambiado o que esa  tendencia fue mejorada.</p>
        <p>La Ley de Salud P�blica para el Estado de Baja California, establece  como un servicio b�sico de salud para toda persona la atenci�n bucodental</p>
<h3>Creaci�n de la Cartilla de Salud Bucal</h3>
<p><a href="../img/pictures/consultamexicana.png" title="Otra ni�a super feliz!"><img src="../img/pictures/consultamexicana.png" alt="Paciente" class="aligncenter" /></a></p>
                    <p>Uno de los aspectos m�s novedoso y de mayor trascendencia es el  relativo a la creaci�n de un documento oficial denominado &ldquo;CARTILLA DE SALUD  BUCAL DIGITAL&rdquo;, que tendr� como objeto llevar un registro y control de la  atenci�n bucal del ni�o y del adolescente, estableciendo un seguimiento en la  salud oral de cada ni�o que adem�s permita a cualquier persona con la simple  lectura conocer y valorar su estado de salud; por otra parte, ser� una  herramienta de medici�n con la que podremos elaborar� el Perfil epidemiol�gico de salud bucal, que  anualmente nos proporcione datos estad�sticos de los avances y logros obtenidos  en esta materia.</p>
</div>
                                
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
                    <li>
							<a href="../registros/regNinio.php">Registrar paciente</a>                        
                    </li>
                    <li>
							<a href="construccion.html">Consulta historia dental</a>                        
                    </li>
                    
                    <li>
                        <a href="../consulta/revisionTrimestral.php">Revisión trimestral</a>
                        
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
                        <a href="#"><img src="../img/pictures/zamudio.png" alt="Our Building" class="alignright" /></a>
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