<?php
function authSendEmail($from, $namefrom, $to, $nameto, $subject, $message)
{
	//SMTP + Detalles del servidor
	/* * * * Inicia configuraci�n * * * */
	$smtpServer = "mail.cartillabucaldigital.org";
	$port = "25";
	$timeout = "30";
	$username = "registro@cartillabucaldigital.org";
	$password = "l@c0yota719p0r";
	$localhost = "localhost";
	$newLine = "\r\n";
	/* * * * Termina configuraci�n * * * * */

	//Conexi�n al servidor en el puerto espec�fico
	$smtpConnect = fsockopen($smtpServer, $port, $errno, $errstr, $timeout);
	$smtpResponse = fgets($smtpConnect, 515);
	if(empty($smtpConnect))
	{
		$output = "Failed to connect: $smtpResponse";
		return $output;
	}
	else
	{
		$logArray['connection'] = "Connected: $smtpResponse";
	}

	//Solicitud de logueo
	fputs($smtpConnect,"AUTH LOGIN" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authrequest'] = "$smtpResponse";

	//Env�o de usuario
	fputs($smtpConnect, base64_encode($username) . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authusername'] = "$smtpResponse";

	//Env�o de password
	fputs($smtpConnect, base64_encode($password) . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['authpassword'] = "$smtpResponse";

	//Saludo a SMTP
	fputs($smtpConnect, "HELO $localhost" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['heloresponse'] = "$smtpResponse";

	//Env�a correo desde
	fputs($smtpConnect, "MAIL FROM: $from" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['mailfromresponse'] = "$smtpResponse";

	//Env�a correo a
	fputs($smtpConnect, "RCPT TO: $to" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['mailtoresponse'] = "$smtpResponse";

	//Cuerpo del mensaje
	fputs($smtpConnect, "DATA" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['data1response'] = "$smtpResponse";

	//Construyendo encabezados
	$headers = "MIME-Version: 1.0" . $newLine;
	$headers .= "Content-type: text/html; charset=iso-8859-1" . $newLine;
	$headers .= "To: $nameto <$to>" . $newLine;
	$headers .= "From: $namefrom <$from>" . $newLine;

	fputs($smtpConnect, "To: $to\nFrom: $from\nSubject: $subject\n$headers\n\n$message\n.\n");
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['data2response'] = "$smtpResponse";

	//Despedida a SMTP
	fputs($smtpConnect,"QUIT" . $newLine);
	$smtpResponse = fgets($smtpConnect, 515);
	$logArray['quitresponse'] = "$smtpResponse";
}

?>