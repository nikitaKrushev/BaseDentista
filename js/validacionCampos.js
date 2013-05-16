
var xmlHttp = createXmlHttpRequestObject();//Variable de instancia que contiene el objeto XMLHttpRequest
var direccionServidor = "validaRegistroDentista.php"; //Ruta de la pagina lado servidor
var displayErrors = true; // Cuando es verdadera, se despliegan errores

var cache = new Array(); //La cola de peticiones

function createXmlHttpRequestObject() {
	var xmlHttp;// Se guarda una referencia 
	try {
		xmlHttp = new XMLHttpRequest();		
	} catch(e) {
		//Para internet explorer 6 o menor
		var xmlHttpVersions = new Array("MSXML2.XMLHTTP.6.0","MSXML2.XMLHTTP.5.0","MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP","MICROSOFT.XMLHTTP");
		for( var i =0; i<xmlHttpVersions.length && !xmlHttp; i++) {
			try {
				xmlHttp = new ActiveXObject(xmlHttpVersions[i]);
			} catch (e) {
				
			}
		}
	}
	if(!xmlHttp)
		displayError("Error creando objeto xmlHttpRequest");
	else
		return xmlHttp;
	
}

function displayError($message) {
	//Ignorar errores si displayErrors es falso
	if(displayErrors) {
		displayErrors = false;
		alert("Error en:\n "+ $message);
		setTimeout("validate();",10000);
	}
}

function validate(valor, id){
	if(xmlHttp) {
		if(id) { //Solamente ponemos valores no nulos dentro de la cola
			valor = encodeURIComponent(valor);//Codificacion de valores para enviarlos por una peticion HTTP
			id = encodeURIComponent(id);
			cache.push("Valor="+valor+"&id="+id); //Agregamos los valores a la cola			
		}
		try {
			if((xmlHttp.readyState == 4 || xmlHttp.readyState==0 ) && cache.length > 0) {//Enviar si el objeto no esta ocupado o existen elementos en la cola
				var cacheEntry = cache.shift(); //Recupera un elemento de la cola
				xmlHttp.open("POST",direccionServidor,true);
				xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
				xmlHttp.onreadystatechange = handleRequestStateChange;
				xmlHttp.send(cacheEntry);
			}
		}catch(e) {
			displayError(e.toString());
		}
				
		}
	}

function handleRequestStateChange() {
	if(xmlHttp.readyState == 4) {
		if (xmlHttp.status == 200) { //Si el estado de http es OK
			try {
				readResponse();
			}
			catch(e) {
				displayError(e.toString());
			}
		}
		else {
			displayError(xmlHttp.statusText);
		}
	}
}

function readResponse() {
	var respuesta = xmlHttp.responseText;
	//Error?
	if(respuesta.indexOf("ERRNO") >= 0 || respuesta.indexOf("error:") >=0 || respuesta.length ==0)
		throw(response.length == 0 ? "Server error." : respuesta);
	respuestaXml= xmlHttp.responseXML;
	xmlDoc = respuestaXml.documentElement;
	resultado = xmlDoc.getElementsByTagName("result")[0].firstChild.data;
	id = xmlDoc.getElementsByTagName("fieldid")[0].firstChild.data;
	//mensaje2 = document.getElementById(id+"Fail");
	//alert(resultado+" "+id+"Fail");
	//mensaje2.classname= (resultado == "0") ? "error" : "hidden"; //Se muestra o no el error
	clase = (resultado == "0") ? "error" : "hidden";
	document.getElementById(id+"Fail").setAttribute("class",clase);
	setTimeout("validate();",500); //Validamos de nuevo por si hay elementos en la cola
}

//Pone el focus al primer elemento de la forma
function setFocus() { 
	document.getElementById("nombre").focus();
	//document.getElementById("usuario").focus();
}



