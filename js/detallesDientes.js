var xmlrequest=new XMLHttpRequest();
xmlrequest.onreadystatechange=handleReply;

function myFunction(valorAntiguo,identificador,arregloCuadrante,posicion) {
	
	$( "#box" ).dialog({
		title: 'Valor de caries',
		width: 500,
		height: 200,
		resizable:false,
		buttons: [
		  {
			  text: 'Aceptar',
			  click: function(){ 
				  
				  //document.write(valorAntiguo+" VA "+identificador+" ID "+arregloCuadrante+" AC "+posicion+ " POS");
				  var nuevoValor = document.getElementById("nuevoValor").value;
				  if( nuevoValor <0 || nuevoValor>6 || isNaN(nuevoValor) || nuevoValor=="" || nuevoValor %1!=0) {
					  alert("Código no aceptado");
					  $(this).dialog('close');
				  }
				  
				  else {
					  document.getElementById("nuevoValor").value=0;
					  
					  //alert("Buen trabajo");
					  xmlrequest.open("GET",'detallesTrimestral.php?arregloCuadrante='+arregloCuadrante+'&posicion='+posicion+'&identificador='+identificador+'&nuevoValor='+nuevoValor,true);
					  xmlrequest.send(null);
					  $(this).dialog('close');
				  }							 			
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

function handleReply () {
	if(xmlrequest.readyState==4) {	
				
		var respuestaHTML = xmlrequest.responseText;
		//alert(respuestaHTML);
		var nuevoValor = respuestaHTML.substring(0,1);
		//alert(nuevoValor);
		var identif = respuestaHTML.substring(1,3);
		//alert(identif);
		
		//document.getElementById(identif).InnerHTML="lol";
		switch (parseInt(nuevoValor)) {
		
		case 0:
			document.getElementById(identif).setAttribute("class","Sano");
			
		break;
			
		case 1:
			document.getElementById(identif).setAttribute("class","ManchaSeco");
		break;
		
		case 2:
			document.getElementById(identif).setAttribute("class","ManchaHumedo");
		break;
		
		case 3:
			document.getElementById(identif).setAttribute("class","MicroCavidad");
		break;
		
		case 4:
			document.getElementById(identif).setAttribute("class","Sombra");
		break;
		
		case 5:
			document.getElementById(identif).setAttribute("class","ExposicionMenor");
		break;
		
		case 6:
			document.getElementById(identif).setAttribute("class","ExposicionMayor");
		break;
		
		
		}	
		
	}
}

function pintaDientes(Valor,identifi) {
	switch (parseInt(Valor)) {
	
	case 0:
		document.getElementById(identif).setAttribute("class","Sano");
		
	break;
		
	case 1:
		document.getElementById(identif).setAttribute("class","ManchaSeco");
	break;
	
	case 2:
		document.getElementById(identif).setAttribute("class","ManchaHumedo");
	break;
	
	case 3:
		document.getElementById(identif).setAttribute("class","MicroCavidad");
	break;
	
	case 4:
		document.getElementById(identif).setAttribute("class","Sombra");
	break;
	
	case 5:
		document.getElementById(identif).setAttribute("class","ExposicionMenor");
	break;
	
	case 6:
		document.getElementById(identif).setAttribute("class","ExposicionMayor");
	break;	
	
	}	
}

