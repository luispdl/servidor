<?php 
	require_once "Config/Autoload.php";
	use Modelos\Alumno;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');
	if(isset($_GET["tipo"]) && !empty($_GET["tipo"])){
		$tipo = $_GET["tipo"];
		switch ($tipo) {
			case 'legajo':
				if ($_GET["legajo"]){
					$legajo = $_GET["legajo"];
					$alumno= Alumno::buscarPorLegajo($legajo);
					echo json_encode([$alumno]);
				} else {
					http_response_code(400);
					echo json_encode(["mensaje"=>"El legajo no fue enviado."]);
				}
				break;
			case 'dni':
				if($_GET["dni"]) {
					$dni = $_GET['dni'];
					$alumno= Alumno::buscarPorDNI($dni);
					echo json_encode([$alumno]);
				} else {
					http_response_code(400);
					echo json_encode(["mensaje"=>"El DNI no fue enviado."]);
				}
				break;
			case 'nombre':
				if($_GET["nombre"]){
					$nombre = $_GET['nombre'];
					$alumnos= Alumno::buscarPorNombre($nombre);
					echo json_encode($alumnos);
				} else {
					http_response_code(400);
					echo json_encode(["mensaje"=>"El nombre no fue enviado"]);
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