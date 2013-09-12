<?php
include '../accesoDentista.php';

if ($_SESSION['type'] != 2 ) { //Checamos si hay una session vacia o si ya hay una sesion
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 1: //Dentista
			header("refresh:3, url=../principales/mainDentista2.php");
		break;
			
		case 3://Maestro
			header("refresh:3;url=../principales/mainMaestro.php");
			break;

		case 4://Director
			header("refresh:3;url=../principales/directorPrincipal.php");
			break;

		case 5://Profesional principal
			header("refresh:3;url=../principales/profesionalPrincipal.php");
			break;
			
		case 6://Admin
				header("refresh:3;url=../principales/adminPage.php");
		break;
		
		
	}
	exit;
}
	$result = @mysql_query("SELECT idPadre FROM Padre where Usuario='".$_SESSION['uid']."'");
	$fila = mysql_fetch_row($result);
	
	$ninos = array(); //Los hijos del padre	
	$query = @mysql_query("SELECT * FROM Ninio WHERE Padre_idPadre='".$fila[0]."'");	
	//echo "SELECT * FROM Ninio WHERE Padre_idPadre='".$fila[0]."'";
		while ($existe= @mysql_fetch_object($query))
			$ninos[] = $existe;				
		$size= count($ninos);	
	//echo $_SESSION['uid'];

	if(isset($_POST['detail'])) { //Selecciono el detalle
				
		if(isset($_POST['ctrl'])) {
			$_SESSION['idNino'] = $_POST['ctrl']; //Para que la pagina de detalles tenga la informacion que requiere.
			
			//Primero vemos si el nino ha tenido una revision o no
			$query = @mysql_query("SELECT UltimaRevision FROM Ninio WHERE idNinio='".$_SESSION['idNino']."'");
			$revision= @mysql_fetch_object($query);
			if($revision->UltimaRevision == 0 ) {
				//header("Location: registroDientes.php");
				print '<script type="text/javascript">';
				print 'alert("Aun no se ha revisado al paciente")';
				print '</script>';
				header('refresh:0;URL=../principales/padrePrincipal.php');
				exit;
			}
			else
				header("Location: detalleSaludBucal2Padre.php");
		}		
		
	}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Revisi&oacute;n Trimestral</title>
		
		 <!-- Styles -->
	    <link rel="stylesheet" type="text/css" href="../css/style.css" />
	    <link rel="stylesheet" type="text/css" href="../css/revision.css" />	    
	    
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
                        <p>Perfil epidemiol&oacute;gico de caries dental</p>                                             
                    </div>
				</div>   
                                                                                 				
							
					<div id="tablaRevisa" class="divisionDetalles">
					
					 <form id="revisaSubmit" name="submision" action="consultaSaludPadre.php" method="post">
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
              	 <li class="act"><a href="../principales/mainDentista2.php">Inicio</a></li>
                        <li> <a href="../consulta/consultaSaludPadre.php">Consultar estado de salud de mi hijo(s)</a> </li>
                	   <li> <a href="../consulta/directorioConsultoriosPadres.php">Consultar directorio de consultorios</a> </li>             
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
                                <input type="submit" value="Fin de sesi&oacute;n" />
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