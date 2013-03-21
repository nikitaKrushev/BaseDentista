<?php

/**
 * Nombre: validaNombre
 * Descripcion: Revisa que el nombre no este vacio.
 * Ademas sin acentos o n. Tambien la longitud del nombre.
 * Si todo se valida, se regresa una cadena vacia.
 * @param unknown $nombre
 * @return string
 */
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
/**
 * Nombre: validaPaterno
 * Descripcion: Revisa que el nombre no este vacio.
 * Ademas sin acentos o n. Tambien la longitud del nombre.
 * Si todo se valida, se regresa una cadena vacia.
 * @param unknown $nombre
 * @param unknown $tipo
 * @return string
 */
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

/**
 * Nombre: validaEscuela
 * Descripcion: Revisa que el nombre no este vacio.
 * Tambien la longitud del nombre.
 * Si todo se valida, se regresa una cadena vacia.
 * @param unknown $escuela
 * @return string
 */
function validaEscuela($escuela) {
	if ($escuela == "") return "Introduce un identificador de escuela valido.\n";
	else 
		if(strlen($escuela) > 30)
			return "Identificador de escuela demasiado largo";
}

function dateCheck($date){
	$format='d/m/Y';
	$parts = date_parse_from_format($format, $date);
	$anio = date("Y");
	if ($parts['year'] < 1992 || $parts['year'] > $anio)
		return "Año fuera del rango aceptable";
	
	if (!checkdate($parts['month'],$parts['day'],$parts['year'])) 
		return "Formato de fecha dd/m/a"; 				
}

/**
 * Nombre: revisaFecha
 * Descripcion: Revisa que la fecha sea valida.
 * Si todo se valida, se regresa una cadena vacia.
 * De lo contrario una cadena de error.
 * Para versiones de php < 5.3
 * @param unknown $dia
 * @param unknown $mes
 * @param unknown $anio
 * @return string
 */
function revisaFecha($dia,$mes,$anio){
	if (!checkdate($mes,$dia,$anio)) {
		echo "Fecha no aceptada";
		return "Fecha no aceptada";
	}
}

function validaCedula($field) {
	if ($field =="") return "Favor de llenar el campo cedula.\n";
}

function validaColonia($field,$i) {
	if ($field =="") {
		if ($i ==1)
			return "Favor de llenar el campo colonia.\n";
		else
			return "Favor de llenar el campo calle.\n";
	}
	else {
		if(strlen($field) > 30)
			return "Descripción de colonia muy extensa";
	}
}

/**
 * Nombre: validaPass
 * Descripcion: Revisa que el password no este vacio.
 * Ademas con al menos 5 caracteres. Y un caracter a-z.
 * Si todo se valida, se regresa una cadena vacia.
 * @param unknown $field
 * @return string
 */
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

/**
 * Nombre: validaClave
 * Descripcion: Revisa que la clave.
 * Ademas solamente con digitos.
 * Si todo se valida, se regresa una cadena vacia.
 * @param unknown $clave
 * @return string
 */
function validaClave($clave) {
	if (! preg_match("/^[0-9]+$/",$clave))
		return "La clave requiere digitos.\n";
	return "";
}

function validaEqualPass($field,$field2) {
	if($field !=$field2) return "Las contraseñas no son iguales.\n";
	return "";
}

/**
 * Nombre: validaCorreo
 * Descripcion: Revisa que el correo no este vacio.
 * Ademas con los caracteres (@-_.). Tambien la longitud del nombre.
 * Si todo se valida, se regresa una cadena vacia.
 * @param unknown $field
 * @return string
 */
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

function validaNombreEscuela($nombre) {
	if ($nombre =="") return "Favor de llenar el nombre de la escuela.\n";
	else {
		if (! preg_match("/^[a-zA-Z\s]+$/",$nombre ))
			return "El nombre de la escuela solo contiene letras sin acentos.\n";
		else {
			if (strlen($nombre) >30)
				return "Nombre de escuela demasiado largo";
		}
	}
	return "";
}

function validateUser($nombre) {
	if ($nombre =="") return "Favor de llenar el campo Usuario.\n";
	else
		if(strlen($nombre) >20)
			return "Usuario demasiado largo";
}

function validaPadre($field) {
	if ($field =="") return "Favor de llenar el campo de padre.\n";
	else 
		if(strlen($field) >30)
			return "Campo de padre demasiado largo";
}
?>