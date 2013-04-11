<?php
//sesion_start(); //Tenemos la sesion
require_once ('validateReg.class.php');

$validator = new Validate(); //Instancia de validar
$respuesta =
'<?xml version="1.0" encoding="UTF-8" standalone="yes"?> '.
'<response>'.
	'<result>'.
		$validator->validateAjax($_POST['Valor'],$_POST['id']).
	'</result>'.
	'<fieldid>'.
		$_POST['id'].
	'</fieldid>'.
'</response>';
if(ob_get_length()) ob_clean();
header('Content-Type: text/xml');
echo $respuesta;
?>