<?php

	/*
		Con el legajo y un array de materias que contiene (codigo_carrera, codigo_materia, fecha_final, modalidad)
		Devuelvo:
			- Un objeto con ["mensaje"=>"Se anotó a los finales exitosamente"] en caso de inscripciones validas
			- Un objeto con ["mensaje"=>"No se inscribió a ningún final"] en caso de que no haya enviado el array materias o se encuentra vacío. Se considera que no desea inscribirse en ninguna materia.
			- Un objeto con ["mensaje"=>"No se puede inscribir a finales de la misma carrera el mismo día"] en caso de que las fechas no pasen la validación de fechas. Y un codigo de error 400.
			-Un objeto con ["mensaje" =>"Legajo no enviado"] en caso de que el legajo llegue vacío o no llegué. Y un codigo de error 500
			- Un objeto con ["mensaje"=>"No se realizó la inscripción. Error en el servidor."] en caso no se guarden las inscripciones por otro tipo de error. Y un codigo de error 500.

	*/

	require_once "Config/Autoload.php";
	use Modelos\Alumno;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	if(isset($_POST["token"]) and !empty($_POST["token"])) {
    $token = $_POST["token"];
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
    echo json_encode($validar["error"]);
    die();
  }

	if(isset($_POST["legajo"]) && !empty($_POST["legajo"])){
		$legajo = $_POST["legajo"];
		$datos = Auth::obtenerDatos($token);
    if($datos->tipo_usuario == 1) {
      if ($datos->legajo != $legajo){
        http_response_code(400);
        echo json_encode(["error" => "Legajo no coincide con el usuario"]);
        die();
      }
    }
		$alumno = new Alumno($_POST["legajo"]);
		if(isset($_POST["materias"]) and !empty($_POST["materias"])){
			$materias = $_POST["materias"];
			$valido = validarFechasFinales($materias);
			// Compruebo si las fechas son validas. No haya dos materias el mismo dia de la misma carrera
			if($valido){
				//Llamo al metodo inscripcionAFinales del alumno.
				// Devuelve true si se hizo la inscripcion correctamente. De lo contrario devuelve false.
				$inscripcion = $alumno->inscripcionAFinales($_POST["materias"]);
				if(!$inscripcion){
					//Si no se realizó la inscripción envió un codigo de estado 500. Con un JSON con el mensaje
					http_response_code(500);
					echo json_encode(["mensaje"=>"No se realizó la inscripción. Error en el servidor."]);
				} else {
					//Si se realizó la inscripcion devuelvo el JSON con el mensaje.
					echo json_encode(["mensaje"=>"Se anotó a los finales exitosamente","no se" => $inscripcion]);
				}


			} else {
				//Si las fechas son invalidas devuelvo un codigo de estado 400 y un JSON con el mensaje
				http_response_code(400);
				echo json_encode(["mensaje"=>"No se puede inscribir a finales de la misma carrera el mismo día"]);
			}

		} else {
			//
			$alumno->inscripcionAFinales();
			echo json_encode(["mensaje"=>"No se inscribió a ningún final"]);
		}
	} else {
		http_response_code(500);
		echo json_encode(["mensaje" =>"Legajo no enviado"]);
	}

	// Valida que no se elijan 2 materias con la misma fecha de la misma carrera.
	function validarFechasFinales($materias){
		$valido = true;
		foreach ($materias as $key => $materia) {
			for ($i=($key+1); $i < count($materias)  ; $i++) {
				if($materia["codigo_carrera"]==$materias[$i]["codigo_carrera"]
					&& $materia["fecha_final"]==$materias[$i]["fecha_final"]){
					$valido = false;
				}
			}
		}
		return $valido;
	}

 ?>
