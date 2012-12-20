<?php
session_start();
if ($_SESSION['type'] != 1) {
	echo("Contenido Restringido");
	switch($_SESSION['type']) {
			
		case 2:
			header( "refresh:3;url=padrePrincipal.html" ); //Redireccionar a pagina
			break;
	
		case 3:
			header( "refresh:3;url=maestroPrincipal.html" ); //Redireccionar a pagina
			break;
	}

}	
	?>
		