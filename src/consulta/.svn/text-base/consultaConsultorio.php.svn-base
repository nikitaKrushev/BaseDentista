
<?php

require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

//recibe info
$name = strip_tags($_POST['name']);

$query = @mysql_query("SELECT * FROM Consultorio WHERE Nombre='".mysql_real_escape_string($name)."'");

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

?>
