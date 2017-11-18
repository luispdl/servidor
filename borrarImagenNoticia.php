<?php
	require_once "Config/Autoload.php";
	use Modelos\Noticia;
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
    echo json_encode(["mensaje" => $validar]);
    die();
  }

	$datos = Auth::obtenerDatos($token);
   if($datos->tipo_usuario == 1) {
     http_response_code(403);
     echo json_encode(["mensaje" => "No tiene autorización para esta operación"]);
     die();
   }

	if(isset($_POST["id"]) && !empty($_POST["id"])){
		$id = $_POST["id"];
		$borrada = Noticia::borrarImagen($id);
		if($borrada) {
			echo json_encode(["mensaje" => "Imagen borrada exitosamente","borrada" => $borrada]);
		} else {
			http_response_code(500);
			echo json_encode(["mensaje" => "Problemas en el servidor"]);
		}
	} else {
		http_response_code(400);
		echo json_encode(["mensaje" => "El id de la noticia es incorrecto"]);
	}
