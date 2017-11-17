<?php
	require_once "Config/Autoload.php";
	use Modelos\Noticia;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	if (isset($_POST["titulo"]) && !empty($_POST["titulo"]) && isset($_POST["contenido"]) && !empty($_POST["contenido"])){
		$titulo = $_POST["titulo"];
		$contenido = $_POST["contenido"];
		if(isset($_FILES["imagen"])){
			$imagen = $_FILES["imagen"];
			$tmp_name = $imagen['tmp_name'];
			$nombre = $imagen["name"];
			$tipo = $imagen["type"];
			$carpeta = "./imagenes";
			if($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif') {
				http_response_code(500);
				echo json_encode(["mensaje"=>"El archivo no es una imagen"]);
			} else {
				$tmp = explode(".",$nombre);
				$extension = end($tmp);
				$src = 'noticia_ISFT179_'.time().'.'.$extension;
				move_uploaded_file($tmp_name, $carpeta .'/' .$src);
			}
		} else {
			$src = null;
		}
		$noticia = new Noticia($titulo, $contenido, $src);
		$resultado = $noticia->guardar();
		if($resultado){
			echo json_encode(["mensaje"=>"La noticia se guardo con exito"]);
		} else {
			http_response_code(500);
			echo json_encode(["mensaje"=>"La noticia no se guardó"]);
		}
	} else {
		http_response_code(400);
		echo json_encode(["mensaje" => "El titulo y/o contenido no puede estar vacío"]);
}


?>
