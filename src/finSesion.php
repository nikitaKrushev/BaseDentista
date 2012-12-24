<?php
session_start();
unset($_SESSION['uid']);
unset($_SESSION['pwd']);
unset($_SESSION['check']);
unset($_SESSION['type']);

session_destroy();

header( "refresh:3;url=index.php" ); //Redireccionar a pagina
echo "Hasta luego\n";
echo "En 3 segundos te redireccionaremos a la pagina de inicio";

?>