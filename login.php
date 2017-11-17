<?php
	require_once "Config/Autoload.php";
	use Modelos\Usuario;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$usuario = $_POST["usuario"];
	$password = $_POST["password"];
	$logueo = Usuario::login($usuario, $password);
	if($logueo){
		if($logueo["estado"] == "error"){
			http_response_code(400);
			echo json_encode(["mensaje" => $logueo["mensaje"]]);
		} else {
			if($logueo["estado"] == "iniciar"){
				$datos = Usuario::obtenerDatos($usuario);
				$token = Auth::autenticar($datos);
				echo json_encode(["estado" => $logueo["estado"], "datos" => $datos, "token" => $token]);
			} else {
				echo json_encode($logueo);
			}
		}
	} else {
		http_response_code(500);
		echo json_encode(["mensaje" => "Error en el servidor"]);
	}

?>
