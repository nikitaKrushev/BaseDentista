<?php
if(isset($_POST['posted'])) {
	require_once('../funciones.php');
	conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

	//recibe info
	$clave = strip_tags($_POST['clave']);
	$nombre = strip_tags($_POST['nombre']);
	$institucion = strip_tags($_POST['institucion']);
	$mensaje = "Registro no exitoso, se encontraron los siguientes<br />
	errores en el formulario:";

	//Para validacion
	$fail = validaClave($clave);
	$fail .= validaNombre(trim($nombre));
	$fail .= validaInstitucion($institucion);
	echo "<html><head><title>Registro Clinica</title>";
	if($fail == "") {

		$query = @mysql_query("SELECT * FROM Clinica WHERE idClinica='".mysql_real_escape_string($clave)."' AND Institucion_idInstitucion= '".mysql_real_escape_string($institucion)."'");

		if($existe = @mysql_fetch_object($query)){
			echo 'La clinica '.$clave.' ya existe';
		}else{
			$meter=@mysql_query('INSERT INTO Clinica (idClinica, NombreClinica, Institucion_idInstitucion) values ("'.mysql_real_escape_string($clave).'","'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($institucion).'")');

			if($meter){
				echo 'clinica registrada con exito';
			}else{
				echo 'Hubo un error';
			}
		}
		exit;
	}
}
else {
	$clave = "Clave:";
	$nombre = "Nombre:";
	$institucion = "Institucion:";

}
function validaClave($clave) {
	if (! preg_match("/^[0-9]+$/",$clave))
		return "La clave requiere digitos.\n";
	return "";
}

function validaNombre($nombre){
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else
		if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
		return "El campo Nombre solo contiene letras.\n";
	return "";
}

function validaInstitucion($institucion) {
	if (! preg_match("/^[0-9]+$/",$institucion))
		return "El estado requiere digitos.\n";
	return "";
}


/*include '../accesoDentista.php';
 //Checamos si hay una session vacia o si ya hay una sesion
if ($_SESSION['type'] != 3) {
echo("Contenido Restringido");
switch($_SESSION['type']) {

case 1:
header("refresh:3, url=loggeado.php");
break;

case 2:
header( "refresh:3;url=padrePrincipal.php" ); //Redireccionar a pagina
break;

}

} */

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
						<p>Página de registro de Clinicas</p>
						<?php 
						if(isset($_POST['posted'])) {

							echo $fail;
						}
						?>

					</div>

					<div id="registra">
						<ul>
							<li><script type="text/javascript">
							function validate(form){
								fail = validateClave(form.clave.value);
								fail += validateNombre(form.nombre.value);
								fail += validateInstitucion(form.institucion.value);
								if (fail =="") return true;
								else {
									alert(fail);
									return false;
								}
							}
						
							function validateClave(field) {
									if (! /^[0-9]+$/.test(field))
									return "La clave requiere digitos.\n";					
								return "";
							}
							
							function validateNombre(field) {
								if (field =="") return "Favor de llenar el campo Nombre.\n";
								else
									if (! /^[a-zA-Z]+$/.test(field) )
										return "El campo Nombre solo contiene letras.\n";
								return "";
							}
							
							function validateClinica(field) {
								if (! /^[0-9]+$/.test(field))
									return "El campo Estado requiere digitos.\n";					
								return "";
							}
							</script>

								<form action="regClinica.php" method="post"
									onSubmit="return validate(this)">
									<input type="text" value="<?php echo $clave;?>"
										alt="Clave de clínica (Numerica):" title="Pon la clave"
										name="clave" id="clave" /> <input type="text"
										value="<?php echo $nombre; ?>" alt="Nombre de la clínica:"
										title="Pon el nombre de la clinica" name="nombre" id="nombre" />
									<input type="text" value="<?php echo $institucion; ?>"
										alt="Institución:" title="Selcciona una institucion"
										name="institucion" id="institucion" /> <input type="submit"
										value="Registrar" /> <input type="hidden" name="posted"
										value="yes" />

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
			<a href="index.html" id="logo">Foundation</a>

			<!-- Main Naigation (active - .act) -->
			<div id="main-nav">
				<ul>
					<li class="act"><a href="index.html">Inicio</a></li>
					<li><a href="contruccion.html">Consultorio</a>
					</li>
					<li><a href="construccion.html">Dentista </a>
					</li>
					<li><a href="construccion.html">Escuela</a>
					</li>
					<li><a href="construccion.html">Paciente</a>
					</li>

					<li><a href="construccion.html">Solicitudes pendientes</a>
					</li>

				</ul>
			</div>

			<!-- News Widget -->
			<div class="widget w-news">
				<h4 class="w-title title-light">Cerrar sesion.</h4>
				<div class="w-content">
					<ul>
						<li>
							<form action="finSesion.php" method="post">
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
				<div id="sidebar-end">
					<form action="#" id="subscribe">
						<input type="text" value="" alt="Recibe las �ltimas noticias!"
							title="Escribe tu correo" /> <input type="submit" value=""
							title="Subscribe" />
					</form>
				</div>
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
						<a href="#"><img src="img/pictures/zamudio.png" alt="Our Building"
							class="alignright" /> </a> Tijuana, B.C., M�xico<br /> Tel.: 664
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

<?php
/*
 if(isset($_POST['posted'])) {
require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');
$mensaje = "Registro no exitoso, se encontraron los siguientes<br />
errores en el formulario:";

//recibe info
$clave = strip_tags($_POST['clave']);
$nombre = strip_tags($_POST['nombre']);
$institucion = strip_tags($_POST['institucion']);
$mensaje = "Registro no exitoso, se encontraron los siguientes<br />
errores en el formulario:";

//Para validacion
$fail = validaClave($clave);
$fail .= validaNombre(trim($nombre));
$fail .= validaInstitucion($institucion);
echo "<html><head><title>Registro Clinica</title>";
if($fail == "") {

$query = @mysql_query("SELECT * FROM Clinica WHERE idClinica='".mysql_real_escape_string($clave)."' AND Institucion_idInstitucion= '".mysql_real_escape_string($institucion)."'");

if($existe = @mysql_fetch_object($query)){
echo 'La clinica '.$clave.' ya existe';
}else{
$meter=@mysql_query('INSERT INTO Clinica (idClinica, NombreClinica, Institucion_idInstitucion) values ("'.mysql_real_escape_string($clave).'","'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($institucion).'")');

if($meter){
echo 'clinica registrada con exito';
}else{
echo 'Hubo un error';
}
}
exit;
}
}

//Output y HTML

echo <<<_END
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- Seccion HTML -->
<style>.signup { border: 1px solid #999999;
font: normal 14px helvetica; color:#444444; }</style>
<script type="text/javascript">
function validate(form){
fail = validateClave(form.clave.value);
fail += validateNombre(form.nombre.value);
fail += validateInstitucion(form.institucion.value);
if (fail =="") return true;
else {
alert(fail);
return false;
}
}

function validateClave(field) {
if (! /^[0-9]+$/.test(field))
	return "La clave requiere digitos.\n";
return "";
}

function validateNombre(field) {
if (field =="") return "Favor de llenar el campo Nombre.\n";
else
	if (! /^[a-zA-Z]+$/.test(field) )
	return "El campo Nombre solo contiene letras.\n";
return "";
}

function validateClinica(field) {
if (! /^[0-9]+$/.test(field))
	return "El campo Estado requiere digitos.\n";
return "";
}
</script></head><body>
<table class="signup" border="0" cellpadding="2"
cellspacing="5" bgcolor="#eeeeee">
<th colspan="2" align="center">Formulario de inscripción</th>
<tr><td colspan="2">$mensaje <p><font color=red size=1><i>$fail</i></font></p>
</td></tr>
<form action="regClinica.php" method="post" onSubmit="return validate(this)">
<tr><td>Clave de clínica (Numerica):</td><td><input type="text" name="clave" size="20" id="clave" value="$clave" />

<tr><td>Nombre de la clínica:</td><td><input type="text" name="nombre" size="20" id="nombre" value="$nombre" />

<tr><td>Institución:</td><td> <input type="text" name="institucion" size="20" id="institucion" value="$institucion"/>

<tr><td><input type="submit" value="Registrar" />

<input type="hidden" name="posted" value="yes" />

</form>
_END;

//Funciones PHP
function validaClave($clave) {
if (! preg_match("/^[0-9]+$/",$clave))
	return "La clave requiere digitos.\n";
return "";
}

function validaNombre($nombre){
if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
else
	if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
	return "El campo Nombre solo contiene letras.\n";
return "";
}

function validaInstitucion($institucion) {
if (! preg_match("/^[0-9]+$/",$institucion))
	return "El estado requiere digitos.\n";
return "";
}

<form action="regClinica.php" method="post" onSubmit="return validate(this)">
<input type="text" value="$clave" alt="Clave de clínica (Numerica):" title="Pon la clave" name="clave" id="clave"/>
<input type="text" value="$nombre" alt="Nombre de la clínica:" title="Pon el nombre de la clinica" name="nombre" id="nombre"/>
<input type="text" value="$institucion" alt="Institución:" title="Selcciona una institucion"  name="institucion" id="institucion"/>
<input type="submit" value="Registrar" />
<input type="hidden" name="posted" value="yes" />

</form>
*/

?>
