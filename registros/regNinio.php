<?php
/**
 * Autor: Josu� Casta�eda
 * Escrito: 2/FEB/2013
 * Ultima actualizacion: 2/FEB/2013
 *
 * Descripcion:
 * 	Realiza el registro de pacientes, en caso de que los datos sean correctos, se crea un
 *  paciente en la base de datos. Adem�s se le asigna una dentadura vacia. 
 *
 */

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

if(isset($_POST['posted'])) {
	require_once('../funciones.php');
	conectar($servidor, $user, $pass, $name);

	$idNinio = strip_tags($_POST['idNinio']);
	$nombre = strtoupper(strip_tags($_POST['nombre']));
	$apaterno = strtoupper(strip_tags($_POST['apaterno']));
	$amaterno = strtoupper(strip_tags($_POST['amaterno']));
	$nacimiento = strip_tags($_POST['nacimiento']);
	$padre = strip_tags($_POST['padre']);
	$grupo = strip_tags($_POST['grupo']);

	//Para la validacion
	$fail = validaNino($idNinio);
	$fail .= validaNombre(trim($nombre));
	$fail .= validaPaterno($apaterno,1);
	$fail .= validaPaterno($amaterno,2);
	//Falta validacion de nacimiento
	$fail .= validaPadre(trim($padre));

	if($fail == "") { //IF A
		$query = @mysql_query('SELECT * FROM Nino WHERE idNino="'.mysql_real_escape_string($idNinio).'"');
		if($existe = @mysql_fetch_object($query)){
			$fail.= 'Este usuario '.$usuario.' ya existe. Intente otro usuario';
			echo $fail;
			header("refresh:3;url=regNinio.php");
		}else{//ELSE F
			
			if($grupo == "")  {
				
				//Obtener el identificador del padre
				$meter2 = @mysql_query('SELECT * from Padre where Usuario="'.mysql_real_escape_string($padre).'"');
				
				
				$idPadre2 = @mysql_fetch_object($meter2);							
				
				$meter=@mysql_query('INSERT INTO Ninio (idNinio,Nombre,ApellidoPaterno,ApellidoMaterno,FechaNaciemiento,Padre_idPadre,UltimaRevision) values 
						("'.mysql_real_escape_string($idNinio).' ","'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
							'","'.mysql_real_escape_string($amaterno).'","'.mysql_real_escape_string($nacimiento).'","'.mysql_real_escape_string($idPadre2->idPadre).'",0'.')');
				
			}
			else { 
	
				$meter3 = @mysql_query('SELECT * from Grupo where idGrupo="'.mysql_real_escape_string($grupo).'"');
				
				$idGrupo = @mysql_fetch_object($meter3);											
								
				$meter=@mysql_query('INSERT INTO Ninio values ("'.mysql_real_escape_string($idNinio).' ","'.mysql_real_escape_string($nombre).'", "'.mysql_real_escape_string($apaterno).
					'","'.mysql_real_escape_string($amaterno).'","'.mysql_real_escape_string($nacimiento).'","'.mysql_real_escape_string($padre).'","'
					.mysql_real_escape_string($grupo).'","'.mysql_real_escape_string($idGrupo->Escuela_idEscuela)
						.'","'.mysql_real_escape_string($idGrupo->Escuela_Direccion_idDireccion).'",0"'.'")');
			}	
			
			if($meter){
				echo 'Usuario registrado con exito';
				header("refresh:3;url=../principales/adminPage.php");
			}
			else {
				$fail .= 'Hubo un error';				
			}
		} //ELSE A
	} //ELSE B
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
function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validaPaterno($nombre,$tipo) {
	if ($nombre =="") {
		if($tipo == 1)
			return "Favor de llenar el campo apellido paterno.\n";
		else
			return "Favor de llenar el campo apellido materno.\n";
	}
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "Los apellidos solo contienen letras.\n";
	return "";
}

function validaPadre($field) {
	if ($field =="") return "Favor de llenar el campo de padre.\n";
}

function validaNino($idNinio) {
	if (! preg_match("/^[0-9]+$/",$idNinio))
		return "La clave del niño requiere solo digitos.\n";
	return "";
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
								<script type="text/javascript">
									function validate(form){
										fail = validateNombre(form.nombre.value);
										fail += validatePaterno(form.apaterno.value,1);
										fail += validatePaterno(form.amaterno.value,2);
										fail += validatePadre(form.padre.value);
										fail += validateNinio(form.idNinio.value);
																												
										if (fail =="") return true;
										else {
											alert(fail);
											return false;
										}
									}
									
									function validateNombre(field) {
										if (field =="") return "Favor de llenar el campo Nombre.\n";
										else
											if (! /^[a-zA-Z]+$/.test(field) )
												return "El campo Nombre solo contiene letras.\n";
										return "";
									}																		
									
									function validatePaterno(field,tipo) {
										if (field =="") {
											if(tipo == 1)
												return "Favor de llenar el campo apellido paterno.\n";
											else
												return "Favor de llenar el campo apellido materno.\n";
										}
										else
											if (! /^[a-zA-Z]+$/.test(field) )
												return "Los apellidos contienen solo letras.\n";
										return "";
									}
									
									function validatePadre(field) {
										if (field =="") return "Favor de llenar el campo padre.\n";
									}									
									
									function validateNinio(field) {
										if (! /^[0-9]+$/.test(field))
											return "El campo ninio requiere digitos.\n";					
										return "";
									}
									
								</script>
								<span style="color:red">Datos personales </span>
									
								<form action="regNinio.php" method="post" >
									<input type="text" value="<?php echo $nombre;?>" name="nombre" alt="*Nombre(s): " title="Introduce tu primer nombre" id="nombre" /> 
									<input type="text" value="<?php echo $apaterno;?>" name="apaterno" alt="*Apellido paterno:" title="Introduce tu apellido paterno" id="apaterno" /> 
									<input type="text" value="<?php echo $amaterno;?>" name="amaterno" alt="*Apellido materno:" title="Introduce tu apellido materno" id="amaterno" /> 
									<input type="text" value="<?php echo $idNinio;?>" name="idNinio" alt="*Identificador Niño:" title="Introduce el identificador del niño" id="idNinio" />
									<input type="text" value="<?php echo $nacimiento;?>" name="nacimiento" alt="*Fecha de nacimiento del Niño Año-Mes-Dia:" title="Introduce la fecha de nacimiento del Niño,Año-Mes-Dia" id="nacimiento" />
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
			<a href="../principales/adminPage.php" id="logo">Foundation</a>

			<!-- Main Naigation (active - .act) -->
			<div id="main-nav">
				<ul>
				<li class="act"><a href="../principales/adminPage.php">Inicio</a></li>
                    <li>
                        <a href="../registros/regAdminDent.php">Registro de Dentistas</a>
                    </li>
                    <li>
                        <a href="../registros/regDirector.php">Registro de Directores</a>      
                    </li>
                    <li>
                        <a href="../registros/regNinio.php">Registro de Pacientes</a>      
                    </li>                 
                    
                    <li>
                        <a href="../construccion.html">Solicitudes pendientes</a>      
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
				<div id="copyright">&copy; 2012 Miguel Alberto Zamudio | UABC</div>
			</div>

			<!-- Footer Widgets -->
			<div id="f-main-col">
				<!-- Links -->
				<div class="widget w-25 w-links"></div>
				<!-- Social -->
				<div class="widget w-25 w-links"></div>
				<!-- Contact Info -->
				<div class="widget w-50 w-text last" id="text-1">
					<h5 class="w-title">Contacto:</h5>
					<div class="w-content">
						<a href="#"><img src="../img/pictures/zamudio.png" alt="Our Building"
							class="alignright" /> </a> Tijuana, B.C., México<br /> Tel.: 664
						400 7866<br /> <a href="#">cartillasaludbucal@gmail.com</a>
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