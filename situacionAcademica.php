<?php
	require_once "Config/Autoload.php";
	use Modelos\Alumno;
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

  if(isset($_GET["legajo"]) && !empty($_GET["legajo"])){
    $legajo = $_GET["legajo"];
    $datos = Auth::obtenerDatos($token);
    if($datos->tipo_usuario == 1) {
      if ($datos->legajo != $legajo){
        http_response_code(403);
        echo json_encode(["mensaje" => "Legajo no coincide con el usuario"]);
        die();
      }
    }

    if(Alumno::alumnoExiste($legajo)){
      $alumno = new ALumno($legajo);
      $materias = $alumno->situacionAcademica();
      if(count($materias)==0){
        http_response_code(500);
        echo json_encode(["mensaje"=>"No tiene materias regularizadas"]);
      } else {
        echo json_encode($materias);
      }

    } else {
      http_response_code(404);
      echo json_encode(["mensaje"=>"El alumno no existe"]);
    }

  }
?>
