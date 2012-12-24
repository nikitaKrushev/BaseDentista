<?php

require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

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

	$query = @mysql_query("SELECT * FROM Consultorio");
	
	echo "<table border='1'>
	<tr>
	<th>Identificador</th>
	<th>Direccion(ID)</th>
	<th>Nombre</th>
	<th>Horas de consulta</th>
	<th>Clinica(ID)</th>
	</tr>";
	
	while($row = mysql_fetch_array($query))
	{
		echo "<tr>";
		echo "<td>".$row['idConsultorio']."</td>";
		echo "<td>".$row['Direccion_idDireccion']."</td>";
		echo "<td>".$row['Nombre']."</td>";
		echo "<td>".$row['HorasConsulta']."</td>";
		echo "<td>".$row['Clinica_idClinica']."</td>";
		echo "<tr />";
	}
	echo "</table>";
}
?>
