<?php

function validaNombre($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
	else {
		if(preg_match("/[ñÑáéíóú]+$/",$nombre))
			return "Escribe tu nombre sin acentos o ñ.\n";
		else
			if (! preg_match("/^[a-zA-Z\s]+$/",$nombre ))
				return "El campo Nombre solo contiene letras.\n";
			else 
				if (strlen($nombre) >30)
					return "Nombre demasiado largo";
	}
	return "";
}

function validaPaterno($nombre,$tipo) {
	if ($nombre =="") {
		if($tipo == 1)
			return "Favor de llenar el campo apellido paterno.\n";
		else
			return "Favor de llenar el campo apellido materno.\n";
	}
	else {
		if(preg_match("/[ñÑáéíóú]+$/",$nombre))
			return "Escribe tu apellido sin acentos o ñ.\n";
		else
			if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
				return "Los apellidos solo contienen letras	.\n";
			else
				if (strlen($nombre) >30)
					return "Apellido demasiado largo";
	}
	return "";
}

function validaEscuela($escuela) {
	if ($escuela == "") return "Introduce un identificador de escuela valido.\n";
	return "";
}

function dateCheck($date,$format='d/m/Y'){
	$parts = date_parse_from_format($format, $date);
	$anio = date("Y");
	if ($parts['year'] < 1992 || $parts['year'] > $anio)
		return "Año fuera del rango aceptable";
	
	if (!checkdate($parts['month'],$parts['day'],$parts['year'])) 
		return "Formato de fecha dd/m/a"; 				
}

function validaCedula($field) {
	if ($field =="") return "Favor de llenar el campo cedula.\n";
}

function validaColonia($field,$i) {
	if ($field =="")
		if ($i ==1)
		return "Favor de llenar el campo colonia.\n";
	else
		return "Favor de llenar el campo calle.\n";
}


function validaPass($field) {
	if($field == "") return "Introduce una contraseña.\n";
	else{
		if (strlen($field) < 5)
			return "El tamaño de la contraseña debe ser por lo menos de 5 caracteres.\n";
		else
			if (! preg_match("/[a-z]/",$field))
			return "La contraseña requiere por lo menos un caracter de [a-z].\n";
	}
	return "";
}

function validaClave($clave) {
	if (! preg_match("/^[0-9]+$/",$clave))
		return "La clave requiere digitos.\n";
	return "";
}

function validaEqualPass($field,$field2) {
	if($field !=$field2) return "Las contraseñas no son iguales.\n";
	return "";
}

function validaCorreo($field) {
	if ($field == "") return "Introduce un correo valido.\n";
	else if (!((strpos($field, ".") > 0) &&
			(strpos($field, "@") > 0))  ||
			preg_match("/[^a-zA-Z0-9.@_-]/",$field))
		return "La dirección de correo electrónico es inválida".$field."\n";
	return "";
}

function validaEqualCorreo($field,$field2){
	if($field !=$field2) return "Los correos no son iguales.\n";
	return "";
}

function validaConsultorio($consultorio) {
	if (! preg_match("/^[0-9]+$/",$consultorio))
		return "El numero postal requiere digitos.\n";
	return "";
}

function validaNombreConsultorio($nombre) {
	echo $nombre." Consultorio";
	if ($nombre =="") return "Favor de llenar el nombre del consultorio.\n";
	else {
		if (! preg_match("/^[a-zA-Z\s]+$/",$nombre ))
			return "El nombre del consultorio solo contiene letras sin acentos.\n";
		else {
			if (strlen($nombre) >30)
				return "Nombre de consultorio demasiado largo";
		}
	}
	return "";
}

function validateUser($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Usuario.\n";
	return "";
}

function validaPadre($field) {
	if ($field =="") return "Favor de llenar el campo de padre.\n";
}
?>