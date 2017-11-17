<?php

	/*
		Obteniendo el legajo de un Alumno pasado por GET, devuelve:
			-Un objeto con las carreras del Alumno y por cada carrera, las materias disponibles
			-Un objeto con ["mensaje"=>"No tiene materias para rendir."] en caso de que no tengas ninguna materia para rendir. Y un codigo de error 500
			-Un objeto con ["mensaje"=>"El alumno no existe"] en caso de que el legajo no se encuentre en la base de datos. Y un codigo de error 404.
			-Un objeto con ["mensaje" => 'Legajo no enviado'] en caso de que no llegue el parametro legajo o se encuentre vacío.
	*/
	require_once "Config/Autoload.php";
	use Modelos\Alumno;
	use Modelos\Periodo;
  use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	if(isset($_GET["token"]) and !empty($_GET["token"])) {
    $token = $_GET["token"];
    try {
      $validar = Auth::verificar($token);
    } catch (Exception $e) {
      http_response_code(400);
      echo json_encode(["mensaje" => $e]);
      die();
    }
  } else {
    http_response_code(400);
    echo "Token no enviado";
    die();
  }
    if($validar["error"]) {
      http_response_code(400);
      echo json_encode($validar);
      die();
    }
  	$fecha_actual = new DateTime('NOW');
  	$fecha_actual->setTimeZone(new DateTimeZone("America/Argentina/Buenos_Aires"));
  	$fechas_periodo = Periodo::fechas();
  	if(!$fechas_periodo) {
  		http_response_code(400);
  		echo json_encode(["mensaje" => "No hay fecha correspondientes al periodo de inscripción. Comuniquese con un administrativo."]);
  		die;
  	}
  	$fecha_inicio = new DateTime($fechas_periodo->fecha_inicio);
  	$fecha_inicio->setTimeZone(new DateTimeZone("America/Argentina/Buenos_Aires"));
  	$fecha_fin = new DateTime($fechas_periodo->fecha_fin);
  	$fecha_fin->setTimeZone(new DateTimeZone("America/Argentina/Buenos_Aires"));
  	if($fecha_actual<$fecha_inicio || $fecha_actual>$fecha_fin){
  		http_response_code(400);
  		echo json_encode(["mensaje"=>"No se encuentra dentro del periodo de inscripción"]);
  		die;
  	}

  if(isset($_GET["legajo"]) && !empty($_GET["legajo"])){
  	$legajo =  $_GET["legajo"];
  	$datos = Auth::obtenerDatos($token);
    if($datos->tipo_usuario == 1) {
      if ($datos->legajo != $legajo){
        http_response_code(400);
        echo json_encode(["error" => "Legajo no coincide con el usuario"]);
        die();
      }
    }
		//Pregunto si el alumno existe en el sistema.
		if(Alumno::alumnoExiste($legajo)){
			//Si existe creo el objeto alumno y devuelvo lo que retorna el metodo materiasDisponiblesParaRendir
			$alumno = new Alumno($legajo);
			$materias = $alumno->materiasDisponiblesParaRendir();
			if(count($materias)==0){
				http_response_code(500);
				echo json_encode(["mensaje"=>"No tiene materias para rendir."]);
			} else {
				echo json_encode($materias);
			}

		} else {
			//Si el alumno no existe mandamos un error 404, con una JSON con el mensaje.
			http_response_code(404);
			echo json_encode(["mensaje"=>"El alumno no existe"]);
		}

	} else {
		http_response_code(500);
		echo json_encode(["mensaje" => 'Legajo no enviado']);
	}
 ?>
