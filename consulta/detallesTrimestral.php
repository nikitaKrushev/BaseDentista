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
	
	if (strlen($_GET['nuevoValor'])==1) {
		switch($_GET['arregloCuadrante']) {
		
			case 1:
				$_SESSION['primerCuadrante'][$_GET['posicion']] = $_GET['nuevoValor'];
				echo $_SESSION['primerCuadrante'][$_GET['posicion']];
				echo $_GET['identificador'];
				break;
		
			case 2:
				$_SESSION['segundoCuadrante'][$_GET['posicion']] = $_GET['nuevoValor'];
				echo $_SESSION['segundoCuadrante'][$_GET['posicion']];
				echo $_GET['identificador'];
				break;
		
			case 3:
				$_SESSION['tercerCuadrante'][$_GET['posicion']] = $_GET['nuevoValor'];
				echo $_SESSION['tercerCuadrante'][$_GET['posicion']];
				echo $_GET['identificador'];
				break;
		
			case 4:
				$_SESSION['cuartoCuadrante'][$_GET['posicion']] =$_GET['nuevoValor'];
				echo $_SESSION['cuartoCuadrante'][$_GET['posicion']];
				echo $_GET['identificador'];
				break;
		}
	}
	else 
		echo "Error";
	//echo "ARREGLO: ". $_GET['arregloCuadrante']." POSICION:".$_GET['posicion']." IDENTIFICADOR:".$_GET['identificador']." NUEVOVALOR:".$_GET['nuevoValor'];
	
}
else {	

	if(isset($_POST['detalles'])) {
		//echo "LOLAZO";
		//Primero hay que crear la exploracion dental
		//$mysqli = new mysqli("localhost", "monty", "holygrail", "newbasedientes");
		
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
		
		//mysql_query('LOCK TABLES ExploracionDental,Dentadura,CuadranteI,CuadranteII,CuadranteIII,CuadranteIV'); //Cerrar las transacciones
		
		//*** Start Transaction ***//
		@mysql_query("BEGIN");
		
		/*$mysqli->query("INSERT INTO ExploracionDental (FechaRevision,Dentista_Cedula,Dentista_Consultorio_idConsultorio)
				VALUES ('$formated_date','$dentista->Cedula',$dentista->Consultorio_idConsultorio)") ? null : $querys_ok=false;*/
		$result = @mysql_query("INSERT INTO ExploracionDental (FechaRevision,Dentista_Cedula,Dentista_Consultorio_idConsultorio)
				VALUES ('$formated_date','$dentista->Cedula',$dentista->Consultorio_idConsultorio)");
		
		
		$query = @mysql_query( "SELECT * FROM ExploracionDental ORDER BY idExploracionDental DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$expoDental = @mysql_fetch_object($query);
		
		//Se crea despues cada cuadrante
		
		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['primerCuadrante'][$j];
		
		//echo "INSERT INTO CuadranteI (`11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,`51`,`52`,`53`,`54`,`55`,`Extra`)
		//		VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])";
		
		/*$mysqli->query("INSERT INTO CuadranteI (`11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,`51`,`52`,`53`,`54`,`55`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;*/
		$result2 = @mysql_query("INSERT INTO CuadranteI (`11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,`51`,`52`,`53`,`54`,`55`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])");
		
		
		$query = @mysql_query( "SELECT * FROM CuadranteI ORDER BY idCuadranteI DESC LIMIT 1");
		$c1 = @mysql_fetch_object($query);
		
		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['segundoCuadrante'][$j];
		
		/*$mysqli->query("INSERT INTO CuadranteII (`21`,`22`,`23`,`24`,`25`,`26`,`27`,`28`,`61`,`62`,`63`,`64`,`65`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;*/
		$result3 =@mysql_query("INSERT INTO CuadranteII (`21`,`22`,`23`,`24`,`25`,`26`,`27`,`28`,`61`,`62`,`63`,`64`,`65`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])");		
		
		$query = @mysql_query( "SELECT * FROM CuadranteII ORDER BY idCuadranteII DESC LIMIT 1");
		$c2 = @mysql_fetch_object($query);
		
		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['tercerCuadrante'][$j];
		
		/*$mysqli->query("INSERT INTO CuadranteIII (`31`,`32`,`33`,`34`,`35`,`36`,`37`,`38`,`71`,`72`,`73`,`74`,`75`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;*/
		$result4 = @mysql_query("INSERT INTO CuadranteIII (`31`,`32`,`33`,`34`,`35`,`36`,`37`,`38`,`71`,`72`,`73`,`74`,`75`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])");
		
		$query = @mysql_query( "SELECT * FROM CuadranteIII ORDER BY idCuadranteIII DESC LIMIT 1");
		$c3 = @mysql_fetch_object($query);
		
		for ($j=0; $j<15; $j++ )
			$a[$j] = $_SESSION['cuartoCuadrante'][$j];
		
		/*$mysqli->query("INSERT INTO CuadranteIV (`41`,`42`,`43`,`44`,`45`,`46`,`47`,`48`,`81`,`82`,`83`,`84`,`85`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])") ? null : $querys_ok=false;*/
		$result4 = @mysql_query("INSERT INTO CuadranteIV (`41`,`42`,`43`,`44`,`45`,`46`,`47`,`48`,`81`,`82`,`83`,`84`,`85`,`Extra`)
				VALUES ($a[1],$a[2],$a[3],$a[4],$a[5],$a[6],$a[7],$a[8],$a[9],$a[10],$a[11],$a[12],$a[13],$a[14])");
		
		
		$query = @mysql_query( "SELECT * FROM CuadranteIV ORDER BY idCuadranteIV DESC LIMIT 1");
		$c4 = @mysql_fetch_object($query);
		
		//Finalmente se crea la dentadura
		
		
		/*$mysqli->query("INSERT INTO Dentadura (ExploracionDental_idExploracionDental, ExploracionDental_Dentista_Cedula,ExploracionDental_Dentista_Consultorio_idConsultorio
				,Ninio_idNinio,Ninio_Padre_idPadre,CuadranteI_idCuadranteI,CuadranteII_idCuadranteII,CuadranteIII_idCuadranteIII,CuadranteIV_idCuadranteIV)
				VALUES ($expoDental->idExploracionDental,'$dentista->Cedula',$dentista->Consultorio_idConsultorio,$nino->idNinio,$nino->Padre_idPadre
				,$c1->idCuadranteI,$c2->idCuadranteII,$c3->idCuadranteIII,$c4->idCuadranteIV)") ? null : $querys_ok=false;*/
		$result5 = @mysql_query("INSERT INTO Dentadura (ExploracionDental_idExploracionDental, ExploracionDental_Dentista_Cedula,ExploracionDental_Dentista_Consultorio_idConsultorio
				,Ninio_idNinio,Ninio_Padre_idPadre,CuadranteI_idCuadranteI,CuadranteII_idCuadranteII,CuadranteIII_idCuadranteIII,CuadranteIV_idCuadranteIV)
				VALUES ($expoDental->idExploracionDental,'$dentista->Cedula',$dentista->Consultorio_idConsultorio,$nino->idNinio,$nino->Padre_idPadre
				,$c1->idCuadranteI,$c2->idCuadranteII,$c3->idCuadranteIII,$c4->idCuadranteIV)");
		
		$query = @mysql_query( "SELECT * FROM Dentadura ORDER BY idDentadura DESC LIMIT 1"); //Obtenemos el ultimo lugar
		$idDentadura = @mysql_fetch_object($query);
		//$mysqli->query("UPDATE Ninio SET UltimaRevision=$idDentadura->idDentadura WHERE idNinio=$nino->idNinio ");
		$result6 = @mysql_query("UPDATE Ninio SET UltimaRevision=$idDentadura->idDentadura WHERE idNinio=$nino->idNinio");
		//echo $result."RE ".$result1."RE1 ".$result2."RE2 ".$result3."RE3 ".$result4."RE4 ".$result5."RE5 ".$result6."RE6 ";
		if($result && $result2 && $result3 && $result4 && $result5 && $result6) {
			mysql_query("COMMIT");
			//echo "SAVE";
		}
		else {
			mysql_query("ROLLBACK");
			print '<script type="text/javascript">';
			print 'alert("Hubo un error en la base de datos")';
			print '</script>';
			header('refresh:0;URL=../principales/mainDentista2.php');
			exit;
		}
		//mysql_query('UNLOCK TABLES');
		//$querys_ok=false;//Borrar
		//$querys_ok ? $mysqli->commit() : $mysqli->rollback();
		//$mysqli->close();
		
		unset($_SESSION['idNino']);
		unset($_SESSION['primerCuadrante']);
		unset($_SESSION['segundoCuadrante']);
		unset($_SESSION['tercerCuadrante']);
		unset($_SESSION['cuartoCuadrante']);
		print '<script type="text/javascript">';
		print 'alert("Cambios guardados")';
		print '</script>';
		header('refresh:0;URL=../principales/mainDentista2.php');
		exit;
		
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
		//echo "TAMANIO 1: ".count($arrayCuadranteI);
		/*for($i=1; $i<count($arrayCuadranteI); $i++) {
			if($arrayCuadranteI[$i]!=-1)
				echo $i."=".$arrayCuadranteI[$i]." ";
		}
		echo "PRIMER CUADRANTE ";*/
		$strCuad1="";
		$valor=11;
		//Construccion de la tabla del primer cuadrante										
		$strCuad1.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad1.="<tr>\n";
		$strCuad1.="<th> C&oacute;digo </th>";
		$strCuad1.="<th> Estado </th>";
		$strCuad1.="</tr>\n";
		
		$_SESSION['primerCuadrante'] = $arrayCuadranteI;
		$contadorPosicion =1;
		//$d = 0;
		//foreach ($arrayCuadranteI as $a) {
		for($i=1; $i<count($arrayCuadranteI); $i++) {					
			//if($a!=-1  && $a !=$idCuadrante1 ) {
			if($arrayCuadranteI[$i]!=-1){
				$strClase = pintaDientes($arrayCuadranteI[$i]);
				//echo $valor." @ ";
				//echo $strClase;
				$strCuad1.="<tr>\n";
				$strCuad1 .= "<td>$valor </td> \n";
				$strCuad1 .= "<td id=\"$valor\" class=\"$strClase\" onclick=\"myFunction(".$_SESSION['primerCuadrante'][$contadorPosicion].",$valor,1,$contadorPosicion)\" > </td> \n";
				$strCuad1.="</tr>\n";
				if($valor==18) //Si me paso de los dientes permantenes, cambio a temporales 
					$valor=50;
				$valor++;
				$contadorPosicion++;
				//$d++;
			}
			else
			{
				if($valor==18) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=50;
				$valor++;
				$contadorPosicion++;
					
			}
		}	
		//}
		$strCuad1.=" </table>";				
		//echo $d;
		
		//Segundo Cuadrante
		$idCuadrante2 = $ultimaDentadura->CuadranteII_idCuadranteII;
		$queryCuadr2 = @mysql_query("SELECT * FROM CuadranteII WHERE idCuadranteII=$idCuadrante2");
		$arrayCuadranteII = @mysql_fetch_array($queryCuadr2, MYSQL_NUM);
		//echo "TAMANIO 2: ".count($arrayCuadranteII);
		/*for($i=1; $i<count($arrayCuadranteII); $i++) {
			if($arrayCuadranteII[$i]!=-1)
				echo $i."=".$arrayCuadranteII[$i]." ";
		}
		echo "SEGUNDO CUADRANTE ";*/
		$strCuad2="";
		$valor=21;
		//Construccion de la tabla del primer cuadrante
		$strCuad2.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad2.="<tr>\n";
		$strCuad2.="<th> C&oacute;digo </th>";
		$strCuad2.="<th> Estado </th>";
		$strCuad2.="</tr>\n";
		$_SESSION['segundoCuadrante'] = $arrayCuadranteII;
		$contadorPosicion =1;
		//$d = 0;
		//foreach ($arrayCuadranteII as $a) {
		for($i=1; $i<count($arrayCuadranteII); $i++) {
			//if($a!=-1  && $a !=$idCuadrante2 ) {
			if($arrayCuadranteII[$i]!=-1){
				//echo $valor." @ ";
				$strClase = pintaDientes($arrayCuadranteII[$i]);
				$strCuad2.="<tr>\n";
				$strCuad2 .= "<td>$valor </td> \n";
				$strCuad2 .= "<td id=\"$valor\" class=\"$strClase\" onclick=\"myFunction(".$_SESSION['segundoCuadrante'][$contadorPosicion].",$valor,2,$contadorPosicion)\"> </td> \n";
				$strCuad2.="</tr>\n";
				if($valor==28) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=60;
				$valor++;
				$contadorPosicion++;
				//$d++;
			}
			else
			{
				if($valor==28) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=60;
				$valor++;
				$contadorPosicion ++;
			}
		}
		//}
		
		$strCuad2.=" </table>";
		//echo $d;
		//Tercer cuadrante
		$idCuadrante3 = $ultimaDentadura->CuadranteIII_idCuadranteIII;
		$queryCuadr3 = @mysql_query("SELECT * FROM CuadranteIII WHERE idCuadranteIII=$idCuadrante3");
		$arrayCuadranteIII = @mysql_fetch_array($queryCuadr3, MYSQL_NUM);
		//echo "TAMANIO 3: ".count($arrayCuadranteIII);
		/*for($i=1; $i<count($arrayCuadranteIII); $i++) {
			if($arrayCuadranteIII[$i]!=-1)
				echo $i."=".$arrayCuadranteIII[$i]." ";
		}
		echo "TERCER CUADRANTE";*/
		$strCuad3="";
		$valor=31;
		//Construccion de la tabla del primer cuadrante
		$strCuad3.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad3.="<tr>\n";
		$strCuad3.="<th> C&oacute;digo </th>";
		$strCuad3.="<th> Estado </th>";
		$strCuad3.="</tr>\n";
		$_SESSION['tercerCuadrante'] = $arrayCuadranteIII;
		$contadorPosicion =1;
		//$d=0;
		//foreach ($arrayCuadranteIII as $a) {
		for($i=1; $i<count($arrayCuadranteIII); $i++) {
			//if($a!=-1  && $a !=$idCuadrante3 ) {
			if($arrayCuadranteIII[$i]!=-1){
				//echo $valor." @ ";
				$strClase = pintaDientes($arrayCuadranteIII[$i]);
				$strCuad3.="<tr>\n";
				$strCuad3 .= "<td>$valor </td> \n";
				$strCuad3 .= "<td id=\"$valor\" class=\"$strClase\" onclick=\"myFunction(".$_SESSION['tercerCuadrante'][$contadorPosicion].",$valor,3,$contadorPosicion)\"> </td> \n";
				$strCuad3.="</tr>\n";
				if($valor==38) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=70;
				$valor++;
				$contadorPosicion ++;
				//$d++;
			}
			else
			{
				if($valor==38) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=70;
				$valor++;
				$contadorPosicion++;					
			}
		}	
		//}
		
		$strCuad3.=" </table>";
		//echo $d;
		//Cuarto Cuadrante
		$idCuadrante4 = $ultimaDentadura->CuadranteIV_idCuadranteIV;
		$queryCuadr4 = @mysql_query("SELECT * FROM CuadranteIV WHERE idCuadranteIV=$idCuadrante4");
		$arrayCuadranteIV = @mysql_fetch_array($queryCuadr4, MYSQL_NUM);
		//echo "TAMANIO 4: ".count($arrayCuadranteIV);
		/*for($i=1; $i<count($arrayCuadranteIV); $i++) {
			if($arrayCuadranteIV[$i]!=-1)
				echo $i."=".$arrayCuadranteIV[$i]." ";
		}
		echo "CUARTO CUADRANTE";*/
		$strCuad4="";
		$valor=41;
		//Construccion de la tabla del primer cuadrante
		$strCuad4.="<table cellspacing=15 id=incisivo width=100%>\n";
		$strCuad4.="<tr>\n";
		$strCuad4.="<th> C&oacute;digo </th>";
		$strCuad4.="<th> Estado </th>";
		$strCuad4.="</tr>\n";
		$_SESSION['cuartoCuadrante'] = $arrayCuadranteIV;
		$contadorPosicion=1;
		//$d=0;
		//foreach ($arrayCuadranteIV as $a) {
		for($i=1; $i<count($arrayCuadranteIV); $i++) {
			//if($a!=-1  && $a !=$idCuadrante4 ) {
			if($arrayCuadranteIV[$i]!=-1){
				//echo $valor." @ ";
				$strClase = pintaDientes($arrayCuadranteIV[$i]);
				$strCuad4.="<tr>\n";
				$strCuad4 .= "<td>$valor </td> \n";
				$strCuad4 .= "<td id=\"$valor\" class=\"$strClase\" onclick=\"myFunction(".$_SESSION['cuartoCuadrante'][$contadorPosicion].",$valor,4,$contadorPosicion)\"> </td> \n";
				$strCuad4.="</tr>\n";
				if($valor==48) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=80;
				$valor++;
				$contadorPosicion++;
				//$d++;
			}
			else
			{
				if($valor==48) //Si me paso de los dientes permantenes, cambio a temporales
					$valor=80;
				$valor++;
				$contadorPosicion++;					
			}
		}
		//}
		//echo $d;
		$strCuad4.=" </table>";			
	}
}

function pintaDientes($valor) {
	switch (intval($valor)) {
	
	case 0:
		return "Sano";	break;
		
	case 1:
		return "ManchaSeco"; break;
	
	case 2:
		return "ManchaHumedo";	break;
	
	case 3:
		return "MicroCavidad"; break;
	
	case 4:
		return "Sombra"; break;
	
	case 5:
		return "ExposicionMenor"; break;
	
	case 6:
		return "ExposicionMayor"; break;		
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
                        <p>Perfil epidemiol&oacute;gico de caries dental</p>                                             
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
    	  	<img src="../images/codigos.png" alt="C�digos de caries" class="left">
                  
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
                    <li> <a href="../registros/regDenPaciente.php">Registrar paciente</a> </li>
                    <li> <a href="../consulta/consultaSaludBucal.php">Consulta historia dental</a> </li>
                    <li> <a href="../consulta/revisionTrimestral.php">Revisi&oacute;n trimestral</a> </li>
                    <li> <a href="../consulta/incrementarDentadura.php">A&ntilde;adir dientes a paciente</a> </li>
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