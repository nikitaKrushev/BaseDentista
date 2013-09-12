<?php
/**
 * Autor: Josué Castañeda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 *
 * Descripcion:
 * 	Realiza el registro de pacientes, en caso de que los datos sean correctos, se crea un
 *  paciente en la base de datos. Además se le asigna una dentadura vacia. 
 *
 */

include '../accesoDentista.php';
include '../validaciones.php';

//Aqui el nombre de los escuela
$query3 = @mysql_query("SELECT idEscuela,NombreEscuela FROM Escuela");

while ($existe3 = @mysql_fetch_object($query3))
	$escuelas[] = $existe3;
$size3= count($escuelas);
/*$i=0;
for($i; $i<count($escuelas); $i++) {
	echo $escuelas[$i]->NombreEscuela;
	
}*/


if ($_SESSION['type'] != 6 ) { //Checamos si hay una session vacia o si ya hay una sesion
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 1: //Padre
			header( "refresh:3;url=../principales/mainDentista2.php" ); //Redireccionar a pagina
		break;
		
		case 2: //Padre
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3://Maestro
			header("refresh:3;url=../principales/maestroPrincipal.php");
			break;

		case 4://Director
			header("refresh:3;url=../principales/directorPrincipal.php");
			break;

		case 5://Profesional
			header("refresh:3;url=../principales/profesionalPrincipal.php");
		break;
			
	}
	exit;
}

if(isset($_POST['posted'])) {
	require_once('../funciones.php');	
	conectar($servidor, $user, $pass, $name);
	
	$fail ="";
	$nombre = strtoupper(strip_tags($_POST['nombre']));
	$apaterno = strtoupper(strip_tags($_POST['apaterno']));
	$amaterno = strtoupper(strip_tags($_POST['amaterno']));
	//$nacimiento = strip_tags($_POST['nacimiento']);
	$padre = strip_tags($_POST['padre']);
	/*$grupo = strip_tags($_POST['grupo']);*/
	$escuela = strip_tags($_POST['idEscuela']);
	$grupo = strip_tags($_POST['grupo']);
	
	//echo $padre." ".$escuela." ".$grupo;
	//exit;
	
	if(empty($padre)){
		$padre = 0; //Valor por default
	}

	$dia = $_POST['dia'];
	$mes = $_POST['mes'];
	$anio =$_POST['year'];
			
	//Para la validacion
	$fail .= validaNombre(trim($nombre));
	$fail .= validaPaterno($apaterno,1);
	$fail .= validaPaterno($amaterno,2);
	$fail .= revisaFecha($dia,$mes,$anio);
	$fail .= validaPadre(trim($padre));
	
	if($fail == "") { //IF A
		$query = @mysql_query('SELECT * FROM Nino WHERE idNino="'.mysql_real_escape_string($idNinio).'"');
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			echo $fail;
			header("refresh:3;url=regNinio.php");
		}else{//ELSE F
			
			//Correcto formato de fecha
			$nacimiento = $anio."-".$mes."-".$dia;
			if($grupo != 0)  {
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
					header("refresh:1;url=../principales/adminPage.php");
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
		header("refresh:1;url=regNinio.php");
	}
}	//ELSE C

else {	
	/*$nombre = "*Primer Nombre:";
	$apaterno = "*Apellido Paterno:";
	$amaterno = "*Apellido Materno";
	$nacimiento = "*Fecha de nacimiento, formato dd/m/a";
	$padre = "Padre del nino:";
	$grupo = "";*/
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
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="../js/superfish.js"></script>
<script type="text/javascript" src="../js/jquery.nivo.slider.pack.js"></script>
<script type="text/javascript" src="../js/jquery.prettyPhoto.js"></script>
<script type="text/javascript" src="../js/jquery.prettySociable.js"></script>
<script type="text/javascript" src="../js/jquery.validate.min.js"></script>
<script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script>
<script type="text/javascript" src="../js/main.js"></script>

<script type="text/javascript">
		
	function cargaEstados(valor) {
			
		if(valor==0) { //Selecciono la primera opcion
			var selectEstado = document.getElementById("grupo");
			selectEstado.length=0;
			var nuevaOpcion = document.createElement("option");
			nuevaOpcion.value=0;
			nuevaOpcion.innerHTML="Selecciona escuela...";
			selectEstado.appendChild(nuevaOpcion);	selectActual.disabled=true;
		}
		else {


			var selectDestino=document.getElementById("grupo");
				
		 	var xmlrequest=new XMLHttpRequest();		 			 
		 	xmlrequest.open("GET",'getGrupos.php?valor='+valor,true);
		 	xmlrequest.onreadystatechange=function() 
				{ 
			 	if((xmlrequest.readyState==1)) { //El cliente espera la respuesta del servidor
						var nuevaOpcion=document.createElement("option"); nuevaOpcion.value=0; nuevaOpcion.innerHTML="Cargando...";
						selectDestino.appendChild(nuevaOpcion); 	
					}
					
					if (xmlrequest.readyState==4)					
						selectDestino.innerHTML=xmlrequest.responseText;										
				}
			 
		 xmlrequest.send(null);
		}
	}	

</script>


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
						<h1>Perfil epidemiológico de caries dental</h1>
						<h2>Página de registro de Dentista</h2>
						
						<?php 
						if(isset($_POST['posted'])) {

							echo $fail;
						}
						?>
					</div>

					<div id="registra">
						<ul>
							<li>								
									
								<form class="form-horizontal" action="regNinio.php" method="post" >
								
									<fieldset>

									<legend>Todos los datos son requeridos</legend>

									<div class="control-group">
										<label class="control-label" for="nombre">Nombre(s):</label>
								  		<div class="controls">
										<input class="pull-left input-xlarge" data-trigger="hover" required type="text" value="<?php echo $nombre;?>" name="nombre" title="Introduce tu primer nombre" id="nombre" /> 									 
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="apaterno">Apellido Paterno:</label>
								  		<div class="controls">
										<input class="pull-left input-xlarge"data-trigger="hover" required type="text" value="<?php echo $apaterno;?>" name="apaterno" title="Introduce tu apellido paterno" id="apaterno" /> 									 
										</div>									
									</div>									
									
									<div class="control-group">
										<label class="control-label" for="amaterno">Apellido Materno:</label>
								  		<div class="controls">
										<input class="pull-left input-xlarge"data-trigger="hover" required type="text" value="<?php echo $amaterno;?>" name="amaterno" title="Introduce tu apellido materno" id="amaterno" /> 									 									 
										</div>									
									</div>
																																																						
									</fieldset>									
									
									<legend>Fecha de nacimiento</legend>
									
									<div class="control-group">
										<label class="control-label" for="amaterno">Día:</label>
								  		<div class="controls">
										
										 <select class="pull-left" name="dia" id="dia">
											<?PHP
											//<input type="text" value="<?php echo $nacimiento;>" name="nacimiento" alt="*Fecha de nacimiento del Niño Año-Mes-Dia:" title="Introduce la fecha de nacimiento del Niño,Año-Mes-Dia" id="nacimiento" />									 
											for($i=1; $i<=31; $i++)
		  										if($year == $i)
													echo "<option value='$i' selected>$i</option>";
												else
													echo "<option value='$i'>$i</option>";
											?>
										</select>									 									 
										</div>									
									</div>
									
									<div class="control-group">
										<label class="control-label" for="amaterno">Mes:</label>
								  		<div class="controls">
											<select class="pull-left" name="mes" id="mes">
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
										</div>									
									</div>									
									
									<div class="control-group">
										<label class="control-label" for="amaterno">Año:</label>
								  		<div class="controls">
										 	<select class="pull-left" name="year" id="year">
												<?PHP for($i=1994; $i<=date("Y")-2; $i++)
		  											if($year == $i)
														echo "<option value='$i' selected>$i</option>";
													else
														echo "<option value='$i'>$i</option>";
												?>
											</select>									 									 
										</div>									
									</div> 																											
									
									<legend>Información del Padre</legend>
									
									<div class="control-group">
										<label class="control-label" for="padre">Usuario del padre:</label>
								  		<div class="controls">
											<input class="pull-left input-xlarge"data-trigger="hover"  type="text" value="<?php echo $padre;?>" name="padre" title="Introduce el usuario del padre" id="padre" /> 									 									 
										</div>									
									</div>
									
										<legend>Escuela</legend>
									
									<div class="control-group">
										<label class="control-label" for="nomEsc">Nombre de la escuela:</label>
								  		<div class="controls">
										<select class="pull-left" name="idEscuela" id="idEscuela" onchange="cargaEstados(this.value)">
										<!--  Valor por default -->
												<option value="0" >Selecciona una escuela </option>
									<?php
										if(isset($size3)) {
										for($i=0; $i<$size3; $i++) {
									?>
										<option value="<?php echo $escuelas[$i]->idEscuela;?>" ><?php echo $escuelas[$i]->NombreEscuela;?> </option>
									<?php }} 
									?>										
									</select>									 									 					 									 								 								  
										</div>									
									</div>
									
									<div class="control-group pul">
										<label class="control-label" for="usuario">Grupo:</label>
								  		<div class="controls">
											<select class="pull-left" name="grupo"  id="grupo">
												<option value="0" >Selecciona una escuela primero </option>
																			
											</select>							
										</div>									
									</div>
									
									<div class="control-group">
										<div class="controls">
											<input class="pull-left" type="submit" value="Registrar" />  									 									 
										</div>									
									</div>
									
									
									<!--<input type="text" value="<?php echo $grupo;?>" name="grupo" alt="" title="Introduce el grupo del nino" id="padre" />-->								
									 
									
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
			<a href="../principales/adminPage.php" id="logo">Foundation</a>

			<!-- Main Naigation (active - .act) -->
			<div id="main-nav">
				<ul>
				<li class="act"><a href="../principales/adminPage.php">Inicio</a></li>
                    <li> <a href="../registros/regAdminDent.php">Registro de Dentistas</a> </li>
                    <li> <a href="../registros/regDirector.php">Registro de Directores</a> </li>
                    <li> <a href="../registros/regNinio.php">Registro de Pacientes</a> </li>                                     
                    <li> <a href="../construccion.html">Solicitudes pendientes</a> </li>
					
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
				<div id="copyright"></div>
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
<script type="text/javascript">
$(function () {
	$('#nombre').popover({
		title: 'Test',
		content: 'El nombre solo contiene letras',
		placement: 'right'
	});
	
	$('#apaterno').popover({
		title: 'Test',
		content: 'Los apellidos llevan solo letras',
		placement: 'right'
	});
	$('#amaterno').popover({
		title: 'Test',
		content: 'Los apellidos llevan solo letras',
		placement: 'right'
	});
	$('#padre').popover({
		title: 'Test',
		content: 'Registra el identificador del padre',
		placement: 'right'
	});		
});
</script>
</html>