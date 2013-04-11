<?php
//require_once('config.php');

class Validate {
	
	private $msqliA; //Conexion a la base de dtos

	function __construct() {
		$this->msqliA = new mysqli('localhost','monty','holygrail','newbasedientes');
	}
	
	function __destroy() {
		$this->msqli->close();
	}
	
	public function validateAJAX($input, $id) {
		switch ($id) {
			
			case 'nombre':
				return $this->validaNombre($input);
			break;
				
			case 'apaterno':
				return $this->validaPaterno($input);
			break;
					
			case 'amaterno':
				return $this->validaMaterno($input);
			break;
			
			case 'cedula':
				return $this->validaCedula($input);
			break;
			
			case 'usuario':
				return $this->validateUser($input);
			break;

			case 'password':
				return $this->validaPass($input);
			break;

			case 'password2':
				return 1;
			break;
			
			case 'correo':
				return $this->validaCorreo($input);
			break;
			
			case 'correo2':
				return 1;
			break;
		
			case 'nombreCons':
				return $this->validaNombreConsultorio($input);
			break;
				
			case 'telefono':
				return $this->validaTelefono($input);
			break;
				
			case 'colonia':
				return $this->validaColonia($input);
			break;
				
			case 'calle':
				return $this->validaCalle($input);
			break;
				
			case 'numPostal':
				return $this->validaNumPostal($input);	
			break;
		}
	}
	
	function validaNombre($nombre) {
		if ($nombre =="") return "Favor de llenar el campo Nombre.\n";
		else {
			if(preg_match("/[ñÑáéíóú]+$/",$nombre))
				return 0;
			else
				if (! preg_match("/^[a-zA-Z\s]+$/",$nombre ))
				return 0;
			else
				if (strlen($nombre) >30)
				return 0;
		}
		return 1;
	}
	
	function validaPaterno($nombre) {
		if ($nombre =="") 	
			return 0;			
		else {
			if(preg_match("/[ñÑáéíóú]+$/",$nombre))
				return 0;
			else
				if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
					return 0;
			else
				if (strlen($nombre) >30)
					return 0;
		}
		return 1;
	}
	
	function validaMaterno($nombre) {
		if ($nombre =="")
			return 0;
		else {
			if(preg_match("/[ñÑáéíóú]+$/",$nombre))
				return 0;
			else
				if (! preg_match("/^[a-zA-Z]+$/",$nombre ))
				return 0;
			else
				if (strlen($nombre) >30)
				return 0;
		}
		return 1;
	}
	
	function validaCedula($field) {
		if ($field =="") 
			return 0;
		else
			return 1;
	}
	
	function validateUser($nombre) {
		$nombre = $this->msqliA->real_escape_string(trim($nombre));
						
		$query = $this->msqliA->query("SELECT * FROM Padre WHERE Usuario='".mysql_real_escape_string($nombre)."'");
		
		if($this->msqliA->affected_rows > 0)	
			return 0;
		else {
			$query = $this->msqliA->query('SELECT * FROM Dentista WHERE Usuario="'.mysql_real_escape_string($nombre).'"');
			if($this->msqliA->affected_rows > 0)
				return 0;
			else {
				//$query = @mysql_query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($nombre)."'");
				//if($existe = @mysql_fetch_object($query))
				$query = $this->msqliA->query("SELECT * FROM Administrador WHERE Usuario='".mysql_real_escape_string($nombre)."'");
				if($this->msqliA->affected_rows > 0)
					return 0;
				else {
					//$query = @mysql_query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($nombre)."'");
					//if($existe = @mysql_fetch_object($query))
					$query = $this->msqliA->query("SELECT * FROM ProfesionalSalud WHERE Usuario='".mysql_real_escape_string($nombre)."'");
					if($this->msqliA->affected_rows > 0)
						return 0;
					else {
						//$query = @mysql_query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($nombre)."'");
						//if($existe = @mysql_fetch_object($query))
						$query = $this->msqliA->query("SELECT * FROM Director WHERE idDirector='".mysql_real_escape_string($nombre)."'");
						if($this->msqliA->affected_rows > 0)
							return 0;
						else {
							//$query = @mysql_query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($nombre)."'");
							//if($existe = @mysql_fetch_object($query))
							$query = $this->msqliA->query("SELECT * FROM Maestro WHERE Usuario='".mysql_real_escape_string($nombre)."'");
							if($this->msqliA->affected_rows > 0)
								return 0;							
						}
					}
				}
				
			}
		}
		
		if ($nombre =="") return 0;
		else
			if(strlen($nombre) >20)
			return 0;
		return 1;
	}
	
	function validaPass($field) {
		if($field == "") return 0;
		else{
			if (strlen($field) < 5)
				return 0;
			else
				if (! preg_match("/[a-z]/",$field))
				return 0;
		}
		return 1;
	}
	
	function validaTelefono($clave) {
		if (! preg_match("/^[0-9]+$/",$clave))
			return 0;
		return 1;
	}
	
	function validaCorreo($field) {
		if ($field == "") return 0;
		else if (!((strpos($field, ".") > 0) &&
				(strpos($field, "@") > 0))  ||
				preg_match("/[^a-zA-Z0-9.@_-]/",$field))
			return 0;
		return 1;
	}
	
	function validaNombreConsultorio($nombre) {
		if ($nombre =="") return 0;
		else {
			if (! preg_match("/^[a-zA-Z\s]+$/",$nombre ))
				return 0;
			else {
				if (strlen($nombre) >30)
					return 0;
			}
		}
		return 1;
	}
	
	function validaNumPostal($consultorio) {
		if (! preg_match("/^[0-9]+$/",$consultorio))
			return 0;
		return 1;
	}
	
	function validaColonia($field) {
		if ($field =="") 		
			return 0;					
		else {
			if(strlen($field) > 30)
				return 0;
		}
		return 1;
	}
	
	function validaCalle($field) {
		if ($field =="")
			return 0;
		else {
			if(strlen($field) > 30)
				return 0;
		}
		return 1;
	}
	
	
	
}
?>