<?php
include '../accesoDentista.php';
include '../validaciones.php';
require_once('../funciones.php');

conectar($servidor, $user, $pass, $name);

if ($_SESSION['type'] != 1 ) { //Checamos si hay una session vacia o si ya hay una sesion
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

		case 5://Profesional Principal
			header("refresh:3;url=../principales/profesionalPrincipal.php");
			break;
			
		case 6://Admin
			header("refresh:3;url=../principales/adminPage.php");
		break;
			
	}
	exit;
}

//Creacion de las variables de sesion para los campos
if(!isset($_SESSION['campos'])) {
	$_SESSION['campos']['usuario']='';
	$_SESSION['campos']['nombre']='';
	$_SESSION['campos']['apat']='';
	$_SESSION['campos']['amat']='';
	$_SESSION['campos']['grupo']='';
}

//Ahora los errores
if(!isset($_SESSION['error'])) {
	$_SESSION['error']['usuario']='hidden';
	$_SESSION['error']['nombre']='hidden';
	$_SESSION['error']['apat']='hidden';
	$_SESSION['error']['amat']='hidden';
	$_SESSION['error']['grupo']='hidden';
}

if(isset($_POST['posted'])) {
	
	
	$fail ="";
	$nombre = strtoupper(strip_tags($_POST['nombre']));
	$apaterno = strtoupper(strip_tags($_POST['apaterno']));
	$amaterno = strtoupper(strip_tags($_POST['amaterno']));
	//$nacimiento = strip_tags($_POST['nacimiento']);
	$padre = strip_tags($_POST['padre']);
	$grupo = strip_tags($_POST['grupo']);
	$dia = $_POST['dia'];
	$mes = $_POST['mes'];
	$anio =$_POST['year'];
	
	//Para la validacion
	$fail .= validaNombre(trim($nombre));
	$fail .= validaPaterno($apaterno,1);
	$fail .= validaPaterno($amaterno,2);
	$fail .= validaPadre(trim($padre));
	$fail .= revisaFecha($dia,$mes,$anio);
	//echo $nacimiento;
	
	if($fail == "") { //IF A	
		$query = @mysql_query('SELECT * FROM Nino WHERE idNino="'.mysql_real_escape_string($idNinio).'"');
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			print '<script type="text/javascript">';
			print 'alert("Error en el registro.")';
			print '</script>';
			header("refresh:1;url=regDenPaciente.php");
		}else{//ELSE F
			
			//Correcto formato de fecha
			$nacimiento = $anio."-".$mes."-".$dia;
			
			if($grupo == "")  {
				
				//Obtener el identificador del padre
				$meter2 = @mysql_query('SELECT * from Padre where Usuario="'.mysql_real_escape_string($padre).'"');
				
				
				$idPadre2 = @mysql_fetch_object($meter2);							
				
				$meter=@mysql_query('INSERT INTO Ninio (Nombre,ApellidoPaterno,ApellidoMaterno,FechaNaciemiento,Padre_idPadre,UltimaRevision) values 
						("'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
							'","'.mysql_real_escape_string($amaterno).'","'.mysql_real_escape_string($nacimiento).'","'.mysql_real_escape_string($idPadre2->idPadre).'",0'.')');
				
			}
			else { 
	
				$meter3 = @mysql_query('SELECT * from Grupo where idGrupo="'.mysql_real_escape_string($grupo).'"');
				
				$idGrupo = @mysql_fetch_object($meter3);																						
				$meter=@mysql_query('INSERT INTO Ninio values ("'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
					'","'.mysql_real_escape_string($amaterno).'","'.mysql_real_escape_string($nacimiento).'","'.mysql_real_escape_string($padre).'","'
					.mysql_real_escape_string($grupo).'","'.mysql_real_escape_string($idGrupo->Escuela_idEscuela)
						.'","'.mysql_real_escape_string($idGrupo->Escuela_Direccion_idDireccion).'",0"'.'")');
			}	
			
			if($meter){
				print '<script type="text/javascript">';
				print 'alert("Registro exitoso de paciente.")';
				print '</script>';
				header("refresh:0;url=../principales/mainDentista2.php");
				unset($_SESSION['campos']);
				unset($_SESSION['error']);
				exit;
			}
			else {
				$fail .= 'Hubo un error';				
			}
		} //ELSE A
	} //ELSE B
	else {
		print '<script type="text/javascript">';
		print 'alert("Error en el registro.")';
		print '</script>';
		header("refresh:0;url=regDenPaciente.php");
		exit;
	}
}	//ELSE C

else {
	$idNinio ="*Identificador del nino(Clave numerica)";
	$nombre = "*Primer Nombre:";
	$apaterno = "*Apellido Paterno:";
	$amaterno = "*Apellido Materno";
	$nacimiento = "*Fecha de nacimiento, formato dd/m/a";
	$padre = "Padre del nino:";
	$grupo = "";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Principal | Cartilla de Salud Bucal</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

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

<body id="home">
	<!-- #home || #page-post || #blog || #portfolio -->

	<!-- Page Start -->
	<div id="page">

		<!-- Main Column Start -->
		<div id="wrap">
			<div id="main-col">
				<!-- Nivo Slider -->

				<!-- Homepage Welcome Text -->
				<div id="homepage-post">
					<h1 class="p-title">
						<a href="#">Bienvenido al sitio de la Cartilla de Salud Bucal
							Digital</a>
					</h1>
					<div class="p-content">
						<p>Perfil epidemiológico de caries dental</p>
						<p>Página de registro de Dentista</p>
						<p>Los campos marcados como * son obligatorios</p>
						
						<?php 
						if(isset($_POST['posted'])) {

							echo $fail;
						}
						?>
					</div>

					<div id="registra">
						<ul>
							<li>
								
								<span style="color:red">Datos personales </span>
									
								<form action="regDenPaciente.php" method="post" >
									<input type="text" value="<?php echo $nombre;?>" name="nombre" alt="*Nombre(s): " title="Introduce tu primer nombre" id="nombre" /> 
									<input type="text" value="<?php echo $apaterno;?>" name="apaterno" alt="*Apellido paterno:" title="Introduce tu apellido paterno" id="apaterno" /> 
									<input type="text" value="<?php echo $amaterno;?>" name="amaterno" alt="*Apellido materno:" title="Introduce tu apellido materno" id="amaterno" />
									<span style="color:red">Fecha de nacimiento </span>
									<select name="dia" id="dia">
										<?PHP
										//<input type="text" value="<?php echo $nacimiento;>" name="nacimiento" alt="*Fecha de nacimiento del Niño Año-Mes-Dia:" title="Introduce la fecha de nacimiento del Niño,Año-Mes-Dia" id="nacimiento" />									 
										for($i=1; $i<=31; $i++)
		  								if($year == $i)
											echo "<option value='$i' selected>$i</option>";
										else
											echo "<option value='$i'>$i</option>";
										?>
									</select>
									
									<select name="mes" id="mes">
										<?PHP for($i=1; $i<=12; $i++) {
											switch ($i) {											
												case 1: $nombreMes = "Enero"; break;
												case 2: $nombreMes = "Febrero"; break;
												case 3: $nombreMes = "Marzo"; break;
												case 4: $nombreMes = "Abril"; break;
												case 5: $nombreMes = "Mayo"; break;
												case 6: $nombreMes = "Junio"; break;
												case 7: $nombreMes = "Julio"; break;
												case 8: $nombreMes = "Agosto"; break;
												case 9: $nombreMes = "Septiembre"; break;
												case 10: $nombreMes = "Octubre"; break;
												case 11: $nombreMes = "Noviembre"; break;
												case 12: $nombreMes = "Diciembre"; break;
											}
											
		  								if($mes == $i)
											echo "<option value='$i' selected>$nombreMes</option>";
										else
											echo "<option value='$i'>$nombreMes</option>";
										}
										?>
									</select>
									
									
									<select name="year" id="year">
										<?PHP for($i=1994; $i<=date("Y")-2; $i++)
		  								if($year == $i)
											echo "<option value='$i' selected>$i</option>";
										else
											echo "<option value='$i'>$i</option>";
										?>
									</select>
									 
									<input type="text" value="<?php echo $padre;?>" name="padre" alt="*Usurario del padre:" title="Introduce el usuario del padre" id="padre" />
									<input type="text" value="<?php echo $grupo;?>" name="grupo" alt="" title="Introduce el grupo del nino" id="padre" />								
									 
									<br></br>																																																																										
									<input type="submit" value="Registrar" /> 
									<input type="hidden" name="posted" value="yes" />
								</form>
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
				 <li class="act"><a href="../principales/mainDentista2.php">Inicio</a></li>
                    <li> <a href="../registros/regDenPaciente.php">Registrar paciente</a> </li>
                    <li> <a href="../consulta/consultaSaludBucal.php">Consulta historia dental</a> </li>                    
                    <li> <a href="../consulta/revisionTrimestral.php">Revisión trimestral</a> </li>	
                    <li> <a href="../consulta/incrementarDentadura.php">Añadir dientes a paciente</a> </li>
				</ul>
			</div>

			<!-- News Widget -->
			<div class="widget w-news">
				<h4 class="w-title title-light">Cerrar sesion.</h4>
				<div class="w-content">
					<ul>
						<li>
							<form action="../finSesion.php" method="post">
								Usuario:
								<?php echo $_SESSION["uid"];?>
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