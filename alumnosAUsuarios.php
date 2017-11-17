<?php 
	require_once "Config/Autoload.php";
	use Modelos\Usuario;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');
	
	$importar = Usuario::importarDeAlumnos();
	if($importar) {
		echo json_encode(["Datos importados correctamente"=>$importar]);
	} else {
		echo json_encode(["Importación fallida"=>$importar]);
	}
 ?>