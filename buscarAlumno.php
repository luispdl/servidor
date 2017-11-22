<?php 
	require_once "Config/Autoload.php";
	use Modelos\Alumno;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json;charset=utf-8');

	// if(isset($_GET["token"]) and !empty($_GET["token"])) {
 //    $token = $_GET["token"];
 //    try {
 //      $validar = Auth::verificar($token);
 //    } catch (Exception $e) {
 //      http_response_code(403);
 //      echo json_encode(["mensaje" => $e]);
 //      die();
 //    }
 //  } else {
 //    http_response_code(403);
 //    echo json_encode(["mensaje" => "Token no enviado"]);
 //    die();
 //  }
 //  if($validar["error"]) {
 //    http_response_code(403);
 //    echo json_encode(["mensaje" => $validar["error"]]);
 //    die();
 //  }


	// $datos = Auth::obtenerDatos($token);
	// if($datos->tipo_usuario == 1) {
	// 	http_response_code(403);
	// 	echo json_encode(["mensaje" => "No tiene autorización para esta operación"]);
	// 	die();
	// }
	$pag = 1;
	if(isset($_GET["tipo"]) && !empty($_GET["tipo"])){
		$tipo = $_GET["tipo"];
		switch ($tipo) {
			case 'legajo':
				if (isset($_GET["legajo"])){
					$legajo = $_GET["legajo"];
					$alumno= Alumno::buscarPorLegajo($legajo);
					echo json_encode([$alumno]);
				} else {
					http_response_code(400);
					echo json_encode(["mensaje"=>"El legajo no fue enviado."]);
				}
				break;
			case 'dni':
				if(isset($_GET["dni"])) {
					$dni = $_GET['dni'];
					$alumno= Alumno::buscarPorDNI($dni);
					echo json_encode([$alumno]);
				} else {
					http_response_code(400);
					echo json_encode(["mensaje"=>"El DNI no fue enviado."]);
				}
				break;
			case 'nombre':
				if(isset($_GET["nombre"])){
					$nombre = $_GET['nombre'];
					if(isset($_GET["pagina"]) && !empty($_GET["pagina"])){
						$pag = $_GET["pagina"];
					}
					$alumnos= Alumno::buscarPorNombre($nombre, $pag);
					echo json_encode($alumnos);
				} else {
					http_response_code(400);
					echo json_encode(["mensaje"=>"El nombre no fue enviado"]);
				}
				break;
			case 'usuario':
				if(isset($_GET['nombre_usuario'])) {
					$nombre_usuario = $_GET["nombre_usuario"];
					$alumno = Alumno::buscarPorNombreUsuario($nombre_usuario);
					echo json_encode([$alumno]);
				}
				else {
					http_response_code(400);
					echo json_encode(["mensaje"=>"El nombre de usuario no fue enviado"]);
				}
				break;
			default:
				http_response_code(400);
				echo json_encode(["mensaje"=>"El tipo de busqueda enviado es inválido"]);
				break;
		}
	} else {
		http_response_code(400);
		echo json_encode(["mensaje"=>"El tipo de busqueda no fue enviado"]);
	}
?>