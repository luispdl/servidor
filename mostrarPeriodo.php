<?php
	require_once "Config/Autoload.php";
	use Modelos\Periodo;
  use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	echo json_encode(["fechas"=>Periodo::fechas()]);
?>
