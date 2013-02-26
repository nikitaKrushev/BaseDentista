/**
 * Para AJAX.
 * 
 * @param valorAntiguo
 * @param identificador
 * @param posicion
 * @param verdadero
 */

var xmlrequest=new XMLHttpRequest();
xmlrequest.onreadystatechange=handleReply;

function myFunction(valorAntiguo,identificador,arregloCuadrante,posicion)
{	
	  xmlrequest.open("GET",'registroDientes.php?arregloCuadrante='+arregloCuadrante+'&posicion='+posicion+'&identificador='+identificador,true);
	  xmlrequest.send(null);	
}

function handleReply () {
	if(xmlrequest.readyState==4) {	
				
		var respuestaHTML = xmlrequest.responseText;
		//alert(respuestaHTML);
		var respuesta = respuestaHTML.substring(1,2);
		//alert(respuesta);
		var identif = respuestaHTML.substring(2,4);
		//alert(identif);
		
		if(respuesta==0)
			document.getElementById(identif).setAttribute("class","Presente");
		else
			document.getElementById(identif).setAttribute("class","noPresente");		
	}
}


