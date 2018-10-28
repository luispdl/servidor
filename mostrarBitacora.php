<?php
	require_once "Config/Autoload.php";
	use Modelos\Bitacora;
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
  if (isset($_GET["q"]) && !empty($_GET["q"]) && isset($_GET['buscarPor']) && !empty($_GET['buscarPor'])) {
    switch ($_GET["buscarPor"]) {
      case 1:
        echo json_encode(Bitacora::mostrarPorNombreUsuario($_GET["q"]));
        break;
      case 2:
        echo json_encode(Bitacora::mostrarPorFecha($_GET["q"]));
        break;
      case 3:
        echo json_encode(Bitacora::mostrarPorLegajo($_GET["q"]));
        break;
      default:
        // code...
        break;
    }
  } else {
    if(isset($_GET["usuario_id"]) and !empty($_GET["usuario_id"])) {
  	 echo json_encode(Bitacora::mostrarPorUsuario($_GET["usuario_id"]));
    } else {
      echo json_encode(Bitacora::mostrarAdmin());
    }
  }
 ?>