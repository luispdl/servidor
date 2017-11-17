<?php
	require_once "Config/Autoload.php";
	use Modelos\Usuario;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	$usuario = $_POST["usuario"];
	$validacion = Usuario::obtenerPassword($usuario);
	if ($validacion) {
		$passwordAlumno = $validacion["password"];
		$primer_ingreso = $validacion["primer_ingreso"];
		$nombre_usuario = $validacion["nombre_usuario"];
		$token = null;
		if (password_verify($_POST["password"], $passwordAlumno)){
			if(!$primer_ingreso) {
				$datos_usuario = Usuario::obtenerDatos($nombre_usuario);
				$token = 	Auth::autenticar($datos_usuario);
			}
			echo json_encode(["datos" => $datos_usuario, "nombre_usuario" => $nombre_usuario, "primer_ingreso" => !$primer_ingreso, "token" => $token]);
		} else {
			http_response_code(400);
			echo json_encode(["mensaje" => "Datos incorrectos"]);
		}
	}	else {
			http_response_code(400);
			echo json_encode(["mensaje" => "Datos incorrectos"]);
	}

?>
