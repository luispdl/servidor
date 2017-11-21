<?php
	require_once "Config/Autoload.php";
	use Modelos\Periodo;
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

	$datos = Auth::obtenerDatos($token);
	if($datos->tipo_usuario != 3) {
		http_response_code(403);
		echo json_encode(["mensaje" => "No tiene autorización para esta operación"]);
		die();
	}

	if (isset($_POST["fecha_inicio"]) && isset($_POST["fecha_fin"]) && !empty($_POST["fecha_inicio"]) && !empty($_POST["fecha_inicio"])) {
		$fecha_inicio = $_POST["fecha_inicio"];
		$fecha_fin = $_POST["fecha_fin"];
		$periodo = new Periodo($fecha_inicio,$fecha_fin);
		if($periodo->guardarFecha()){
			echo json_encode(["mensaje"=> "Período de inscripcion guardado correctamente"]);
		} else {
			http_response_code(500);
			echo json_encode(["mensaje"=> "Problemas en el servidor"]);
		}
	} else {
		http_response_code(400);
		echo json_encode(["mensaje" => "Fechas enviadas incorrectamente"]);
	}

?>
