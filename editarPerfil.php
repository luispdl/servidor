<?php 

	require_once "Config/Autoload.php";
	use Modelos\Usuario;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	if(isset($_GET["token"]) and !empty($_GET["token"])) {
    $token = $_GET["token"];
    try {
      $validar = Auth::verificar($token);
    } catch (Exception $e) {
      http_response_code(403);
      echo json_encode(["mensaje" => $e]);
      die();
    }
  } else {
    http_response_code(403);
    echo json_encode(["mensaje" => "Token no enviado"]);
    die();
  }
  if($validar["error"]) {
    http_response_code(403);
    echo json_encode(["mensaje" => $validar]);
    die();
  }

	if(isset($_POST["email"]) && !empty($_POST["email"]) && isset($_POST["nombre_usuario"]) && !empty($_POST["nombre_usuario"])) {
		$email = $_POST["email"];
		$nombre_usuario = $_POST["nombre_usuario"];	
		if(isset($_POST["password_actual"]) && !empty($_POST["password_actual"]) && isset($_POST["password_nuevo"]) && !empty($_POST["password_nuevo"])){
			$password_actual = $_POST["password_actual"];
			$password_nuevo = $_POST["password_nuevo"];
			$actualizar = Usuario::actualizar($email, $nombre_usuario, $password_actual, $password_nuevo);
			if($actualizar) {
				echo json_encode(["mensaje" => "Modificación exitosa!","sql" => $actualizar]);
			} else {
				http_response_code(500);
				echo json_encode(["mensaje" => "Error en la modificación"]);
			}
		} else {
			$actualizar = Usuario::actualizar($email, $nombre_usuario);
			if($actualizar) {
				echo json_encode(["mensaje" => "Modificación exitosa!" ,"sql" => $actualizar]);
			} else {
				http_response_code(500);
				echo json_encode(["mensaje" => "Error en la modificación"]);
			}
		}
	} else {
		http_response_code(400);
		echo json_encode(["mensaje" => 'Datos incorrectos']);
	}

 ?>