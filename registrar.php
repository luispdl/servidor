<?php
	require_once "Config/Autoload.php";
	use Modelos\Usuario;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$legajo = $_POST["legajo"];
	$nombre_usuario = $_POST["nombre_usuario"];
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	if ($registrado = Usuario::registrarAlumno($legajo, $nombre_usuario, $password)) {
		echo json_encode(["mensaje" => "ALumno registrado con exitó"]);
	} else {
		http_response_code(500);
		echo json_encode(["mensaje" => "Error al registrar el alumno"]);
	}
?>
