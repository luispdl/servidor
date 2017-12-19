<?php namespace Modelos;

	class Bitacora
	{
		private $con;

		public function __construct($fecha_inicio, $fecha_fin){
			$this->con = new Conexion();

		}

		public static function guardar($usuario, $descripcion){
			$con = new Conexion();
			$sql = "INSERT INTO bitacora (ID_usuario, fecha, Descripcion) VALUES ($usuario, now(), '$descripcion')";
			$resultado = $con->consultaRetorno($sql);
			if($resultado){
				return true;
			} else {
				return false;
			}
		}

		public static function mostrar(){
			$con = new Conexion();
		}
	}