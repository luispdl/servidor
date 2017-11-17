<?php namespace Modelos;

use Modelos\Conexion;

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
			$con = new Conexion();
			$sql = "SELECT password, nombre_cuenta_usuario, primer_ingreso FROM usuarios WHERE nombre_cuenta_usuario = '$usuario'";
			$resultado = $con->consultaRetorno($sql);
			if(mysqli_num_rows($resultado)){
				$row = mysqli_fetch_object($resultado);
				return ["password" => $row->password, "nombre_usuario" => $row->nombre_cuenta_usuario, "primer_ingreso" => $row->primer_ingreso];
			} else {
				return false;
			}

		}

		public static function registrarAlumno ($legajo, $nombre_usuario, $password) {
			$con = new Conexion();
			$sql = "UPDATE usuarios SET nombre_cuenta_usuario = '$nombre_usuario', password = '$password', primer_ingreso = 0 WHERE ID_usuarioAlumno = $legajo";
			$resultado = $con->consultaRetorno($sql);
			if($resultado) {
				return true;
			} else {
				return false;
			}
		}

		public static function obtenerDatos($nombre_usuario) {
			$con = new Conexion();
			$sql = "SELECT u.ID_usuarioAlumno, u.ID_usuarioPreceptor, u.nombre_cuenta_usuario, u.ID_rol, a.legajo, a.nombre as alumno_nombre, a.apellido as alumno_apellido, p.ID_preceptor, p.nombre AS preceptor_nombre, p.apellido AS preceptor_apellido from usuarios u
			LEFT JOIN alumnos a ON a.legajo = u.ID_usuarioAlumno
			LEFT JOIN preceptores p ON p.ID_preceptor = u.ID_usuarioPreceptor
			WHERE u.nombre_cuenta_usuario = '$nombre_usuario'";
			$resultado = $con->consultaRetorno($sql);
			if($row = mysqli_fetch_object($resultado)) {
				if($row->ID_rol == 1){
					return [
						"legajo" => $row->legajo,
						"apellido" => $row->alumno_apellido,
						"nombre" => $row->alumno_nombre,
						"tipo_usuario" => $row->ID_rol
					];
				} else {
					return [
						"id_preceptor" => $row->ID_usuarioPreceptor,
						"apellido" => $row->preceptor_apellido,
						"nombre" => $row->preceptor_nombre,
						"tipo_usuario" => $row->ID_rol
					];
				}
			}
		}

		public function login() {

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
