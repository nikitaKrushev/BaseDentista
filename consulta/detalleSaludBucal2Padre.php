<?php
include '../accesoDentista.php';


if ($_SESSION['type'] != 2  ) { //Checamos si hay una session vacia o si ya hay una sesion
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 1://Dentista
			header("refresh:3, url=../principales/mainDentista2.php");
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
			
		case 6://Admin
			header("refresh:3;url=../principales/adminPage.php");
		break;
					
	}
	exit;
}

$mensaje = 0; //Desplegar un mensaje para el padre	
$peorCaso = 0; //Sirve para no modificar el mensaje

$texto = array();
$texto[0] = "Sano: Felicidades no presenta caries dental!";
$texto[1] = "Enfermo: Requiere de atenci&oacute;n dental";
$texto[2] = "Enfermo: Requiere de atenci&oacute;n dental inmediata!";

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

$dientes = array(); //Los dientes del paciente
$imagenes = array(); //Imagenes de los dientes del paciente
$conta = 0;
//Se obtienen los dientes presentes en la ultima dentadura
$id = $identificadores[0];
$query = "SELECT `11`,`12`,`13`,`14`,`15`,`16`,`17`,`18` FROM CuadranteI WHERE idCuadranteI = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);

for($i=0; $i<count($dientesPresentes); $i++) {
	$dientes[$conta] = $dientesPresentes[$i];
	$conta++;
}

$id = $identificadores[1];
$query = "SELECT `21`,`22`,`23`,`24`,`25`,`26`,`27`,`28` FROM CuadranteII WHERE idCuadranteII = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);

for($i=0; $i<count($dientesPresentes); $i++) {
	$dientes[$conta] = $dientesPresentes[$i];
	$conta++;
}

$id = $identificadores[2];
$query = "SELECT `31`,`32`,`33`,`34`,`35`,`36`,`37`,`38` FROM CuadranteIII WHERE idCuadranteIII = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);

for($i=0; $i<count($dientesPresentes); $i++) {
	$dientes[$conta] = $dientesPresentes[$i];
	$conta++;
}

$id = $identificadores[3];
$query = "SELECT `41`,`42`,`43`,`44`,`45`,`46`,`47`,`48` FROM CuadranteIV WHERE idCuadranteIV = $id";
$result = $mysqli->query($query);
$dientesPresentes =$result->fetch_array(MYSQLI_NUM);

for($i=0; $i<count($dientesPresentes); $i++) {
	$dientes[$conta] = $dientesPresentes[$i];
	$conta++;
}

for($i=0; $i<count($dientes); $i++) {
	if($dientes[$i] == 4 || $dientes[$i] == 5 || $dientes[$i] == 6) {
		$mensaje=2;
		$peorCaso = 1;
	}
	else {
		if($dientes[$i] == 1 || $dientes[$i] == 2 || $dientes[$i] == 3 ) {
			if($peorCaso==0)
				$mensaje=1;
		}
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
            <div id="main-col3"><!-- Nivo Slider -->                                                                         
                <!-- Homepage Welcome Text -->
              
                                     	                                        
                   		<div id="revisaForm" style="color:#0000FF" class="divisionDetalles">
							
								<div class="divisionDetalles">
									
									
								</div>
			
						  		<div class="divisionDetalles">
						  		<h1>Estado dental </h1>
						  		<p> Le informamos que el ni&ntilde;o(a): <?php echo $nombreNino." ".$apellidoPNino." ".$apellidoMNino; ?>  </p>	
						  		<p> Fue revisado por los odontopediatras de Tijuana A.C.</p>
						  		<p> Se observ&oacute que su hijo se encuentra:</p>						  
						  		<h2> <?php echo $texto[$mensaje]; ?></h2>
						  		<p> Para la atenci&oacute;n dental su hijo podr&aacute ser atendido en el centro de salud de la calle ocho.</p>
						  		<p>	Las cl&iacute;nicas de la Facultad de Odontolog&iacute;a, los centros de salud o con su dentista particular.
						  		</p>
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
    
        
    
       
        
    </div>
    <!-- Page End -->		
</body>
</html>