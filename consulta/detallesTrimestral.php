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

if(isset($_GET['posicion']) && isset($_GET['nuevoValor'])) { //Llamada mediante AJAX
	$_SESSION['dientes'][$_GET['posicion']] = $_GET['nuevoValor']; //Posicion empieza en 1
	echo ""; //Mandamos nada al handler de la funcion de AJAX
}
else {	

	if(isset($_POST['detalles'])) {
		
		/*if(isset($_SESSION['dientes'])) {			
			echo $_SESSION['dientes'][1];
			echo $_SESSION['dientes'][2];
		}*/
		
		$mysqli = new mysqli("localhost", "monty", "holygrail", "newbasedientes");
		//$mysqli->autocommit(FALSE);
		
		$querys_ok = true; //Servira de centinela
		
		//Obtenemos la cedula y el idConsultorio del dentista
		
		$query = @mysql_query( "SELECT * FROM Dentista WHERE Usuario='".$_SESSION["uid"]."'");
		$dentista = @mysql_fetch_object($query);
		
		//Obtenemos el padre del nino
		//echo count($_SESSION['dientes']);
		
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio='".$_SESSION['idNino']."'");
		$nino= @mysql_fetch_object($query);
		//echo $nino->idNino;
		//Fecha
		$date_array = getdate();
		$formated_date = $date_array['mday'] . "/";
		$formated_date .= $date_array['mon'] . "/";
		$formated_date .= $date_array['year'];	
		
		//Construccion del arreglo de datos
		for ($j=0; $j<35; $j++ )
			$a[$j] = $_SESSION['dientes'][$j]; 
		
		mysql_query('LOCK TABLES exploraciondental'); //Cerrar las transacciones
		
		$mysqli->query("INSERT INTO exploraciondental (FechaRevision,Dentista_Cedula,Dentista_Consultorio_idConsultorio) 
				VALUES ('$formated_date','$dentista->Cedula',$dentista->Consultorio_idConsultorio)") ? null : $querys_ok=false;
		
		$query = @mysql_query( "SELECT * FROM exploraciondental ORDER BY idExploracionDental DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$expoDental = @mysql_fetch_object($query);
		//echo $expoDentalObjeto->idExploracionDental;
		
		/*echo "INSERT INTO dentadura (InFS1,InFS2,InFB1,InFB2,InLS1,InLS2,InLB1,InLB2,CS1,CS2,CB1,CB2,PPS1,PPS2,PPB1,PPB2,SPS1,SPS2,
				SPB1,SPB2,MPS1,MPS2,MPB1,MPB2,MSS1,MSS2,MSB1,MSB2,MTS1,MTS2,MTB1,MTB2,EXTRA,ExploracionDental_idExploracionDental,
					ExploracionDental_Dentista_Cedula,ExploracionDental_Dentista_Consultorio_idConsultorio,Ninio_idNinio,Ninio_Padre_idPadre) 
				
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14],$a[15],$a[16],$a[17],$a[18],$a[19],$a[20],
					$a[21],$a[22],$a[23],$a[24],$a[25],$a[26],$a[27],$a[28],$a[29],$a[30],$a[31],$a[32],$a[33],
						$expoDental->idExploracionDental,'$dentista->Cedula',$dentista->Consultorio_idConsultorio,$nino->idNinio,$nino->Padre_idPadre)";*/
				
		$mysqli->query("INSERT INTO dentadura (InFS1,InFS2,InFB1,InFB2,InLS1,InLS2,InLB1,InLB2,CS1,CS2,CB1,CB2,PPS1,PPS2,PPB1,PPB2,SPS1,SPS2,
				SPB1,SPB2,MPS1,MPS2,MPB1,MPB2,MSS1,MSS2,MSB1,MSB2,MTS1,MTS2,MTB1,MTB2,EXTRA,ExploracionDental_idExploracionDental,
					ExploracionDental_Dentista_Cedula,ExploracionDental_Dentista_Consultorio_idConsultorio,Ninio_idNinio,Ninio_Padre_idPadre) 
				
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14],$a[15],$a[16],$a[17],$a[18],$a[19],$a[20],
					$a[21],$a[22],$a[23],$a[24],$a[25],$a[26],$a[27],$a[28],$a[29],$a[30],$a[31],$a[32],$a[33],
						$expoDental->idExploracionDental,'$dentista->Cedula',$dentista->Consultorio_idConsultorio,$nino->idNinio,$nino->Padre_idPadre)") ? null : $querys_ok=false;
						
		$query = @mysql_query( "SELECT * FROM dentadura ORDER BY idDentadura DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$idDentadura = @mysql_fetch_object($query);
		
		//Actualizar ultima de tablas a nino
		$mysqli->query("UPDATE Ninio SET UltimaRevision=$idDentadura->idDentadura WHERE idNinio=$nino->idNinio ");
		
		mysql_query('UNLOCK TABLES');
		//$querys_ok=false;//Borrar
		$querys_ok ? $mysqli->commit() : $mysqli->rollback();
		$mysqli->close();
		
		unset($_SESSION['idNino']);
		unset($_SESSION['dientes']);
		print '<script type="text/javascript">';
		print 'alert("Cambios guardados")';
		print '</script>';
		header('Location: ../principales/mainDentista2.php');
		
		
		
	} else {
		
		//Recuperar el objeto nino
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
		$existe= @mysql_fetch_object($query);
		if($existe->UltimaRevision == 0) { //Generar una nueva revision			
			for ($j=0; $j<35; $j++ ) 
				$dientes[$j] = 0; //Valor default de los dientes
			$_SESSION['dientes']=$dientes; //Guardo el array en la sesion
			
			
		} else { //Abrir la mas reciente revision del nino
			$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
			$existe= @mysql_fetch_object($query);		
			$query2 = @mysql_query("SELECT * FROM Dentadura WHERE idDentadura=".mysql_real_escape_string($existe->UltimaRevision)."");
			$dentadura = @mysql_fetch_array($query2);
			//$dientes[] = (array) $dentadura;	//A partir de 1 es indice de dientes
			for ($j=0; $j<35; $j++ )
				$dientes[$j] = $dentadura[$j]; //Valor default de los dientes
			
			$_SESSION['dientes']=$dientes;
		}
	
		$nombreNino = $existe->Nombre;
		$apellidoPNino = $existe->ApellidoPaterno;
		$apellidoMNino = $existe->ApellidoMaterno;
	}
}
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
                	<!--  Antiguo valor: <input value="" id="antiguoValor" type="text" ><br /> -->
                	Nuevo valor: <input type="text" value="" id="nuevoValor"> <br />         
                </div>
                <!-- Homepage Welcome Text -->
                <div id="homepage-post">
                <h1 class="p-title" ><a href="#">Bienvenido al sitio de la Cartilla de Salud Bucal Digital</a></h1>
                    <div class="p-content">
                        <p>Perfil epidemiológico de caries dental</p>                                             
                    </div>
                    
                    <div id="revisa" class="divisionDetalles">
                    
                    	<form id="detallesSubmit" name="guardar" action="detallesTrimestral.php" method="post">
                  			
                        
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
						  		
						  		 <table cellspacing="15" id="incisivo" width="100%">
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>	
						  			<tr>
						  				<td> Incisivo frontal superior primero </td>
						  				<td id="IFSP" onclick="myFunction('<?php echo $dientes[1];?>', 'incisivo','1','1' )">  <?php echo $dientes[1];?> </td>						  				
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo frontal superior segundo </td>
						  				<td id="IFSS" onclick="myFunction('<?php echo $dientes[2];?>', 'incisivo','2','2' )">  <?php echo $dientes[2];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo frontal inferior primero </td>
						  				<td id="IFIP" onclick="myFunction('<?php echo $dientes[3];?>', 'incisivo','3','3' )">  <?php echo $dientes[3];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo frontal inferior segundo</td>
						  				<td id="IFIS" onclick="myFunction('<?php echo $dientes[4];?>', 'incisivo','4','4' )">  <?php echo $dientes[4];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>  Incisivo lateral superior primero </td>
						  				<td id="ILSP" onclick="myFunction('<?php echo $dientes[5];?>', 'incisivo','5','5' )">  <?php echo $dientes[5];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo lateral superior segundo</td>
						  				<td id="ILSS" onclick="myFunction('<?php echo $dientes[6];?>', 'incisivo','6','6' )">  <?php echo $dientes[6];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo lateral inferior primero </td>
						  				<td id="ILIP" onclick="myFunction('<?php echo $dientes[7];?>', 'incisivo','7','7' )">  <?php echo $dientes[7];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Incisivo lateral inferior sengudo</td>
						  				<td id="ILIS" onclick="myFunction('<?php echo $dientes[8];?>', 'incisivo','8','8' )">  <?php echo $dientes[8];?> </td>
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
						  				<td id="CSP" onclick="myFunction('<?php echo $dientes[9];?>', 'canino','1','9' )">  <?php echo $dientes[9];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Canino superior segundo </td>
						  				<td id="CSS" onclick="myFunction('<?php echo $dientes[10];?>', 'canino','2','10' )">  <?php echo $dientes[10];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Canino inferior primero</td>
						  				<td id="CIP" onclick="myFunction('<?php echo $dientes[11];?>', 'canino','3','11' )">  <?php echo $dientes[11];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Canino inferior segundo</td>
						  				<td id="CIS" onclick="myFunction('<?php echo $dientes[12];?>', 'canino','4','12' )">  <?php echo $dientes[12];?> </td>
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
						  				<td id="PPMSP" onclick="myFunction('<?php echo $dientes[13];?>', 'premolar','1','13' )">  <?php echo $dientes[13];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer premolar superior segundo</td>
						  				<td id="PPMSS" onclick="myFunction('<?php echo $dientes[14];?>', 'premolar','2','14' )">  <?php echo $dientes[14];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer premolar inferior primero </td>
						  				<td id="PPMIP" onclick="myFunction('<?php echo $dientes[15];?>', 'premolar','3','15' )">  <?php echo $dientes[15];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer premolar inferior segundo</td>
						  				<td id="PPIS" onclick="myFunction('<?php echo $dientes[16];?>', 'premolar','4','16' )">  <?php echo $dientes[16];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar superior primero </td>
						  				<td id="SPMSP" onclick="myFunction('<?php echo $dientes[17];?>', 'premolar','5','17' )">  <?php echo $dientes[17];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar superior segundo</td>
						  				<td id="SPMSS" onclick="myFunction('<?php echo $dientes[18];?>', 'premolar','6','18' )">  <?php echo $dientes[18];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar inferior primero </td>
						  				<td id="SPMIP" onclick="myFunction('<?php echo $dientes[19];?>', 'premolar','7','19' )">  <?php echo $dientes[19];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo premolar inferior segundo</td>
						  				<td id="SPMIS" onclick="myFunction('<?php echo $dientes[20];?>', 'premolar','8','20' )">  <?php echo $dientes[20];?> </td>
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
						  				<td id="PMSP" onclick="myFunction('<?php echo $dientes[21];?>', 'molar','1','21' )">  <?php echo $dientes[21];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer molar superior segundo</td>
						  				<td id="PMSS" onclick="myFunction('<?php echo $dientes[22];?>', 'molar','2','22' )">  <?php echo $dientes[22];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer molar inferior primero </td>
						  				<td id="PMIP" onclick="myFunction('<?php echo $dientes[23];?>', 'molar','3','23' )">  <?php echo $dientes[23];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Primer molar inferior segundo</td>
						  				<td id="PMIS" onclick="myFunction('<?php echo $dientes[24];?>', 'molar','4','24' )">  <?php echo $dientes[24];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar superior primero </td>
						  				<td id="SMSP" onclick="myFunction('<?php echo $dientes[25];?>', 'molar','5','25' )">  <?php echo $dientes[25];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar superior segundo</td>
						  				<td id="SMSS" onclick="myFunction('<?php echo $dientes[26];?>', 'molar','6','26' )">  <?php echo $dientes[26];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar inferior primero </td>
						  				<td id="SMIP" onclick="myFunction('<?php echo $dientes[27];?>', 'molar','7','27' )">  <?php echo $dientes[27];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Segundo molar inferior segundo</td>
						  				<td id="SMIS" onclick="myFunction('<?php echo $dientes[28];?>', 'molar','8','28' )">  <?php echo $dientes[28];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar superior primero </td>
						  				<td id="TMSP" onclick="myFunction('<?php echo $dientes[29];?>', 'molar','9','29' )">  <?php echo $dientes[29];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar superior segundo</td>
						  				<td id="TMSS" onclick="myFunction('<?php echo $dientes[30];?>', 'molar','10','30' )">  <?php echo $dientes[30];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar inferior primero </td>
						  				<td id="TMIP" onclick="myFunction('<?php echo $dientes[31];?>', 'molar','11','31' )">  <?php echo $dientes[31];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Tercer molar inferior segundo</td>
						  				<td id="TMIS" onclick="myFunction('<?php echo $dientes[32];?>', 'molar','12','32' )">  <?php echo $dientes[32];?> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Extra</td>
						  				<td id="extra" onclick="myFunction('<?php echo $dientes[33];?>', 'molar','13','33' )">  <?php echo $dientes[33];?> </td>
						  			</tr>  			  			  			
						  		</table>
						  								  		
						  	</div>	
						     <!--  <div  class="divisionDetalles">
                  				<form id="detallesSubmit" name="guardar" action="detallesTrimestral.php" method="post">
                  						<input type="submit" value="Guardar estado de la dentadura">
                  						<input type="hidden" name="detalles" value="yes">	
                  				</form>
                  			</div>	-->
                  			<input type="submit" value="Guardar estado de la dentadura">
                  			<input type="hidden" name="detalles" value="yes">	
                  				</form>					  		 			
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