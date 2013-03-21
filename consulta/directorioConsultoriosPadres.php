<?php
include '../accesoDentista.php';

if(isset($_POST['posted'])) {
	$texto = strtoupper(strip_tags($_POST['nombre']));
	$choice = $_POST['name'];
	$consultorios = array();
	
	if($choice == "Nombre")	{	
		$query =  @mysql_query("SELECT Consultorio.Nombre AS Consultorio_Nombre,Telefono,Colonia,Calle,NumeroPostal,Ciudad.Nombre AS Ciudad_Nombre,Ciudad.Estado_Nombre AS Ciudad_Estado_Nombre
					FROM Consultorio,Direccion,Ciudad WHERE Consultorio.Nombre='$texto' && Direccion_idDireccion = idDireccion && Ciudad_idCiudad = idCiudad ");		
	}
	else {
		if($choice == "Colonia") {
			
			$query = @mysql_query("SELECT Consultorio.Nombre AS Consultorio_Nombre,Telefono,Colonia,Calle,NumeroPostal,Ciudad.Nombre AS Ciudad_Nombre,Ciudad.Estado_Nombre AS Ciudad_Estado_Nombre
						FROM Consultorio,Direccion,Ciudad WHERE Colonia='$texto' && Direccion_idDireccion = idDireccion && Ciudad_idCiudad = idCiudad ");			
		}
		else {
			if($choice == "Calle") {
				$query = @mysql_query("SELECT Consultorio.Nombre AS Consultorio_Nombre,Telefono,Colonia,Calle,NumeroPostal,Ciudad.Nombre AS Ciudad_Nombre,Ciudad.Estado_Nombre AS Ciudad_Estado_Nombre
							FROM Consultorio,Direccion,Ciudad WHERE Calle='$texto' && Direccion_idDireccion = idDireccion && Ciudad_idCiudad = idCiudad ");				
			}
			else {
				if($choice == "Postal") {
					$query =  @mysql_query("SELECT Consultorio.Nombre AS Consultorio_Nombre,Telefono,Colonia,Calle,NumeroPostal,Ciudad.Nombre AS Ciudad_Nombre,Ciudad.Estado_Nombre AS Ciudad_Estado_Nombre
						FROM Consultorio,Direccion,Ciudad WHERE NumeroPostal='$texto' && Direccion_idDireccion = idDireccion && Ciudad_idCiudad = idCiudad ");				
				}
				else {
					if($choice == "Ciudad") { 
						$query = @mysql_query("SELECT Consultorio.Nombre AS Consultorio_Nombre,Telefono,Colonia,Calle,NumeroPostal,Ciudad.Nombre AS Ciudad_Nombre,Ciudad.Estado_Nombre AS Ciudad_Estado_Nombre
									FROM Consultorio,Direccion,Ciudad WHERE Ciudad.Nombre='$texto' && Direccion_idDireccion = idDireccion && Ciudad_idCiudad = idCiudad ");						
					}
					else {
						if($choice == "Estado") {
							$query=@mysql_query("SELECT Consultorio.Nombre AS Consultorio_Nombre,Telefono,Colonia,Calle,NumeroPostal,Ciudad.Nombre AS Ciudad_Nombre,Ciudad.Estado_Nombre AS Ciudad_Estado_Nombre
									FROM Consultorio,Direccion,Ciudad WHERE Ciudad_Estado_Nombre='$texto' && Direccion_idDireccion = idDireccion && Ciudad_idCiudad = idCiudad ");
							
						}
					}
				}				
			}
		}
	}
	
	while ($existe= @mysql_fetch_object($query))
		$consultorios[] = $existe;
	$size= count($consultorios);		
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
		<title>Directorio de consultorios</title>
		
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
                  
                <div id="titulo" class="divisionDetalles">
                	Directorio de consultorios
                </div>
                  
                <div id="revisa" class="divisionDetalles">                                            					                       
					<div id="revisaForm" style="color:#0000FF">			
						<form action="directorioConsultoriosPadres.php" method="post">
							<input type="text"  value="Nombre o clave del ni�o" name="nombre" alt="Texto:" title="" id="nombre" /><br>  					
						  	<input type="radio" name="name" CHECKED value="Nombre">B&uacute;squeda por nombre<br>
						  	<input type="radio" name="name" value="Colonia">B&uacute;squeda por colonia<br>
						  	<input type="radio" name="name" value="Calle">B&uacute;squeda por calle<br>						  			
						  	<input type="radio" name="name" value="Postal">B&uacute;squeda por n&uacute;mero postal<br>
						  	<input type="radio" name="name" value="Ciudad">B&uacute;squeda por ciudad<br>    			  				
						  	<input type="radio" name="name" value="Estado">B&uacute;squeda por estado<br>    			  										  	    			  			
						  	<input type="submit" value="Buscar" />								
							<input type="hidden" name="posted" value="yes" />
						 </form>
					</div>
							
					<div id="tablaRevisa" class="divisionDetalles">
					
						
				  						  			
				  		
					</div>
						<table  width=40%  height=100>
				  				<tr>
				  					<th> Nombre </th>
				  					<th> Tel&eacute;fono </th>
				  					<th> Colonia </th>
				  					<th> Calle </th>
				  					<th> Num&eacute;ro postal </th>
				  					<th> Ciudad </th>
				  					<th> Estado </th>				  					
				  				</tr>	
				  				<?php
				  					if(isset($size)) {
									for($i=0; $i<$size; $i++) {
								?>
				  				<tr>
				  					<td> <?php echo $consultorios[$i]->Consultorio_Nombre; ?> </td>
				  					<td> <?php echo $consultorios[$i]->Telefono; ?> </td>
				  					<td> <?php echo $consultorios[$i]->Colonia; ?> </td>
				  					<td> <?php echo $consultorios[$i]->Calle; ?> </td>
				  					<td> <?php echo $consultorios[$i]->NumeroPostal; ?> </td>
				  					<td> <?php echo $consultorios[$i]->Ciudad_Nombre; ?> </td>
				  					<td> <?php echo $consultorios[$i]->Ciudad_Estado_Nombre; ?> </td>
				  				</tr>
				  			 	<?php } }?>
				  			</table>
				  			<br>										                                           
            	</div>         
            </div>
        </div>
        <!-- Main Column End -->
        
        <!-- Left Column Start -->
        <div id="left-col">
        
        	<!-- Logo -->
            <a href="../principales/padrePrincipal.php" id="logo">Foundation</a>
            
            <!-- Main Naigation (active - .act) -->
            <div id="main-nav">
            	<ul>
              		<li class="act"><a href="../principales/padrePrincipal.php">Inicio</a></li>
                       <li> <a href="../consulta/consultaSaludPadre.php">Consultar estado de salud de mi hijo(s)</a> </li>
                   <li> <a href="../consulta/directorioConsultoriosPadres.php">Consultar directorio de consultorios</a> </li>                           
                </ul>
            </div>
            
            <!-- News Widget -->
            <div class="widget w-news">
                <h4 class="w-title title-light">Cerrar sesi&oacute;n.</h4>
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
                      <img src="img/pictures/zamudio.png" class="alignright" />
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