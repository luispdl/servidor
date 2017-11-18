<?php
	require_once "Config/Autoload.php";
	use Modelos\Usuario;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$nombre_usuario = $_POST["nombre_usuario"];
	$numero_documento = $_POST["numero_documento"];
	$email = $_POST["email"];
	$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
	if(isset($_POST["legajo"]) && !empty($_POST["legajo"])){
		$legajo = $_POST["legajo"];		
		$id_rol = 1;
	} else {
		$legajo = null;
		$id_rol = 2;
	}
	if ($registrado = Usuario::registrarAlumno($legajo, $nombre_usuario, $password, $id_rol, $email)) {
			echo json_encode(["mensaje" => "ALumno registrado con exito", "registrado" => $registrado]);
	} else {
		http_response_code(500);
		echo json_encode(["mensaje" => "Error al registrar el usuario"]);
	}


?>
