<?php

require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

//recibe info
$user = strip_tags($_POST['user']);

$query = @mysql_query("SELECT * FROM Capturista WHERE Usuario='".mysql_real_escape_string($user)."'");

echo "<table border='1'>
<tr>
<th>Identificador</th>
<th>Usuario</th>
<th>Password</th>
</tr>";

while($row = mysql_fetch_array($query))
{
	echo "<tr>";
	echo "<td>".$row['idCapturista']."</td>";
	echo "<td>".$row['Usuario']."</td>";
	echo "<td>".$row['Password']."</td>";
	echo "<tr />";
}
echo "</table>";

?>
