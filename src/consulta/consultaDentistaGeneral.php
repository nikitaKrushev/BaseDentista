
<?php

require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

$query = @mysql_query("SELECT * FROM Dentista");

echo "<table border='1'>
<tr>
<th>Identificador</th>
<th>Primer  Nombre</th>
<th>Segundo Nombre</th>
<th>Apellido Paterno</th>
<th>Apellido Materno</th>
<th>Cedula</th>
<th>Usuario</th>
</tr>";

while($row = mysql_fetch_array($query))
{
	echo "<tr>";
	echo "<td>".$row['idDentista']."</td>";
	echo "<td>".$row['Nombre']."</td>";
	echo "<td>".$row['2d0_Nombre']."</td>";
	echo "<td>".$row['ApellidoPaterno']."</td>";
	echo "<td>".$row['ApellidoMaterno']."</td>";
	echo "<td>".$row['Cedula']."</td>";
	echo "<td>".$row['Usuario']."</td>";
	echo "<tr />";
}
echo "</table>";

?>
