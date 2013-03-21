<?php
$servidor ='localhost'; 
$user = 'monty';
$pass = 'holygrail';
$name = 'newbasedientes';

//$mysqli = new mysqli("localhost", "monty", "holygrail", "newbasedientes");
//$mysqli->select_db("newbasedientes");
function conectar($servidor, $user, $pass, $name)
{
        $con = @mysql_connect($servidor, $user, $pass);
        @mysql_select_db($name, $con);
		//$mysqli = new mysqli("localhost", "monty", "holygrail", "newbasedientes");
}
?>
