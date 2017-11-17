<?php
	require_once "Config/Autoload.php";
	use Modelos\Noticia;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');
	if(isset($_GET["id"]) && !empty($_GET["id"])){
		$id = $_GET["id"];
		$noticia = Noticia::mostrar($id);
		if($noticia){
			echo json_encode($noticia);
		} else {
			http_response_code(400);
			echo json_encode(["mensaje"=>"La noticia no fue encontrada"]);
		}

	} else {
		http_response_code(400);
		echo json_encode(["mensaje"=>"El id fue enviado incorrectamente"]);
	}

 ?>
