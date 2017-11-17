<?php
	require_once "Config/Autoload.php";
	use Modelos\Usuario;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$legajo = $_POST["legajo"];
	$nombre_usuario = $_POST["nombre_usuario"];
	$numero_documento = $_POST["numero_documento"];
	$email = $_POST["email"];
	$id_rol = $_POST["id_rol"];
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	if ($registrado = Usuario::registrarAlumno($legajo, $nombre_usuario, $password, $id_rol, $email)) {
		echo json_encode(["mensaje" => "ALumno registrado con exito", "registrado" => $registrado]);
	} else {
		http_response_code(500);
		echo json_encode(["mensaje" => "Error al registrar el alumno"]);
	}
?>
