var xmlrequest=new XMLHttpRequest();
xmlrequest.onreadystatechange=handleReply;

function myFunction(valorAntiguo,identificador,posicion,verdadero)
{
	//document.getElementById("antiguoValor").value=valorAntiguo;
	
	
	
	$( "#box" ).dialog({
		title: 'Valor de caries',
		width: 500,
		height: 200,
		resizable:false,
		buttons: [
		  {
			  text: 'Aceptar',
			  click: function(){ //Modificar el valor del diente en cuestion
				  var nuevoValor = document.getElementById("nuevoValor").value;
				  var renglones = document.getElementById(identificador).rows;
				  var actualizado = renglones[posicion].cells;
				  
				  /*
				   * Funcion para revisar que el codigo puesto sea el correcto, si no funciona, poner una
				   * alerta.
				   * 
				   * validaNumero();
				   */
				  
				  //document.write(actualizado[posicion]);
				  actualizado[1].innerHTML=nuevoValor;
				  //DEbe existir una funciona para cambiar el valor del nuevo valor a 0 y revisar que los numeros sean correctos
				  //Poner el valor de nuevo a 0;
				  document.getElementById("nuevoValor").value=0;
				
				  //updateValue(posicion,nuevoValor);
				  xmlrequest.open("GET",'detallesTrimestral.php?posicion='+verdadero+'&nuevoValor='+nuevoValor,true);
				  xmlrequest.send(null);				  
				   $(this).dialog('close');
				  
			  }
		  },
		  {
			  text: 'Cerrar',
			  click: function() {
				  $(this).dialog('close');
			  }
		  },
		]
	});
}

/*	Funcion para AJAX. Envio el dato a la pagina de detalles, actualizando la variable de
 * sesion
 *
 */ 

function updateValue(posicion, nuevoValor) {
	xmlrequest.open("GET",'detallesTrimestral.php?posicion='+posicion+'?nuevoValor='+nuevoValor,true);
	xmlrequest.send(null);
}

function handleReply () {
	if(xmlrequest.readyState==4) {
    	
	}
}

