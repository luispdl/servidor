<?php namespace Modelos;

	class Usuario {

		public $nombre;
		public $apellido;
		public $nombre_usuario;
		public $password;
		public $tipo;
		private $con;

		public function __construct(){
			$this->con = new Conexion();
		}

		public static function obtenerPassword($usuario) {
			$con = New Conexion();
			$sql = "SELECT password, nombre_cuenta_usuario, primer_ingreso FROM usuarios WHERE nombre_cuenta_usuario = '$usuario'";
			$resultado = $con->consultaRetorno($sql);
			if(mysqli_num_rows($resultado)){
				$row = mysqli_fetch_object($resultado);
				return ["password" => $row->password, "nombre_usuario" => $row->nombre_cuenta_usuario, "primer_ingreso" => $row->primer_ingreso];
			} else {
				return false;
			}

		}

		public static function registrarAlumno ($legajo, $nombre_usuario, $password, $ID_rol, $email) {
			$con = new Conexion();
			if($legajo) {
				$sql = "INSERT usuarios (nombre_cuenta_usuario, password, correoElectronico, legajo, ID_rol ) VALUES ('$nombre_usuario', '$password', '$email', $legajo, $ID_rol)";
			} else {
				$sql = "INSERT usuarios (nombre_cuenta_usuario, password, correoElectronico, ID_rol ) VALUES ('$nombre_usuario', '$password', '$email', $ID_rol)";
			}

			$resultado = $con->consultaRetorno($sql);
			if($resultado) {
				return true;
			} else {
				return false;
			}
		}

		public static function obtenerDatos($nombre_usuario) {
			$con = new Conexion();
			$sql = "SELECT u.legajo, u.ID_rol, u.nombre_cuenta_usuario, u.correoElectronico, a.nombre, a.apellido FROM usuarios u LEFT JOIN alumnos a ON a.legajo = u.legajo WHERE u.nombre_cuenta_usuario = '$nombre_usuario'";
			$resultado = $con->consultaRetorno($sql);
			if($row = mysqli_fetch_object($resultado)) {
				if($row->ID_rol == 1){
					return [
						"legajo" => $row->legajo,
						"apellido" => $row->apellido,
						"nombre" => $row->nombre,
						"tipo_usuario" => $row->ID_rol,
						"nombre_usuario" => $row->nombre_cuenta_usuario,
						"email" => $row->correoElectronico
					];
				} else {
					return [
						"nombre_usuario" => $row->nombre_cuenta_usuario,
						"tipo_usuario" => $row->ID_rol,
						"email" => $row->correoElectronico
					];
				}
			}
		}

		public function login($usuario, $password) {
			$con = new Conexion();
			$sql = "SELECT password FROM usuarios WHERE nombre_cuenta_usuario = '$usuario'";
			$resultado = $con->consultaRetorno($sql);
			if($resultado->num_rows != 0) {
				$row = mysqli_fetch_object($resultado);
				if (password_verify($password, $row->password)){
					return ["estado" => "iniciar"];
				} else {
					return ["estado" => "error", "mensaje" => "Datos incorrectos"];
				}
			} else {
				$sql = "SELECT 1 FROM alumnos WHERE numero_documento = '$usuario' and numero_documento = '$password'";
				return $sql;
				$resultado = $con->consultaRetorno($sql);
				if($resultado->num_rows != 0){
					return ["estado" => "registrar"];
				} else {
					return ["estado" => "error", "mensaje" => "Datos incorrectos"];
				}
			}
		}

		public static function importarDeAlumnos() {
			$con = new Conexion();
		 	$sql = "SELECT DISTINCT legajo, numero_documento FROM alumnos";
		 	$resultado = $con->consultaRetorno($sql);
		 	while( $row = mysqli_fetch_object($resultado)) {
		 		$password = password_hash($row->numero_documento, PASSWORD_DEFAULT);
		 		$sql = "INSERT into usuarios (nombre_cuenta_usuario, password, ID_rol, primer_ingreso, ID_usuarioAlumno, ID_usuarioPreceptor) values ('$row->numero_documento', '$password', 0, 0, $row->legajo, null)";
		 		$insert = $con->consultaRetorno($sql);
		 		if(!$insert){
		 			return $sql;
		 		}
		 	}
		 	return true;
		}
	}
