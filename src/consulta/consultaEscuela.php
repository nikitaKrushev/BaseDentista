
<?php

require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

//recibe info
$name = strip_tags($_POST['name']);

$query = @mysql_query("SELECT * FROM Escuela WHERE NombreEscuela='".mysql_real_escape_string($name)."'");

echo "<table border='1'>
<tr>
<th>Identificador</th>
<th>Nombre</th>
<th>Zona(ID)</th>
</tr>";

while($row = mysql_fetch_array($query))
{
	echo "<tr>";
	echo "<td>".$row['idEscuela']."</td>";
	echo "<td>".$row['NombreEscuela']."</td>";
	echo "<td>".$row['Zona_idZona']."</td>";
	echo "<tr />";
}
echo "</table>";

?>
