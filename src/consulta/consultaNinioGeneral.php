
<?php

require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

$query = @mysql_query("SELECT * FROM Ninio");

echo "<table border='1'>
<tr>
<th>Identificador</th>
<th>Primer Nombre</th>
<th>Segundo Nombre</th>
<th>Apellido Paterno</th>
<th>Apellido Materno</th>
<th>Fecha de Nacimiento</th>
<th>Grupo(ID)</th>
</tr>";

while($row = mysql_fetch_array($query))
{
	echo "<tr>";
	echo "<td>".$row['idNinio']."</td>";
	echo "<td>".$row['Nombre']."</td>";
	echo "<td>".$row['2d0_Nombre']."</td>";
	echo "<td>".$row['ApellidoPaterno']."</td>";
	echo "<td>".$row['ApellidoMaterno']."</td>";
	echo "<td>".$row['FechaNacimiento']."</td>";
	echo "<td>".$row['Grupo_idGrupo']."</td>";
	echo "<tr />";
}
echo "</table>";

?>
