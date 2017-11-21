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
    echo json_encode(["mensaje" => $validar["error"]]);
    die();
  }
	echo json_encode(["preceptores" => Usuario::preceptores()]);
 ?>
