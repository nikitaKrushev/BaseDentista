<?php
include '../accesoDentista.php';


if ($_SESSION['type'] != 6 && $_SESSION['type'] != 1 ) { //Checamos si hay una session vacia o si ya hay una sesion
	echo("Contenido Restringido");
	switch($_SESSION['type']) {

		case 2: //Padre
			header( "refresh:3;url=../principales/padrePrincipal.php" ); //Redireccionar a pagina
			break;

		case 3://Maestro
			header("refresh:3;url=../principales/padrePrincipal.php");
			break;

		case 4://Director
			header("refresh:3;url=../principales/directorPrincipal.php");
			break;

		case 5://Admin
			header("refresh:3;url=../principales/profesionalPrincipal.php");
			break;
	}
	exit;
}
echo $_SESSION['idNino'];

	//Recuperar el objeto nino
	$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
	$existe= @mysql_fetch_object($query);
	if($existe->UltimaRevision == 0) { //Generar una nueva revision			
		for ($j=0; $j<35; $j++ ) 
			$dientes[$j] = 0; //Valor default de los dientes
		
		
	} else { //Abrir la mas reciente revision del nino
		$query = @mysql_query("SELECT * FROM Ninio WHERE idNinio=".mysql_real_escape_string($_SESSION['idNino'])."");
		$existe= @mysql_fetch_object($query);		
		$query2 = @mysql_query("SELECT * FROM Dentadura WHERE idDentadura=".mysql_real_escape_string($existe->UltimaRevision)."");
		$dentadura = @mysql_fetch_object($query2);
		$dientes[] = (array) $dentadura;	//A partir de 1 es indice de dientes
	}		
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Detalles dentadura</title>
</head>
<body>

		<form action="#" method="post">
  		<table>
  			<tr>
  				<td> ID </td>
  				<td> InFS1 </td>
  				<td> InFS2 </td>
  				<td> InFB1 </td>
  				<td> InFB2 </td>
  				<td> InLS1 </td>
  				<td> InLS2 </td>
  				<td> InLB1 </td>
  				<td> InLB2</td>
  				<td>  	CS1</td>
  				<td>  	CS2</td>
  				<td>CB1 </td>
  				<td>CB2 </td>
  				<td>PPS1 </td>
  				<td>PPS2 </td>
  				<td> 	PPB1 </td>
  				<td> 	PPB2 </td>
  				<td> SPS1</td>
  				<td>SPS2 </td>
  				<td>SPB1 </td>
  				<td>SPB2 </td>
  				<td>MPS1 </td>
  				<td>MPS2 </td>
  				<td>MPB1 </td>
  				<td>MPB2 </td>
  				<td> MSS1</td>
  				<td>MSS2 </td>
  				<td>MSB1 </td>
  				<td>MSB2 </td>
  				<td>MTS1 </td>
  				<td>MTS2 </td>
  				<td>MTB1 </td>
  				<td>MTB2 </td>
  				<td>EXTRA </td>  		
  			</tr>	
  			<tr>
  				<td>  <?php echo $dientes[0];?> </td>
  				<td>  <?php echo $dientes[1];?> </td>
  				<td>  <?php echo $dientes[2];?> </td>
  				<td>  <?php echo $dientes[3];?> </td>
  				<td>  <?php echo $dientes[4];?> </td>
  				<td>  <?php echo $dientes[5];?> </td>
  				<td>  <?php echo $dientes[6];?> </td>
  				<td>  <?php echo $dientes[7];?> </td>
  				<td>  <?php echo $dientes[8];?> </td>
  				<td>  <?php echo $dientes[9];?> </td>
  				<td>  <?php echo $dientes[10];?> </td>
  				<td>  <?php echo $dientes[11];?> </td>
  				<td>  <?php echo $dientes[12];?> </td>
  				<td>  <?php echo $dientes[13];?> </td>
  				<td>  <?php echo $dientes[14];?> </td>
  				<td>  <?php echo $dientes[15];?> </td>
  				<td>  <?php echo $dientes[16];?> </td>
  				<td>  <?php echo $dientes[17];?> </td>
  				<td>  <?php echo $dientes[18];?> </td>
  				<td>  <?php echo $dientes[19];?> </td>
  				<td>  <?php echo $dientes[20];?> </td>
  				<td>  <?php echo $dientes[21];?> </td>
  				<td>  <?php echo $dientes[22];?> </td>
  				<td>  <?php echo $dientes[23];?> </td>
  				<td>  <?php echo $dientes[24];?> </td>
  				<td>  <?php echo $dientes[25];?> </td>
  				<td>  <?php echo $dientes[26];?> </td>
  				<td>  <?php echo $dientes[27];?> </td>
  				<td>  <?php echo $dientes[28];?> </td>
  				<td>  <?php echo $dientes[29];?> </td>
  				<td>  <?php echo $dientes[30];?> </td>	
  				<td>  <?php echo $dientes[31];?> </td>
  				<td>  <?php echo $dientes[32];?> </td>
  				<td>  <?php echo $dientes[33];?> </td>
  				<td>  <?php echo $dientes[34];?> </td>	
  			</tr>  			
  		</table>
  	
  			<input type="hidden" name="detail" value="yes">	
  			<input type="submit" value="Detalles">
  		</form>

</body>
</html>