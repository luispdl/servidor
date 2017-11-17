<?php
	require_once "Config/Autoload.php";
	use Modelos\Noticia;
	use Modelos\Auth;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	if(isset($_POST["id"]) && !empty($_POST["id"])){
		if(isset($_POST["contenido"]) && !empty($_POST["contenido"]) && isset($_POST["titulo"]) && !empty($_POST["titulo"])){
			if(isset($_FILES["imagen"]) && !empty($_FILES["imagen"])){
				$titulo = $_POST["titulo"];
				$contenido = $_POST["contenido"];
				$id = $_POST["id"];
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
					$resultado = Noticia::actualizar($id,$titulo,$contenido,$src);
					if($resultado){
						echo json_encode(["mensaje"=>"La noticia se actualizó con exito","noticia"=>$resultado]);
					} else {
						http_response_code(500);
						echo json_encode(["mensaje"=>"La noticia no se actualizo1","noticia"=>['id'=>$id,'titulo'=>$titulo]]);
					}

				}

			} else {
				$src = null;
				$titulo = $_POST["titulo"];
				$contenido = $_POST["contenido"];
				$id = $_POST["id"];
				$resultado = Noticia::actualizar($id,$titulo,$contenido,$src);
				if($resultado){
					echo json_encode(["mensaje"=>"La noticia se actualizó con exito",]);
				} else {
					http_response_code(500);
					echo json_encode(["mensaje"=>"La noticia no se actualizó2","noticia"=>['id'=>$id,'titulo'=>$titulo]]);
				}
			}
		}	else {
			http_response_code(400);
			echo json_encode(["mensaje" => "El titulo y/o contenido no puede estar vacío"]);
		}

	} else {
		http_response_code(400);
		echo json_encode(["mensaje"=>"El id de la noticia es inválido"]);

	}

?>
