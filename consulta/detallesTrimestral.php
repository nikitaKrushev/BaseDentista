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
echo $_SESSION['idNino'];

	//Recuperar el objeto nino
	$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
	$existe= @mysql_fetch_object($query);
	if($existe->UltimaRevision == 0) { //Generar una nueva revision			
		for ($j=0; $j<35; $j++ ) 
			$dientes[$j] = 0; //Valor default de los dientes
		
		
	} else { //Abrir la mas reciente revision del nino
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
		$existe= @mysql_fetch_object($query);		
		$query2 = @mysql_query("SELECT * FROM Dentadura WHERE idDentadura=".mysql_real_escape_string($existe->UltimaRevision)."");
		$dentadura = @mysql_fetch_object($query2);
		$dientes[] = (array) $dentadura;	//A partir de 1 es indice de dientes
	}

	$nombreNino = $existe->Nombre;
	$apellidoPNino = $existe->ApellidoPaterno;
	$apellidoMNino = $existe->ApellidoMaterno;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<!-- Estilos -->
 	<link rel="stylesheet" type="text/css" href="../css/style.css" />	
    <link rel="stylesheet" type="text/css" href="../css/revision.css" />
    <link rel="stylesheet" href="../public/js/ui-lightness/jquery-ui.css" />
	
<title>Detalles dentadura</title>
 <!-- JavaScript -->
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
  		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>			
	    <script type="text/javascript" src="../js/superfish.js"></script>
	    <script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
	    <script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
	    <script type="text/javascript" src="../js/jquery.prettySociable.js"></script>
	    <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script  type="text/javascript" src="../js/custom.js"></script>
	 
</head>
<body id="home" ><!-- #home || #page-post || #blog || #portfolio -->	

	    <!-- Page Start -->
    <div id="page" >
        
        <!-- Main Column Start -->
        <div id="wrap">
            <div id="main-col"><!-- Nivo Slider -->
                
                <div id="box" style=display:none>
                	Antiguo valor: <input value="" id="antiguoValor" type="text" ><br />
                	Nuevo valor: <input type="text" value="" id="nuevoValor"> <br />         
                </div>
                <!-- Homepage Welcome Text -->
                <div id="homepage-post">
                <h1 class="p-title" ><a href="#">Bienvenido al sitio de la Cartilla de Salud Bucal Digital</a></h1>
                    <div class="p-content">
                        <p>Perfil epidemiológico de caries dental</p>                                             
                    </div>
                    
                    <div id="revisa" class="divisionDetalles">
                        
							<div id="revisaForm" style="color:#0000FF" class="divisionDetalles">
							
								<div class="divisionDetalles">
									<p>
									Nombre completo del paciente:
									</p>								
									<p><?php 
										echo $nombreNino." ".$apellidoPNino." ".$apellidoMNino;
										?> 
									</p>
									
								</div>
			
						  		<div class="divisionDetalles">
						  		 <table cellspacing="15" id="incisivo"  width="100%">
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>	
						  			<tr>
						  				<td> Incisivo frontal superior primero </td>
						  				<td id="IFSP" onclick="myFunction('<?php echo $dientes[1];?>', 'incisivo','1' )">  <?php echo $dientes[1];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo frontal superior segundo </td>
						  				<td>  <?php echo $dientes[2];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo frontal inferior primero </td>
						  				<td>  <?php echo $dientes[3];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo frontal inferior segundo</td>
						  				<td>  <?php echo $dientes[4];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>  Incisivo lateral superior primero </td>
						  				<td>  <?php echo $dientes[5];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo lateral superior segundo</td>
						  				<td>  <?php echo $dientes[6];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo lateral inferior primero </td>
						  				<td>  <?php echo $dientes[7];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo lateral inferior sengudo</td>
						  				<td>  <?php echo $dientes[8];?> </td>
						  			</tr>
						  			
						  		 </table>
						  	   </div>
						  	<div class="divisionDetalles">		
						  		<table cellspacing="15" id="canino"  width="100%">
						  			
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>
						  			
						  			<tr>
						  				<td> Canino superior primero </td>
						  				<td>  <?php echo $dientes[9];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Canino superior segundo </td>
						  				<td>  <?php echo $dientes[10];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Canino inferior segundo</td>
						  				<td>  <?php echo $dientes[11];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Canino inferior segundo</td>
						  				<td>  <?php echo $dientes[12];?> </td>
						  			</tr>
						  			
						  		</table>
						  	</div>	
						  	
						  	<div class="divisionDetalles">	
						  		<table cellspacing="15" id="premolar"  width="100%">
						  		
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer premolar superior primero </td>
						  				<td>  <?php echo $dientes[13];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer premolar superior segundo</td>
						  				<td>  <?php echo $dientes[14];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer premolar inferior primero </td>
						  				<td>  <?php echo $dientes[15];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer premolar inferior segundo</td>
						  				<td>  <?php echo $dientes[16];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar superior primero </td>
						  				<td>  <?php echo $dientes[17];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar superior segundo</td>
						  				<td>  <?php echo $dientes[18];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar inferior primero </td>
						  				<td>  <?php echo $dientes[19];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar inferior segundo</td>
						  				<td>  <?php echo $dientes[20];?> </td>
						  			</tr>
						  			
						  		</table>
						  	</div>	
						  	
						  	<div class="divisionDetalles"> 
						  		<table cellspacing="15" id="molar"  width="100%">
						  			
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer molar superior primero </td>
						  				<td>  <?php echo $dientes[21];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer molar superior segundo</td>
						  				<td>  <?php echo $dientes[22];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer molar inferior primero </td>
						  				<td>  <?php echo $dientes[23];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer molar inferior segundo</td>
						  				<td>  <?php echo $dientes[24];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar superior primero </td>
						  				<td>  <?php echo $dientes[25];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar superior segundo</td>
						  				<td>  <?php echo $dientes[26];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar inferior primero </td>
						  				<td>  <?php echo $dientes[27];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar inferior segundo</td>
						  				<td>  <?php echo $dientes[28];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar superior primero </td>
						  				<td>  <?php echo $dientes[29];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar superior segundo</td>
						  				<td>  <?php echo $dientes[30];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar inferior primero </td>
						  				<td>  <?php echo $dientes[31];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar inferior segundo</td>
						  				<td>  <?php echo $dientes[32];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Extra</td>
						  				<td>  <?php echo $dientes[33];?> </td>
						  			</tr>  			  			  			
						  		</table>
						  	</div>	
						  							  					
						</div>																	                       
                    </div>
                  
                  
                </div>                                              
                <!-- Homepage Teasers End -->
    	  	<img src="../images/codigos.png" alt="Códigos de caries" class="left">
                  
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