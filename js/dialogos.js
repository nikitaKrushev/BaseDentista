
function registroExitoso(clave,nombre,apellidoPaterno, apellidoMaterno) {
	
}

function registroError(error) {
	$( "#box" ).dialog({
		title: 'Error en el registro',
		width: 500,
		height: 200,
		resizable:false,
		buttons: [	
		          {
					  text: 'Cerrar',
					  click: function() {
						  $(this).dialog('close');
					  }
				  },
				]
	});
}