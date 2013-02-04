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

if(isset($_GET['arregloCuadrante']) && isset($_GET['posicion']) && isset($_GET['identificador']) && isset($_GET['nuevoValor']) ){
	switch($_GET['arregloCuadrante']) {
		
		case 1:			
				$_SESSION['primerCuadrante'][$_GET['posicion']] = $_GET['nuevoValor'];
				echo $_SESSION['primerCuadrante'][$_GET['posicion']];										
				echo $_GET['identificador'];			
		break;
		
		case 2:			
				$_SESSION['segundoCuadrante'][$_GET['posicion']] = $_GET['nuevoValor'];
				echo "-".$_SESSION['segundoCuadrante'][$_GET['posicion']];
				echo $_GET['identificador'];		
		break;	
		
		case 3:
				$_SESSION['tercerCuadrante'][$_GET['posicion']] = $_GET['nuevoValor'];
				echo "-".$_SESSION['tercerCuadrante'][$_GET['posicion']];
				echo $_GET['identificador'];				
		break;
		
		case 4:
				$_SESSION['cuartoCuadrante'][$_GET['posicion']] =$_GET['nuevoValor'];
				echo $_SESSION['cuartoCuadrante'][$_GET['posicion']];
				echo $_GET['identificador'];		
		break;
	}	
}
else {	

	if(isset($_POST['detalles'])) {
		
		$mysqli = new mysqli("localhost", "monty", "holygrail", "newbasedientes");
		
		$querys_ok = true; //Servira de centinela
		
		//Obtenemos la cedula y el idConsultorio del dentista
		
		$query = @mysql_query( "SELECT * FROM Dentista WHERE Usuario='".$_SESSION["uid"]."'");
		$dentista = @mysql_fetch_object($query);
		
		//Obtenemos el padre del nino
	
		
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio='".$_SESSION['idNino']."'");
		$nino= @mysql_fetch_object($query);
		//Fecha
		$date_array = getdate();
		$formated_date = $date_array['mday'] . "/";
		$formated_date .= $date_array['mon'] . "/";
		$formated_date .= $date_array['year'];	
		
		//Construccion del arreglo de datos
		for ($j=0; $j<35; $j++ )
			$a[$j] = $_SESSION['dientes'][$j]; 
		
		mysql_query('LOCK TABLES exploraciondental'); //Cerrar las transacciones
		
		$mysqli->query("INSERT INTO ExploracionDental (FechaRevision,Dentista_Cedula,Dentista_Consultorio_idConsultorio) 
				VALUES ('$formated_date','$dentista->Cedula',$dentista->Consultorio_idConsultorio)") ? null : $querys_ok=false;
		
		$query = @mysql_query( "SELECT * FROM ExploracionDental ORDER BY idExploracionDental DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$expoDental = @mysql_fetch_object($query);

		$mysqli->query("INSERT INTO Dentadura (InFS1,InFS2,InFB1,InFB2,InLS1,InLS2,InLB1,InLB2,CS1,CS2,CB1,CB2,PPS1,PPS2,PPB1,PPB2,SPS1,SPS2,
				SPB1,SPB2,MPS1,MPS2,MPB1,MPB2,MSS1,MSS2,MSB1,MSB2,MTS1,MTS2,MTB1,MTB2,EXTRA,ExploracionDental_idExploracionDental,
					ExploracionDental_Dentista_Cedula,ExploracionDental_Dentista_Consultorio_idConsultorio,Ninio_idNinio,Ninio_Padre_idPadre) 
				
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14],$a[15],$a[16],$a[17],$a[18],$a[19],$a[20],
					$a[21],$a[22],$a[23],$a[24],$a[25],$a[26],$a[27],$a[28],$a[29],$a[30],$a[31],$a[32],$a[33],
						$expoDental->idExploracionDental,'$dentista->Cedula',$dentista->Consultorio_idConsultorio,$nino->idNinio,$nino->Padre_idPadre)") ? null : $querys_ok=false;
						
		$query = @mysql_query( "SELECT * FROM Dentadura ORDER BY idDentadura DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$idDentadura = @mysql_fetch_object($query);
		
		//Actualizar ultima de tablas a nino
		//echo "UPDATE Ninio SET UltimaRevision=$idDentadura->idDentadura WHERE idNinio=$nino->idNinio ";
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
		
		//Primero se recuperan los valores de los cuadrantes de la dentadura del ninio
		
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
		$existe= @mysql_fetch_object($query);
		$idDentadura = $existe->UltimaRevision;
		
		$nombreNino = $existe->Nombre;
		$apellidoPNino = $existe->ApellidoPaterno;
		$apellidoMNino = $existe->ApellidoMaterno;
		
		//Se recuperan los cuadrantes de la dentadura
		$query = @mysql_query("SELECT * FROM Dentadura WHERE idDentadura=$idDentadura");
		$ultimaDentadura = @mysql_fetch_object($query);
		
		//Primer Cuadrante
		$idCuadrante1 = $ultimaDentadura->CuadranteI_idCuadranteI;
		$queryCuadr1 = @mysql_query("SELECT * FROM CuadranteI WHERE idCuadranteI=$idCuadrante1");
		$arrayCuadranteI = @mysql_fetch_array($queryCuadr1, MYSQL_NUM);
		$strCuad1="";
		$valor=10;
		//Construccion de la tabla del primer cuadrante										
		$strCuad1.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad1.="<tr>\n";
		$strCuad1.="<th> Código </th>";
		$strCuad1.="<th> Estado </th>";
		$strCuad1.="</tr>\n";
		
		$_SESSION['primerCuadrante'] = $arrayCuadranteI;
		$contadorPosicion =0;
		
		foreach ($arrayCuadranteI as $a) {
			if($a!=-1  && $a !=$idCuadrante1 ) {
				$strCuad1.="<tr>\n";
				$strCuad1 .= "<td>$valor </td> \n";
				$strCuad1 .= "<td id=\"$valor\" onclick=\"myFunction(".$_SESSION['primerCuadrante'][$contadorPosicion].",$valor,1,$contadorPosicion)\" >$a </td> \n";
				$strCuad1.="</tr>\n";
				if($valor==18) //Si me paso de los dientes permantenes, cambio a temporales 
					$valor=50;
				$valor++;
				$contadorPosicion++;
			}
			else
			{
				if($valor==18) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=50;
				$valor++;
				$contadorPosicion++;
					
			}
		}
		$strCuad1.=" </table>";
		
		//Segundo Cuadrante
		$idCuadrante2 = $ultimaDentadura->CuadranteII_idCuadranteII;
		$queryCuadr2 = @mysql_query("SELECT * FROM CuadranteII WHERE idCuadranteII=$idCuadrante2");
		$arrayCuadranteII = @mysql_fetch_array($queryCuadr2, MYSQL_NUM);
		$strCuad2="";
		$valor=20;
		//Construccion de la tabla del primer cuadrante
		$strCuad2.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad2.="<tr>\n";
		$strCuad2.="<th> Código </th>";
		$strCuad2.="<th> Estado </th>";
		$strCuad2.="</tr>\n";
		$_SESSION['segundoCuadrante'] = $arrayCuadranteII;
		$contadorPosicion =0;
		foreach ($arrayCuadranteII as $a) {
			if($a!=-1  && $a !=$idCuadrante2 ) {
				$strCuad2.="<tr>\n";
				$strCuad2 .= "<td>$valor </td> \n";
				$strCuad2 .= "<td onclick=\"myFunction(".$_SESSION['segundoCuadrante'][$contadorPosicion].",$valor,2,$contadorPosicion)\">$a </td> \n";
				$strCuad2.="</tr>\n";
				if($valor==28) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=60;
				$valor++;
				$contadorPosicion++;
			}
			else
			{
				if($valor==28) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=60;
				$valor++;
				$contadorPosicion ++;
			}
		}
		
		$strCuad2.=" </table>";
		
		//Tercer cuadrante
		$idCuadrante3 = $ultimaDentadura->CuadranteIII_idCuadranteIII;
		$queryCuadr3 = @mysql_query("SELECT * FROM CuadranteIII WHERE idCuadranteIII=$idCuadrante3");
		$arrayCuadranteIII = @mysql_fetch_array($queryCuadr3, MYSQL_NUM);
		$strCuad3="";
		$valor=30;
		//Construccion de la tabla del primer cuadrante
		$strCuad3.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad3.="<tr>\n";
		$strCuad3.="<th> Código </th>";
		$strCuad3.="<th> Estado </th>";
		$strCuad3.="</tr>\n";
		$_SESSION['tercerCuadrante'] = $arrayCuadranteIII;
		$contadorPosicion =0;
		foreach ($arrayCuadranteIII as $a) {
			if($a!=-1  && $a !=$idCuadrante3 ) {
				$strCuad3.="<tr>\n";
				$strCuad3 .= "<td>$valor </td> \n";
				$strCuad3 .= "<td onclick=\"myFunction(".$_SESSION['tercerCuadrante'][$contadorPosicion].",$valor,3,$contadorPosicion)\">$a </td> \n";
				$strCuad3.="</tr>\n";
				if($valor==38) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=70;
				$valor++;
				$contadorPosicion ++;
			}
			else
			{
				if($valor==38) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=70;
				$valor++;
				$contadorPosicion++;					
			}
		}
		
		$strCuad3.=" </table>";

		//Cuarto Cuadrante
		$idCuadrante4 = $ultimaDentadura->CuadranteIV_idCuadranteIV;
		$queryCuadr4 = @mysql_query("SELECT * FROM CuadranteIV WHERE idCuadranteIV=$idCuadrante4");
		$arrayCuadranteIV = @mysql_fetch_array($queryCuadr4, MYSQL_NUM);
		$strCuad4="";
		$valor=40;
		//Construccion de la tabla del primer cuadrante
		$strCuad4.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad4.="<tr>\n";
		$strCuad4.="<th> Código </th>";
		$strCuad4.="<th> Estado </th>";
		$strCuad4.="</tr>\n";
		$_SESSION['cuartoCuadrante'] = $arrayCuadranteIV;
		$contadorPosicion=0;
		foreach ($arrayCuadranteIII as $a) {
			if($a!=-1  && $a !=$idCuadrante4 ) {
				$strCuad4.="<tr>\n";
				$strCuad4 .= "<td>$valor </td> \n";
				$strCuad4 .= "<td onclick=\"myFunction(".$_SESSION['cuartoCuadrante'][$contadorPosicion].",$valor,4,$contadorPosicion)\">$a </td> \n";
				$strCuad4.="</tr>\n";
				if($valor==48) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=80;
				$valor++;
				$contadorPosicion++;
			}
			else
			{
				if($valor==48) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=80;
				$valor++;
				$contadorPosicion++;					
			}
		}
		
		$strCuad4.=" </table>";
		
	
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
	    <script  type="text/javascript" src="../js/detallesDientes.js"></script>
	 
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
						  		
						  		
						  	   </div>
						  	<div class="divisionDetalles">	
						  		<p>Primer cuadrante </p>	
						  		<?php echo $strCuad1;?>
						  	</div>	
						  	
						  	<div class="divisionDetalles">
						  		<p>Segundo cuadrante </p>		
						  		<?php echo $strCuad2;?>
						  	</div>	
						  	
						  	<div class="divisionDetalles"> 
						  		<p>Tercer cuadrante </p>	
						  		<?php echo $strCuad3;?>						  								  		
						  	</div>	
						  	
						  	<div class="divisionDetalles"> 
						  		<p>Cuarto cuadrante </p>	
						  		<?php echo $strCuad4;?>						  								  	
						  	</div>	
						   
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