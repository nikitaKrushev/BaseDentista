/*$(function() {

	$( "#box" ).dialog({
		title: 'Mommy milk',
		width: 500,
		height: 300,
		resizable:false,
		buttons: [
		  {
			  text: 'Okay',
			  click: function(){
				  alert('You said okay');
			  }
		  },
		  {
			  text: 'Nay!',
			  click: function() {
				  $(this).dialog('close');
			  }
		  },
		]
	});
});
*/
function myFunction(valorAntiguo,identificador,posicion)
{
	//alert("Hello World!");
	//alert(valor);
	//<input id="antiguoValor" text=type="text" >
	//var texto= valorAntiguo;
	//document.getElementById("antiguoValor").value='12';
	document.getElementById("antiguoValor").value=valorAntiguo;	
	//document.write(valorAntiguo);
	$( "#box" ).dialog({
		title: 'Valor de caries',
		width: 500,
		height: 300,
		resizable:false,
		buttons: [
		  {
			  text: 'Aceptar',
			  click: function(){ //Modificar el valor del diente en cuestion
				  //document.write(identificador);
				  var nuevoValor = document.getElementById("nuevoValor").value;
				  var renglones = document.getElementById(identificador).rows;
				  //document.write(identificador);
				  //document.write(renglones[1]);
				  var actualizado = renglones[1].cells;
				  actualizado[posicion].innerHTML=nuevoValor;
				  //.rows[posicion].value = nuevoValor;
				  //document.write(document.getElementById(identificador).rows[posicion].value);
				  //document.write( document.getElementById(identificador).value);
				  $(this).dialog('close');
				  //alert('You said okay');
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
