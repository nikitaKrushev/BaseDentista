<?php
require_once('../funciones.php');
conectar('localhost', 'monty', 'holygrail', 'BaseDientes');

//recibe info
$clave = strip_tags($_POST['clave']);
$nombre = strip_tags($_POST['nombre']);
$pais = strip_tags($_POST['pais']);

$query = @mysql_query("SELECT * FROM Estado WHERE idEstado='".mysql_real_escape_string($clave).'")');

if($existe = @mysql_fetch_object($query)){
	echo 'El estado '.$clave.' ya existe';
}else{
	
	$meter=@mysql_query('INSERT INTO Estado (idEstado, Nombre, Pais_idPais) values ("'.mysql_real_escape_string($clave).'","'.mysql_real_escape_string($nombre).'","'.mysql_real_escape_string($pais).'")');

	if($meter){
		echo 'Estado registrado con exito';
	}else{
		echo 'Hubo un error';
	}
}

?>