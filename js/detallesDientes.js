var xmlrequest=new XMLHttpRequest();
xmlrequest.onreadystatechange=handleReply;

var xmlrequest2=new XMLHttpRequest();
xmlrequest2.onreadystatechange=handleReply2;

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
					  alert("C�digo no aceptado");
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

function handleReply2() {
	if(xmlrequest2.readyState==4) {
		var respuestaHTML = xmlrequest2.responseText;
		var nuevoValor = respuestaHTML.substring(0,2);
		//alert(nuevoValor); 
		var miTabla = document.getElementById("nuevos");
		var fila = document.createElement("tr"); 
		var celda1 = document.createElement('td');
		celda1.innerHTML = nuevoValor; 
		fila.appendChild(celda1); 
		miTabla.appendChild(fila);
		document.getElementById("box2").style.display = 'inline';
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
/**
 * Se valida el codigo del diente introducido, primero se ven si esta
 * dentro de los limites de los dientes 11-85. Despues revisamos si el
 * diente ya esta presente en la dentadura. Sino, calculamos su categoria
 * y validamos que el codigo sea correcto.
 * 
 * Finalmente se envia una llamada por AJAX para poner el nuevo diente en la dentadura
 * del paciente.
 */
function debugTexto() {
	//alert("Has hecho click sobre mi");
	$( "#box" ).dialog({
		title: 'Valor de caries',
		width: 500,
		height: 200,
		resizable:false,
		buttons: [
		  {
			  text: 'Aceptar',
			  click: function(){ 
				  
				  var limiteInferior = 11;
				  var limiteSuperior = 85;
				  var nuevoValor = Number(document.getElementById("nuevoValor").value);
				  //alert(typeof nuevoValor);
				  var dientesPresentes = new Array(); //arreglo con los elementos presentes
				  var encontrado = false; 
				  var categoriaNuevoDiente=0; 
				  var i=0; //Para los ciclos for
				  
				  if (nuevoValor < limiteInferior || nuevoValor > limiteSuperior ) {
					  alert("C&oacute;digo no aceptado. Valores en el rango 11-85");
					  $(this).dialog('close');
				  } 
				  else {
					  //8 FORS
					  for (i =11; i<19; i++) {
						try { 	
						  var valorPresente = document.getElementById(i.toString()).value;
						  dientesPresentes.push(i);
						}						
						catch (e) {}
					  }
					  for (i =21; i<29; i++) {
							try { 	
							  var valorPresente = document.getElementById(i.toString()).value;
							  dientesPresentes.push(i);
							}						
							catch (e) {}
					  }
					  for (i =31; i<39; i++) {
							try { 	
							  var valorPresente = document.getElementById(i.toString()).value;
							  dientesPresentes.push(i);
							}						
							catch (e) {}
					  }
					  for (i =41; i<49; i++) {
							try { 	
							  var valorPresente = document.getElementById(i.toString()).value;
							  dientesPresentes.push(i);
							}						
							catch (e) {}
					  }
					  for (i =51; i<56; i++) {
							try { 	
							  var valorPresente = document.getElementById(i.toString()).value;
							  dientesPresentes.push(i);
							}						
							catch (e) {}
					  }
					  for (i =61; i<66; i++) {
							try { 	
							  var valorPresente = document.getElementById(i.toString()).value;
							  dientesPresentes.push(i);
							}						
							catch (e) {}
					  }
					  for (i =71; i<76; i++) {
							try { 	
							  var valorPresente = document.getElementById(i.toString()).value;
							  dientesPresentes.push(i);
							}						
							catch (e) {}
					  }
					  for (i =81; i<86; i++) {
							try { 	
							  var valorPresente = document.getElementById(i.toString()).value;
							  dientesPresentes.push(i);
							}						
							catch (e) {}
					  }					  
					  //alert(dientesPresentes.length);
					  while ( !encontrado && dientesPresentes.length>0 ) {
							 if( Number(dientesPresentes.shift()) == nuevoValor)
								 encontrado=true;
						 }
						 if(encontrado) {
							 alert("Código esta presente ya");
							  $(this).dialog('close');
						 }
						 else {							 
							 encontrado = false;
							 i = 1;														 
							 while (!encontrado && i<9) {
								 tuMadre = (nuevoValor / (i*10)) < 1;								
								 if(tuMadre)
									 encontrado = true;
								 else {
									 categoriaNuevoDiente = i;
									 i=i+1;
								 }
							 }
							 
							 switch (categoriaNuevoDiente) {
							 
							 case 1:
								 if( nuevoValor >18) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 11-18 para este cuadrante");
									 $(this).dialog('close');
								 }
								 else {
									 //alert("Vamos bien");
									 //xmlrequest.open("GET",'incDentaduraDetalle.php?nuevoDiente='+nuevoValor+'&categoria='+categoriaNuevoDiente,true);
									 xmlrequest2.open("GET",'incDentaduraDetalle.php?nuevoDiente='+nuevoValor+'&categoria='+categoriaNuevoDiente,true);
									 xmlrequest2.send(null);
									 $(this).dialog('close');
								 }
							 break;
							 
							 case 2:
								 if( nuevoValor >28 || nuevoValor <21) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 21-28 para este cuadrante");
									 $(this).dialog('close');
								 }
								 else {
									 alert("Vamos bien");
								 }
							 break;
							 
							 case 3:
								 if( nuevoValor >38 || nuevoValor <31) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 31-38 para este cuadrante");
									 $(this).dialog('close');
								 }
								 else {
									 alert("Vamos bien");
								 }
							 break;
							 
							 case 4:
								 if( nuevoValor >48 || nuevoValor <41) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 41-48 para este cuadrante");
									 $(this).dialog('close');
								 }
								 else {
									 alert("Vamos bien");
								 }
							 break;
							 
							 case 5:
								 if( nuevoValor >55 || nuevoValor <51) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 51-55 para este cuadrante");
									 $(this).dialog('close');
								 }
								 else {
									 alert("Vamos bien");
								 }
							 break;
							 
							 case 6:
								 if( nuevoValor >65 || nuevoValor <61) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 61-65 para este cuadrante");
									 $(this).dialog('close');
								 }
								 break;
								 
							 case 7:
								 if( nuevoValor >75	|| nuevoValor <71) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 71-75 para este cuadrante");
									 $(this).dialog('close');
								 }
								 else {
									 alert("Vamos bien");
								 }
							 break;
								 
							 case 8:
								 if( nuevoValor >85 || nuevoValor <81) {
									 alert("C&oacute;digo no aceptado. Valores en el rango 81-85 para este cuadrante");
									 $(this).dialog('close');
								 }
								 else {
									 alert("Vamos bien");
								 }
							 break;
							 }
							 //alert( categoriaNuevoDiente);
							 // $(this).dialog('close');
						 }
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
