<?php 
	require_once "Config/Autoload.php";
	use Modelos\Noticia;

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


	$datos = Auth::obtenerDatos($token);
	if($datos->tipo_usuario == 1) {
		http_response_code(403);
		echo json_encode(["mensaje" => "No tiene autorización para esta operación"]);
		die();
	}

	if(isset($_GET["id"]) && !empty($_GET["id"])){
		$id = $_GET["id"];
		$resultado = Noticia::eliminar($id);
		if($resultado) {
			echo json_encode(["mensaje"=>"La noticia se eliminó correctamente"]);
		} else {
			http_response_code(500);
			echo json_encode(["mensaje"=>'Problema en el servidor']);
		}
	} else {
		http_response_code(400);
		echo json_encode(["mensaje"=>'El id no fue enviado correctamente']);
	}


 ?>