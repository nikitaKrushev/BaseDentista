<?php
define ("FILEREPOSITORY","/srv/http/t/src/FILEREPOSITORY/");

if (is_uploaded_file($_FILES['classnotes']['tmp_name'])) {

	if ($_FILES['classnotes']['type'] != "application/pdf") {
		echo "<p>Class notes must be uploaded in PDF format.</p>";
	} else {
		$name = $_POST['name'];
		$name2 =$_POST['name2'];
		if($name=="" || $name2=="") 
			echo "Llenar los datos";
		else
			$result = move_uploaded_file($_FILES['classnotes']['tmp_name'], FILEREPOSITORY."$name$name2.pdf");
		if ($result == 1) echo "<p>File successfully uploaded.</p>";
		else echo "<p>There was a problem uploading the file.</p>";
	} #endIF
} #endIF
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
                        <p>Página de registro Profesionales de Salud. Documentos </p>
                        
                        <?php 
                        if(isset($_POST['posted'])) {
                     	
                        	echo $fail;
                        }
                        ?>
                    </div>
                    
                    <div id="texto" > 
                    	<p> Para facilitar tu registro, ponemos a disposición una plantilla que puedes
                    	descargar y llenar con tus datos, despues puedes subir el archivo en formato pdf
                    	y nosotros nos encargamos de darte de alta.</p>
                   
                    	
                    	<p>Cuando tu solicitud sea recibida, te enviaremos
                    	un correo de confirmación con tu usuario y contraseña, para que puedas aprovechar de nuestros
                    	servicios.</p>
                    	
                    	<p>La plantilla se encuentra abajo.</p>
                    </div>
                    
                    <div id="plantilla">
                    <style type="text/css">
						A:link {text-decoration: none}
						A:visited {text-decoration: none}
						A:active {text-decoration: none}
						A:hover {text-decoration: underline; color: red;}
						</style>
                    	<a href="../FILEREPOSITORY/PlantillaDentista.pdf" text="Plantilla" >Plantilla:</a>
                    </div>
                    
                    <div id="registra"	>
                    <ul>
                        <li>														
							<form action="regDentistaPdf.php" enctype="multipart/form-data" method="post">
							   <br /> <input type="text" name="name" alt="Nombre(s):" title="Escribe tu nombre(s)" value="" /><br />
							   <br /> <input type="text" alt="Apellidos(s):" title="Escribe tus apellidos" name="name2" value="" /><br />
							   <br /> <input type="file" name="classnotes" alt="Archivo: "name="classnotes" value="" /><br />
							   <p><input type="submit" name="submit" value="Subir archivo" /></p>
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
                    <li>
                        <a href="ProfesionalSaludPrincipal.php">Profesional de Salud</a>                        
                    </li>
                    <li>
                        <a href="padrePrincipal.php">Padres de familia</a>
                    </li>
                    <li><a href="escuelaPrincipal">Escuelas</a></li>
                    
                </ul>
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