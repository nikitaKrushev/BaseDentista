<?php
include '../accesoDentista.php';
require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');
//Checamos si hay una session vacia o si ya hay una sesion
//Checamos si hay una session vacia o si ya hay una sesion
if ($_SESSION['type'] != 1) {
	echo("Contenido Restringido");
	switch($_SESSION['type']) {
			
		case 2:
			header( "refresh:3;url=padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3:
			header( "refresh:3;url=maestroPrincipal.php" ); //Redireccionar a pagina
			break;
	}

}
else {

	$query = @mysql_query("SELECT * FROM Clinica");
	
	echo "<table border='1'>
	<tr>
	<th>Identificador</th>
	<th>Nombre</th>
	<th>Institucion(ID)</th>
	</tr>";
	
	while($row = mysql_fetch_array($query))
	{
		echo "<tr>";
		echo "<td>".$row['idClinica']."</td>";
		echo "<td>".$row['NombreClinica']."</td>";
		echo "<td>".$row['Institucion_idInstitucion']."</td>";
		echo "<tr />";
	}
	echo "</table>";
}
?>
