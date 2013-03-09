<?php
include '../accesoDentista.php';


if ($_SESSION['type'] != 6 && $_SESSION['type'] != 1 ) { //Checamos si hay una session vacia o si ya hay una sesion
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 2: //Padre
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3://Maestro
			header("refresh:3;url=../principales/maestroPrincipal.php");
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

$strCuad1=""; //Los dientes que estan presentes en el cuadrante 1
$strCuad2=""; //Los dientes que estan presentes en el cuadrante 2
$strCuad3=""; //Los dientes que estan presentes en el cuadrante 3
$strCuad4=""; //Los dientes que estan presentes en el cuadrante 4
		
$mysqli = new mysqli("localhost", "monty", "holygrail", "newbasedientes");
/* check connection */
if (mysqli_connect_errno()) {
	printf("Connect failed: %s\n", mysqli_connect_error());
	exit();
}
$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
$existe= @mysql_fetch_object($query);
$nombreNino = $existe->Nombre;
$apellidoPNino = $existe->ApellidoPaterno;
$apellidoMNino = $existe->ApellidoMaterno;

//Obtencion de todas las dentaduras
$query ="SELECT idDentadura FROM Dentadura WHERE Ninio_idNinio=".mysql_real_escape_string($_SESSION['idNino'])."";
$result = $mysqli->query($query);
$dentaduras = array();
while($dentaduras[] = $result->fetch_assoc());
array_pop($dentaduras); // pop the last row off, which is an empty row
$ultimo = count($dentaduras);
$ultimo = implode($dentaduras[$ultimo-1]) ;



//Se obtien los identificadores de los cuadrantes de la ultima dentadura
$query = "SELECT CuadranteI_idCuadranteI,CuadranteII_idCuadranteII,CuadranteIII_idCuadranteIII,CuadranteIV_idCuadranteIV  FROM `Dentadura` WHERE idDentadura=$ultimo";
$result = $mysqli->query($query);
$identificadores = $result->fetch_array(MYSQLI_NUM);

//Se obtienen los dientes presentes en la ultima dentadura
$id = $identificadores[0];
$query = "SELECT `11`,`12`,`13`,`14`,`15`,`16`,`17`,`18`,`51`,`52`,`53`,`54`,`55` FROM CuadranteI WHERE idCuadranteI = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);
$contador = 11;
$cambio = false;
for($i=0; $i<count($dientesPresentes); $i++) {
	$val =$dientesPresentes[$i]; 
	if( $val!= -1)
		$strCuad1.="`$contador`,";
	$contador++;
	if($contador >18 && $cambio==false) {
		$contador=51;
		$cambio=true;
	}
}
$strCuad1 = substr_replace($strCuad1 ,"",-1);;
//echo $strCuad1."\n"; //Dientes presentes en el primer cuadrante

$id = $identificadores[1];
$query = "SELECT `21`,`22`,`23`,`24`,`25`,`26`,`27`,`28`,`61`,`62`,`63`,`64`,`65` FROM CuadranteII WHERE idCuadranteII = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);
$contador = 21;
$cambio = false;
for($i=0; $i<count($dientesPresentes); $i++) {
	$val =$dientesPresentes[$i];
	if( $val!= -1)
		$strCuad2.="`$contador`,";
	$contador++;
	if($contador >28 && $cambio==false) {
		$contador=61;
		$cambio=true;
	}
}
$strCuad2 = substr_replace($strCuad2 ,"",-1);;
//echo $strCuad2; //Dientes presentes en el s cuadrante

$id = $identificadores[2];
$query = "SELECT `31`,`32`,`33`,`34`,`35`,`36`,`37`,`38`,`71`,`72`,`73`,`74`,`75` FROM CuadranteIII WHERE idCuadranteIII = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);
$contador = 31;
$cambio = false;
for($i=0; $i<count($dientesPresentes); $i++) {
	$val =$dientesPresentes[$i];
	if( $val!= -1)
		$strCuad3.="`$contador`,";
	$contador++;
	if($contador >38 && $cambio==false) {
		$contador=71;
		$cambio=true;
	}
}
$strCuad3 = substr_replace($strCuad3 ,"",-1);;
//echo $strCuad3; //Dientes presentes en el primer cuadrante

$id = $identificadores[3];
$query = "SELECT `41`,`42`,`43`,`44`,`45`,`46`,`47`,`48`,`81`,`82`,`83`,`84`,`85` FROM CuadranteIV WHERE idCuadranteIV = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);
$contador = 41;
$cambio = false;
for($i=0; $i<count($dientesPresentes); $i++) {
	$val =$dientesPresentes[$i];
	if( $val!= -1)
		$strCuad4.="`$contador`,";
	$contador++;
	if($contador >48 && $cambio==false) {
		$contador=81;
		$cambio=true;
	}
}
$strCuad4 = substr_replace($strCuad4 ,"",-1);;
//echo $strCuad4; //Dientes presentes en el primer cuadrante

//echo count($dentaduras);

//Ahora a crear el arreglo 2x2 que contendra cada estado de los dientes en cada revision
for($i=0; $i<count($dentaduras); $i++) {
	$val = implode($dentaduras[$i]);
	$query = "SELECT $strCuad1,$strCuad2,$strCuad3,$strCuad4 FROM CuadranteI,CuadranteII,CuadranteIII,CuadranteIV WHERE CuadranteI.idCuadranteI=$val AND 
		CuadranteII.idCuadranteII=$val AND CuadranteIII.idCuadranteIII=$val AND CuadranteIV.idCuadranteIV=$val";
	//echo $query;
	$result = $mysqli->query($query);
	$dientesPresentes =$result->fetch_array(MYSQLI_NUM);
	//echo count($dientesPresentes);
	for($j=0; $j<count($dientesPresentes); $j++) {
		$tabla[$j][$i] = $dientesPresentes[$j]; //Se crea la tabla de los dientes presentes
		//echo $dientesPresentes[$j]." V ";
	}
	
}

/*************************************************************************************
 * WORKING....
 ********************************/


$strTabla="<table cellspacing=15 class=\"consulta\" id=Consulta width=100%>\n";
 $strTabla.="<tr>\n";
$strCuad1 = str_replace("`","",$strCuad1);
$arregloDientes1 = explode(',', $strCuad1, 6);

$strTabla.="<th> Dientes </th>";
for($i=1; $i<count($dentaduras); $i++) {
	$strTabla.="<th> $i </th>";
}

$strCuad2 = str_replace("`","",$strCuad2);
$arregloDientes2 = explode(',', $strCuad2, 6);

$strCuad3 = str_replace("`","",$strCuad3);
$arregloDientes3 = explode(',', $strCuad3, 6);


$strCuad4 = str_replace("`","",$strCuad4);
$arregloDientes4 = explode(',', $strCuad4, 6);
//echo count ($arregloDientes1);
$arregloDientes1 = array_merge($arregloDientes1,$arregloDientes2);
$arregloDientes1 = array_merge($arregloDientes1,$arregloDientes3);
$arregloDientes1 = array_merge($arregloDientes1,$arregloDientes4); //Se obtienen todos los dientes en un solo array
echo count($arregloDientes1);

$strTabla.="</tr>\n";

for($i=0;$i<count($arregloDientes1);$i++) {
	$strTabla.="<tr>\n";
	if($i%2==0)
		$strTabla.= "<td> $arregloDientes1[$i] </td> \n";
	else 
		$strTabla.= "<td class=\"consulta\"> $arregloDientes1[$i] </td> \n";
	
	for($j=1;$j<count($dentaduras);$j++) {
		$val = $tabla[$i][$j];
		if($i%2==0)
			$strTabla.= "<td> $val </td> \n";
		else
			$strTabla.= "<td class=\"consulta\"> $val </td> \n";
		//echo $tabla[$j][$i].",";
		}
	$strTabla.="</tr>\n";
}
$strTabla.=" </table>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<!-- Estilos -->
 	<link rel="stylesheet" type="text/css" href="../css/style.css" />	
    <link rel="stylesheet" type="text/css" href="../css/revision.css" />
    <link rel="stylesheet" href="../public/js/ui-lightness/jquery-ui.css" />
	
<title>Consulta dentadura</title>
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
            <div id="main-col2"><!-- Nivo Slider -->                                                                         
                <!-- Homepage Welcome Text -->
              
                                     	                                        
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
						  		<p>Estado dental </p>	
						  		<?php 
						  		
						  			echo $strTabla;
						  			?>
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
    
        
    
       
        
    </div>
    <!-- Page End -->		
</body>
</html>