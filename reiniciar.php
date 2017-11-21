<?php
	require_once "Config/Autoload.php";
	use Modelos\Usuario;
  use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

  if(isset($_POST["token"]) and !empty($_POST["token"])) {
    $token = $_POST["token"];
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
    echo json_encode(["mensaje" => $validar["error"]]);
    die();
  }
	if(isset($_POST["nombre_usuario"]) && !empty($_POST["nombre_usuario"])) {
    $nombre_usuario = $_POST["nombre_usuario"];
    if(Usuario::reiniciar($nombre_usuario)){
      echo json_encode(["mensaje" =>  "Usuario reiniciado exitosamente."]);
    } else {
      http_response_code(500);
      echo json_encode(["mensaje" => "Error en el servidor."]);
    }
  } else {
    http_response_code(400);
    echo json_encode(["mensaje" => "No se envío el nombre de usuario."]);
  }
  
 ?>