<?php
	require_once "Config/Autoload.php";
	use Modelos\Noticia;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	echo json_encode(["noticias" => Noticia::todas()]);
 ?>
