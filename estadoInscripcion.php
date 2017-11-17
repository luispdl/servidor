<?php
	/*
		Con el legajo pasado por GET por parametro. Devuelve:
			-Un objeto con todas las carreras (codigo_carrera, nombre_carrera) y en cada carrera todas la materias que el alumno tiene inscriptas para rendir final con codigo_materia, codigo_carrera, fecha_final, modalidad.
			-Un objeto con ["mensaje"=>"No tiene materias inscriptas."] en caso que tenga 0 materias inscriptas. Y un codigo de error 500
			-Un objeto con ["mensaje" => "El alumno no existe"] en caso de que el legajo no se encuentre en la base de datos. Y un codigo de error 404.
			-Un objeto con ["mensaje" => 'El legajo no fue enviado'] en caso de que el legajo no haya sido enviado o este vacio.
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
      http_response_code(403);
      echo json_encode(["mensaje" => $e]);
      die();
    }
  } else {
    http_response_code(403);
    echo "Token no enviado";
    die();
  }
  if($validar["error"]) {
    http_response_code(403);
    echo json_encode($validar);
    die();
  }

	if(isset($_GET["legajo"]) && !empty($_GET["legajo"])){
		$legajo = $_GET["legajo"];
		$datos = Auth::obtenerDatos($token);
    if($datos->tipo_usuario == 1) {
      if ($datos->legajo != $legajo){
        http_response_code(403);
        echo json_encode(["error" => "Legajo no coincide con el usuario"]);
        die();
      }
    }
		if (Alumno::alumnoExiste($legajo)){
			$alumno = new ALumno($_GET["legajo"]);
			$materias = $alumno->estadoInscripcion();
			if(count($materias)==0){
				http_response_code(500);
				echo json_encode(["mensaje"=>"No tiene materias inscriptas."]);
			} else {
				echo json_encode($materias);
			}
		} else {
			http_response_code(404);
			echo json_encode(["mensaje" => "El alumno no existe"]);
		}
	} else {
		http_response_code(500);
		echo json_encode(["mensaje" => 'El legajo no fue enviado']);
	}
?>
