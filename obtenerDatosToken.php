<?php
	require_once "Config/Autoload.php";
	use Modelos\Usuario;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$token = $_GET["token"];
	$datos = Auth::obtenerDatos($token);
	echo json_encode($datos);
?>