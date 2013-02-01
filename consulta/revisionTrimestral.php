<?php
include '../accesoDentista.php';

if ($_SESSION['type'] != 6 && $_SESSION['type'] != 1 ) { //Checamos si hay una session vacia o si ya hay una sesion
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 2: //Padre
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3://Maestro
			header("refresh:3;url=../principales/padrePrincipal.php");
			break;

		case 4://Director
			header("refresh:3;url=../principales/directorPrincipal.php");
			break;

		case 5://Admin
			header("refresh:3;url=../principales/profesionalPrincipal.php");
			break;
	}
	exit;
}

if(isset($_POST['posted'])) { 
	
	$texto = strip_tags($_POST['nombre']);
	$choice = $_POST['name'];
	$ninos = array();
	
	if($choice == "Busqueda por nombre") 
		$query = @mysql_query("SELECT * FROM Ninio WHERE Nombre='".mysql_real_escape_string($texto)."'");	
	else 
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($texto)."");
	//if($existe = @mysql_fetch_object($query)){
		while ($existe= @mysql_fetch_object($query))
			$ninos[] = $existe;				
		$size= count($ninos);	
}
else  {
	if(isset($_POST['detail'])) { //Selecciono el detalle
				
		if(isset($_POST['ctrl'])) {
			$_SESSION['idNino'] = $_POST['ctrl']; //Para que la pagina de detalles tenga la informacion que requiere.
			
			//Primero vemos si el nino ha tenido una revision o no
			$query = @mysql_query("SELECT UltimaRevision FROM Ninio WHERE idNinio='".$_SESSION['idNino']."'");
			$revision= @mysql_fetch_object($query);
			if($revision->UltimaRevision == 0 )
				header("Location: registroDientes.php");
			else
				header("Location: detallesTrimestral.php");
		}		
		
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Revisión Trimestral</title>
		
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
                    
                    <div id="revisa"	>
                    <ul>
                        <li>
						
                        
							<div id="revisaForm" style="color:#0000FF">
			
						  		<form action="revisionTrimestral.php" method="post">
						  			<input type="text"  value="Nombre o clave del niño" name="nombre" alt="Nombre:" title="Escribe el nombre del paciente" id="nombre" /><br>  					
						  			<input type="radio" name="name" CHECKED value="Busqueda por nombre">Busqueda por nombre<br>
						  			<input type="radio" name="name" value="Busqueda por clave">Busqueda por clave<br>  			  				
						  			<input type="submit" value="Buscar" />								
									<input type="hidden" name="posted" value="yes" />
						  		</form>
							</div>
							
					<div id="tablaRevisa" >
					
					<form id="revisaSubmit" name="submision" action="revisionTrimestral.php" method="post">
				  		<table>
				  			<tr>
				  				<th> Identificador </th>
				  				<th> Nombre </th>
				  				<th> Apellido Paterno </th>
				  				<th> Apellido Materno </th>
				  				<th> Fecha de Nacimiento </th>
				  				<th> Seleccionar </th>
				  			</tr>	
				  			<?php
				  				if(isset($size)) {
								for($i=0; $i<$size; $i++) {
									//foreach ($ninos as $row) : 
							?>
				  			<tr>
				  				<td> <?php echo $ninos[$i]->idNinio; ?> </td>
				  				<td> <?php echo $ninos[$i]->Nombre; ?> </td>
				  				<td> <?php echo $ninos[$i]->ApellidoPaterno; ?> </td>
				  				<td> <?php echo $ninos[$i]->ApellidoMaterno; ?> </td>
				  				<td> <?php echo $ninos[$i]->FechaNaciemiento; ?> </td>  
				  				<td> <input type="radio" name="ctrl" value="<?php echo $ninos[$i]->idNinio; ?>"></td>				
				  			</tr>
				  			 <?php } }?>
				  		</table>
				  		<br>
				  			<input type="hidden" name="detail" value="yes">	
				  			<input type="submit" value="Detalles">
				  		</form>
					</div>
										
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
            <a href="../principales/mainDentista2.php" id="logo">Foundation</a>
            
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
                        Tijuana, B.C., Mï¿½xico<br />
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