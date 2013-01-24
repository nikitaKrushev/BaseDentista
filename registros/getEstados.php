<?php
require_once('../funciones.php');
conectar($servidor, $user, $pass, $name);

$opcionSeleccionada=$_GET["valor"];

$query = mysql_query("SELECT Nombre FROM Estado where Pais_Nombre='".$opcionSeleccionada."'");

echo "<select name='estado' id='estado'>";
while($registro=mysql_fetch_row($query))
{
	// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
	$registro[0]=htmlentities($registro[0]);
	// Imprimo las opciones del select
	echo "<option value='".$registro[0]."'>".$registro[0]."</option>";
}
echo "</select>";


?>