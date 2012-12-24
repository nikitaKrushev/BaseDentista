<?php 
if(isset($_POST['posted'])) {

	require_once('../funciones.php');
	conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

	//recibe info
	$clave = strip_tags($_POST['clave']);
	$nombre = strip_tags($_POST['nombre']);
	$estado = strip_tags($_POST['estado']);
	$mensaje = "Registro no exitoso, se encontraron los siguientes<br />
	errores en el formulario:";

	//Para validacion
	$fail = validaClave($clave);
	$fail .= validaNombre(trim($nombre));
	$fail .= validaEstado($estado);

	echo "<html><head><title>Registro Ciudad</title>";
	if($fail == "") {
		 
		$query = @mysql_query("SELECT * FROM Ciudad WHERE idCiudad=".mysql_real_escape_string($clave));
		if( $existe = @mysql_fetch_object($query)){
			echo 'La ciudad '.$clave.' ya existe';
		}else{

			$meter=@mysql_query('INSERT INTO Ciudad (idCiudad, Nombre, Estado_idEstado) values ("'.mysql_real_escape_string($clave).'","'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($estado).'")');

			if($meter){
				echo "</head><body>	Datos registrados con exito!</body></html>";
			}else{
				echo 'Hubo un error';
			}
		}

		exit;
	}
}

else {
	$clave = "Clave";
	$nombre = "Nombre";
	$estado = "Estado";
	
}

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

function validaEstado($estado) {
	if (! preg_match("/^[0-9]+$/",$estado))
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
						<p>Página de registro de Ciudad</p>
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
								fail += validateEstado(form.estado.value);
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
							
							function validateEstado(field) {
								if (! /^[0-9]+$/.test(field))
									return "El campo Estado requiere digitos.\n";					
								return "";
							}
						</script>
								<form action="#" method="post" onsubmit="return validate(this)">
									<input type="text" value="<?php echo $clave;?>" alt="Clave:"
										title="Escribe una clave" name="clave" id="clave" /> <input
										type="text" value="<?php echo $nombre;?>" alt="Nombre:"
										title="Escribe un nombre" name="nombre" id="nombre" /> <input
										type="text" value="<?php echo $estado;?>" name="estado"
										alt="Estado:" title="Selecciona un estado de la lista"
										id="estado" /> <input type="submit" value="Estados" alt="Estado:"
										title="Selecciona un estado de la lista POP UP O ALGO ASI"
										id="estado"> </input> <input type="submit" value="Registrar" />
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

//recibe info
$clave = strip_tags($_POST['clave']);
$nombre = strip_tags($_POST['nombre']);
$estado = strip_tags($_POST['estado']);
$mensaje = "Registro no exitoso, se encontraron los siguientes<br />
errores en el formulario:";

//Para validacion
$fail = validaClave($clave);
$fail .= validaNombre(trim($nombre));
$fail .= validaEstado($estado);

echo "<html><head><title>Registro Ciudad</title>";
if($fail == "") {
	
$query = @mysql_query("SELECT * FROM Ciudad WHERE idCiudad=".mysql_real_escape_string($clave));
if( $existe = @mysql_fetch_object($query)){
echo 'La ciudad '.$clave.' ya existe';
}else{
	
$meter=@mysql_query('INSERT INTO Ciudad (idCiudad, Nombre, Estado_idEstado) values ("'.mysql_real_escape_string($clave).'","'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($estado).'")');

if($meter){
echo "</head><body>	Datos registrados con exito!</body></html>";
}else{
echo 'Hubo un error';
}
}

exit;
}
}

//Output HTML y JavaScript

echo <<<_END
<script type="text/javascript">
function validate(form){
fail = validateClave(form.clave.value);
fail += validateNombre(form.nombre.value);
fail += validateEstado(form.estado.value);
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

function validateEstado(field) {
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
<form action="regCiudad.php" method="post" onsubmit="return validate(this)">
<tr><td>Clave:</td><td> </label> <input type="text" name="clave" size="20" id="clave" value="$clave" />

<tr><td>Nombre:</td><td> </label> <input type="text" name="nombre" size="20" id="nombre" value="$nombre"/>

<tr><td>Estado (Clave):</td><td> </label> <input type="text" name="estado" size="20" id="estado" value="$estado" />

<tr><td><input type="submit" value="Registrar" /></td><td>
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

function validaEstado($estado) {
if (! preg_match("/^[0-9]+$/",$estado))
	return "El estado requiere digitos.\n";
return "";
}
*/
?>