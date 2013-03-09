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

if(isset($_GET['arregloCuadrante']) && isset($_GET['posicion']) && isset($_GET['identificador'])){
	switch($_GET['arregloCuadrante']) {
		
		case 1:
			if($_SESSION['primerCuadrante'][$_GET['posicion']] == -1) {
				$_SESSION['primerCuadrante'][$_GET['posicion']] = 0;
				echo "-".$_SESSION['primerCuadrante'][$_GET['posicion']];
			}
			else { 
				$_SESSION['primerCuadrante'][$_GET['posicion']] =-1;
				echo $_SESSION['primerCuadrante'][$_GET['posicion']];
			}
			echo $_GET['identificador'];
			
		break;
		
		case 2:
			if($_SESSION['segundoCuadrante'][$_GET['posicion']] == -1) {
				$_SESSION['segundoCuadrante'][$_GET['posicion']] = 0;
				echo "-".$_SESSION['segundoCuadrante'][$_GET['posicion']];
					
			}
			else {
				$_SESSION['segundoCuadrante'][$_GET['posicion']] =-1;
				echo $_SESSION['segundoCuadrante'][$_GET['posicion']];					
			}
			
			echo $_GET['identificador'];
		
		break;	
		
		case 3:
			if($_SESSION['tercerCuadrante'][$_GET['posicion']] == -1) {
				$_SESSION['tercerCuadrante'][$_GET['posicion']] = 0;
				echo "-".$_SESSION['tercerCuadrante'][$_GET['posicion']];
			}
			else {
				$_SESSION['tercerCuadrante'][$_GET['posicion']] =-1;
				echo $_SESSION['tercerCuadrante'][$_GET['posicion']];
			}			
			
			echo $_GET['identificador'];
				
		break;
		
		case 4:
			if($_SESSION['cuartoCuadrante'][$_GET['posicion']] == -1) {
				$_SESSION['cuartoCuadrante'][$_GET['posicion']] = 0;
				echo "-".$_SESSION['cuartoCuadrante'][$_GET['posicion']];
			}
			else {
				$_SESSION['cuartoCuadrante'][$_GET['posicion']] =-1;
				echo $_SESSION['cuartoCuadrante'][$_GET['posicion']];
			}
			
			//echo $_SESSION['cuartoCuadrante'][$_GET['posicion']];
			echo $_GET['identificador'];
			
		break;
	}
	//echo "";	
}
else {
	
	if(isset($_POST['detalles'])) {
		//echo "LOL";
		
		//Primero hay que crear la exploracion dental
		$mysqli = new mysqli("localhost", "monty", "holygrail", "newbasedientes");
		
		$querys_ok = true; //Servira de centinela
		
		//Obtenemos la cedula y el idConsultorio del dentista
		
		$query = @mysql_query( "SELECT * FROM Dentista WHERE Usuario='".$_SESSION["uid"]."'");
		$dentista = @mysql_fetch_object($query);
		
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio='".$_SESSION['idNino']."'");
		$nino= @mysql_fetch_object($query);
		//Fecha
		$date_array = getdate();
		$formated_date = $date_array['mday'] . "/";
		$formated_date .= $date_array['mon'] . "/";
		$formated_date .= $date_array['year'];
		
		mysql_query('LOCK TABLES ExploracionDental,Dentadura,CuadranteI,CuadranteII,CuadranteIII,CuadranteIV'); //Cerrar las transacciones
		
		$mysqli->query("INSERT INTO ExploracionDental (FechaRevision,Dentista_Cedula,Dentista_Consultorio_idConsultorio)
				VALUES ('$formated_date','$dentista->Cedula',$dentista->Consultorio_idConsultorio)") ? null : $querys_ok=false;
		
		$query = @mysql_query( "SELECT * FROM ExploracionDental ORDER BY idExploracionDental DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$expoDental = @mysql_fetch_object($query);		
		
		//Se crea despues cada cuadrante
				
		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['primerCuadrante'][$j];
		
		//echo "INSERT INTO CuadranteI (`11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,`51`,`52`,`53`,`54`,`55`,`Extra`)
		//		VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])";
		
		$mysqli->query("INSERT INTO CuadranteI (`11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,`51`,`52`,`53`,`54`,`55`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;

		$query = @mysql_query( "SELECT * FROM CuadranteI ORDER BY idCuadranteI DESC LIMIT 1"); 
		$c1 = @mysql_fetch_object($query);

		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['segundoCuadrante'][$j];
		
		$mysqli->query("INSERT INTO CuadranteII (`21`,`22`,`23`,`24`,`25`,`26`,`27`,`28`,`61`,`62`,`63`,`64`,`65`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;
		
		$query = @mysql_query( "SELECT * FROM CuadranteII ORDER BY idCuadranteII DESC LIMIT 1");
		$c2 = @mysql_fetch_object($query);
		
		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['tercerCuadrante'][$j];
		
		$mysqli->query("INSERT INTO CuadranteIII (`31`,`32`,`33`,`34`,`35`,`36`,`37`,`38`,`71`,`72`,`73`,`74`,`75`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;
		
		$query = @mysql_query( "SELECT * FROM CuadranteIII ORDER BY idCuadranteIII DESC LIMIT 1");
		$c3 = @mysql_fetch_object($query);		

		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['cuartoCuadrante'][$j];
		
		$mysqli->query("INSERT INTO CuadranteIV (`41`,`42`,`43`,`44`,`45`,`46`,`47`,`48`,`81`,`82`,`83`,`84`,`85`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;
		
		$query = @mysql_query( "SELECT * FROM CuadranteIV ORDER BY idCuadranteIV DESC LIMIT 1");
		$c4 = @mysql_fetch_object($query);
		
		//Finalmente se crea la dentadura
	
						
		$mysqli->query("INSERT INTO Dentadura (ExploracionDental_idExploracionDental, ExploracionDental_Dentista_Cedula,ExploracionDental_Dentista_Consultorio_idConsultorio
				,Ninio_idNinio,Ninio_Padre_idPadre,CuadranteI_idCuadranteI,CuadranteII_idCuadranteII,CuadranteIII_idCuadranteIII,CuadranteIV_idCuadranteIV)
				VALUES ($expoDental->idExploracionDental,'$dentista->Cedula',$dentista->Consultorio_idConsultorio,$nino->idNinio,$nino->Padre_idPadre
					,$c1->idCuadranteI,$c2->idCuadranteII,$c3->idCuadranteIII,$c4->idCuadranteIV)") ? null : $querys_ok=false;
		
		$query = @mysql_query( "SELECT * FROM Dentadura ORDER BY idDentadura DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$idDentadura = @mysql_fetch_object($query);
		$mysqli->query("UPDATE Ninio SET UltimaRevision=$idDentadura->idDentadura WHERE idNinio=$nino->idNinio ");
		
		mysql_query('UNLOCK TABLES');
		//$querys_ok=false;//Borrar
		$querys_ok ? $mysqli->commit() : $mysqli->rollback();
		$mysqli->close();
		
		unset($_SESSION['idNino']);
		unset($_SESSION['primerCuadrante']);
		unset($_SESSION['segundoCuadrante']);
		unset($_SESSION['tercerCuadrante']);
		unset($_SESSION['cuartoCuadrante']);
		print '<script type="text/javascript">';
		print 'alert("Cambios guardados")';
		print '</script>';
		header('refresh:0;URL=../principales/mainDentista2.php');
		
	}
	
	else {
	
	//Se crean los cuadrantes.
		for ($j=0; $j<15; $j++ )
			$primerCuadrante[$j] = -1; //Valor default de los dientes
		$_SESSION['primerCuadrante']=$primerCuadrante; //Guardo el array en la sesion
		
		for ($j=0; $j<15; $j++ )
			$segundoCuadrante[$j] = -1; //Valor default de los dientes
		$_SESSION['segundoCuadrante']=$segundoCuadrante; //Guardo el array en la sesion
		
		for ($j=0; $j<15; $j++ )
			$tercerCuadrante[$j] = -1; //Valor default de los dientes
		$_SESSION['tercerCuadrante']=$tercerCuadrante; //Guardo el array en la sesion
		
		for ($j=0; $j<15; $j++ )
			$cuartoCuadrante[$j] = -1; //Valor default de los dientes
		$_SESSION['cuartoCuadrante']=$cuartoCuadrante; //Guardo el array en la sesion
		
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
		$existe= @mysql_fetch_object($query);
						
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
	
<title>Registro dentadura</title>
 <!-- JavaScript -->
  		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
  		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>			
	    <script type="text/javascript" src="../js/superfish.js"></script>
	    <script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
	    <script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
	    <script type="text/javascript" src="../js/jquery.prettySociable.js"></script>
	    <script type="text/javascript" src="../js/jquery.validate.min.js"></script>
	    <script type="text/javascript" src="../js/main.js"></script>
	    <script  type="text/javascript" src="../js/registroDental.js"></script>  
	    
	 
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
                        <p>Perfil epidemiol�gico de caries dental</p>                                             
                    </div>
                    
                    <div id="revisa" class="divisionDetalles">
                    
                    	<form id="detallesSubmit" name="guardar" action="registroDientes.php" method="post">
                  			
                        
							<div id="revisaForm" style="color:#0000FF" class="divisionDetalles">
							
								<div class="divisionDetalles">
									<p>
										Haz click en los dientes que se encuentren presentes.
									</p>
									<p>
									Nombre completo del paciente:
									</p>								
									<p><?php 
										echo $nombreNino." ".$apellidoPNino." ".$apellidoMNino;
										?> 
									</p>
									
								</div>
			
						  		<div class="divisionDetalles">
						  		<p>
						  			Primer cuadrante
						  		</p>
						  		
						  		 <table cellspacing="15" id="incisivo" width="100%">
						  			<tr>
						  				<th> C�digo </th>
						  				<th> Estado </th>  				
						  			</tr>	
						  			<tr>
						  				<td> 11 </td>
						  				<td id="11" class="noPresente" onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][1];?>', '11','1','1')"></td>						  				
						  			</tr>
						  			
						  			<tr>
						  				<td> 12 </td>
						  				<td id="12" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][2];?>', '12','1','2')">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 13 </td>
						  				<td id="13" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][3];?>', '13','1','3')">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 14 </td>
						  				<td id="14" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][4];?>', '14','1','4')">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>  15 </td>
						  				<td id="15" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][5];?>', '15','1','5')">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 16 </td>
						  				<td id="16" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][6];?>', '16','1','6')">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 17 </td>
						  				<td id="17" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][7];?>', '17','1','7')">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 18</td>
						  				<td id="18" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][8];?>', '18','1','8')">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 51</td>
						  				<td id="51" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][9];?>', '51','1','9')"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 52</td>
						  				<td id="52" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][10];?>', '52','1','10')">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 53</td>
						  				<td id="53" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][11];?>', '53','1','11')">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 54</td>
						  				<td id="54" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][12];?>', '54','1','12')">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 55</td>
						  				<td id="55" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][13];?>', '55','1','13')">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>Dientes extras</td>
						  				<td id="59" style="visibility:hidden" class="noPresente"  onclick="myFunction('<?php echo $_SESSION['primerCuadrante'][14];?>', '59','1','14')">  </td>
						  			</tr>	
						  			
						  		 </table>
						  	   </div>
						  	   
						  	<div class="divisionDetalles">
						  		<p>
						  			Segundo cuadrante
						  		</p>
						  				
						  		<table cellspacing="15" id="canino"  width="100%">
						  			
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>
						  			
						  			<tr>
						  				<td> 21 </td>
						  				<td id="21" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][1];?>', '21','2','1' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>22 </td>
						  				<td id="22" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][2];?>', '22','2','2' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 23</td>
						  				<td id="23" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][3];?>', '23','2','3' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 24</td>
						  				<td id="24" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][4];?>', '24','2','4' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 25</td>
						  				<td id="25" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][5];?>', '25','2','5' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 26</td>
						  				<td id="26" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][6];?>', '26','2','6' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 27</td>
						  				<td id="27" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][7];?>', '27','2','7' )">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 28</td>
						  				<td id="28" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][8];?>', '28','2','8' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 61</td>
						  				<td id="61" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][9];?>', '61','2','9' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 62</td>
						  				<td id="62" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][10];?>', '62','2','10' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 63</td>
						  				<td id="63" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][11];?>', '63','2','11' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 64</td>
						  				<td id="64" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][12];?>', '64','2','12' )">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>65</td>
						  				<td id="65" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][13];?>', '65','2','13' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>Dientes extras</td>
						  				<td id="69" class="noPresente" onclick="myFunction('<?php echo $_SESSION['segundoCuadrante'][14];?>', '69','2','14' )"> </td>
						  			</tr>	
						  		
						  		</table>
						  	</div>	
						  	
						  	<div class="divisionDetalles">	
						  	
						  		<p>
						  			Tercer cuadrante
						  		</p>
						  	
						  		<table cellspacing="15" id="premolar"  width="100%">
						  		
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>
						  			
						  			<tr>
						  				<td> 31 </td>
						  				<td id="31" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][1];?>', '31','3','1' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 32</td>
						  				<td id="32" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][2];?>', '32','3','2' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 33 </td>
						  				<td id="33" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][3];?>', '33','3','3' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 34</td>
						  				<td id="34" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][4];?>', '34','3','4' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 35 </td>
						  				<td id="35" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][5];?>', '35','3','5' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 36</td>
						  				<td id="36" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][6];?>', '36','3','6' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 37 </td>
						  				<td id="37" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][7];?>', '37','3','7' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 38</td>
						  				<td id="38" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][8];?>', '38','3','8' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 71</td>
						  				<td id="71" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][9];?>', '71','3','9' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 72 </td>
						  				<td id="72" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][10];?>', '72','3','10' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 73</td>
						  				<td id="73" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][11];?>', '73','3','11' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 74</td>
						  				<td id="74" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][12];?>', '74','3','12' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>75</td>
						  				<td id="75" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][13];?>', '75','3','13' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td>Dientes extras</td>
						  				<td id="79" class="noPresente" onclick="myFunction('<?php echo $_SESSION['tercerCuadrante'][14];?>', '79','3','14' )"> </td>
						  			</tr>					  									  	
						  			
						  		</table>
						  	</div>	
						  	
						  	<div class="divisionDetalles"> 
						  	
						  		<p>
						  			Cuarto cuadrante
						  		</p>
						  		<table cellspacing="15" id="molar"  width="100%">
						  			
						  			<tr>
						  				<th> Nombre </th>
						  				<th> Estado </th>  				
						  			</tr>
						  			
						  			<tr>
						  				<td> 41 </td>
						  				<td id="41" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][1];?>', '41','4','1' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 42</td>
						  				<td id="42" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][2];?>', '42','4','2' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 43 </td>
						  				<td id="43" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][3];?>', '43','4','3' )">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 44</td>
						  				<td id="44" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][4];?>', '44','4','4' )">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 45 </td>
						  				<td id="45" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][5];?>', '45','4','5' )">   </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 46</td>
						  				<td id="46" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][6];?>', '46','4','6' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 47 </td>
						  				<td id="47" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][7];?>', '47','4','7' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 48</td>
						  				<td id="48" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][8];?>', '48','4','8' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 81 </td>
						  				<td id="81" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][9];?>', '81','4','9' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 82</td>
						  				<td id="82" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][10];?>', '82','4','10' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 83 </td>
						  				<td id="83" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][11];?>', '83','4','11' )">  </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 84</td>
						  				<td id="84" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][12];?>', '84','4','12' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> 85</td>
						  				<td id="85" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][13];?>', '85','4','13' )"> </td>
						  			</tr>
						  			
						  			<tr>
						  				<td> Extra</td>
						  				<td id="89" class="noPresente" onclick="myFunction('<?php echo $_SESSION['cuartoCuadrante'][14];?>', '89','4','14' )"> </td>
						  			</tr>  			  			  			
						  		</table>
						  								  		
						  	</div>	
						  	<input type="submit" value="Guardar dientes presentes">
                  			<input type="hidden" name="detalles" value="yes">	
                  				</form>					  		 			
						</div>	
																				                       
                    </div>
                  
                  
                  
                </div>                                              
                <!-- Homepage Teasers End -->
    	  	<img src="../images/codigoPresentes.png" alt="C�digos de caries" class="left">
                  
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
                    <li> <a href="../registros/regNinio.php">Registrar paciente</a> </li>
                    <li> <a href="construccion.html">Consulta historia dental</a> </li>                    
                    <li> <a href="../consulta/revisionTrimestral.php">Revisi�n trimestral</a> </li>
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