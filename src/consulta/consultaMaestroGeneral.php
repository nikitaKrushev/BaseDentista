
<?php

require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

$query = @mysql_query("SELECT * FROM Maestro");

echo "<table border='1'>
<tr>
<th>Identificador</th>
<th>Nombre</th>
<th>Apellido Paterno</th>
<th>Escuela(ID)</th>
</tr>";

while($row = mysql_fetch_array($query))
{
	echo "<tr>";
	echo "<td>".$row['idMaestro']."</td>";
	echo "<td>".$row['Nombre']."</td>";
	echo "<td>".$row['ApellidoPaterno']."</td>";
	echo "<td>".$row['Escuela_idEscuela']."</td>";
	echo "<tr />";
}
echo "</table>";

