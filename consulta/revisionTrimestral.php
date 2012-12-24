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

if(isset($_POST['posted'])) { 
	
	$texto = strip_tags($_POST['nombre']);
	$choice = $_POST['name'];
	$ninos = array();
	
	if($choice == "Busqueda por nombre") 
		$query = @mysql_query("SELECT * FROM Ninio WHERE Nombre='".mysql_real_escape_string($texto)."'");	
	else 
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($texto)."");
	//if($existe = @mysql_fetch_object($query)){
		while ($existe= @mysql_fetch_object($query))
			$ninos[] = $existe;				
		$size= count($ninos);	
}
else  {
	if(isset($_POST['detail'])) { //Selecciono el detalle
		echo $_POST['ctrl'];
		$_SESSION['idNino'] = $_POST['ctrl']; //Para que la pagina de detalles tenga la informacion que requiere.
		header("refresh:1;url=detallesTrimestral.php");
		
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Revisión Trimestral</title>
	
</head>
<body>

	<div style="color:#0000FF">
		
  		<form action="revisionTrimestral.php" method="post">
  			<input type="text"  value="Nombre o clave del morro" name="nombre" alt="Nombre:" title="Escribe el nombre del paciente" id="nombre" /><br>  					
  			<input type="radio" name="name" CHECKED value="Busqueda por nombre">Busqueda por nombre<br>
  			<input type="radio" name="name" value="Busqueda por clave">Busqueda por clave<br>  			  				
  			<input type="submit" value="Buscar" />								
			<input type="hidden" name="posted" value="yes" />
  		</form>
	</div>
	
	<div >
		<h3>This is a heading in a div element</h3>
  		<p>This is some text in a div element.</p>
  	<form name="submision" action="revisionTrimestral.php" method="post">
  		<table>
  			<tr>
  				<td> Identificador </td>
  				<td> Nombre </td>
  				<td> Apellido Paterno </td>
  				<td> Apellido Materno </td>
  				<td> Fecha de Nacimiento </td>
  				<td> Seleccionar </td>
  			</tr>	
  			<?php
  				if(isset($size)) {
				for($i=0; $i<$size; $i++) {
					//foreach ($ninos as $row) : 
			?>
  			<tr>
  				<td> <?php echo $ninos[$i]->idNinio; ?> </td>
  				<td> <?php echo $ninos[$i]->Nombre; ?> </td>
  				<td> <?php echo $ninos[$i]->ApellidoPaterno; ?> </td>
  				<td> <?php echo $ninos[$i]->ApellidoMaterno; ?> </td>
  				<td> <?php echo $ninos[$i]->FechaNaciemiento; ?> </td>  
  				<td> <input type="radio" name="ctrl" value="<?php echo $ninos[$i]->idNinio; ?>"></td>				
  			</tr>
  			 <?php } }?>
  		</table>
  	
  			<input type="hidden" name="detail" value="yes">	
  			<input type="submit" value="Detalles">
  		</form>
	</div>
</body>
</html>