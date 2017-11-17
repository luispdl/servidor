<?php 
	require_once "Config/Autoload.php";
	use Modelos\Noticia;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

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