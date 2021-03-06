<?php
	require_once "Config/Autoload.php";
	use Modelos\Noticia;
	use Modelos\Auth;
	// import the Intervention Image Manager Class
	use Intervention\Image\ImageManagerStatic as Image;

	Config\Autoload::run();
	header("Access-Control-Allow-Origin: *");
	header('Content-Type: application/json');

	if(isset($_POST["token"]) and !empty($_POST["token"])) {
    $token = $_POST["token"];
    try {
      $validar = Auth::verificar($token);
    } catch (Exception $e) {
      http_response_code(403);
      echo json_encode(["mensaje" => $e]);
      die();
    }
  } else {
    http_response_code(403);
    echo json_encode(["mensaje" => "Token no enviado"]);
    die();
  }
  if($validar["error"]) {
    http_response_code(403);
    echo json_encode(["mensaje" => $validar["error"]]);
    die();
  }

  $datos = Auth::obtenerDatos($token);
	if($datos->tipo_usuario == 1) {
		http_response_code(403);
		echo json_encode(["mensaje" => "No tiene autorización para esta operación"]);
		die();
	}

	if (isset($_POST["titulo"]) && !empty($_POST["titulo"]) && isset($_POST["contenido"]) && !empty($_POST["contenido"])){
		$titulo = $_POST["titulo"];
		$contenido = $_POST["contenido"];
		if(isset($_FILES["imagen"])){
			$imagen = $_FILES["imagen"];
			$tmp_name = $imagen['tmp_name'];
			$nombre = $imagen["name"];
			$tipo = $imagen["type"];
			if ($tipo == '') {
				$dividirTipo = explode('.', $nombre);
				$tipo = end( $dividirTipo);
			}
			$carpeta = "./imagenes";
			if($tipo != 'image/jpg' && $tipo != 'image/jpeg' && $tipo != 'image/png' && $tipo != 'image/gif' &&
				$tipo != 'jpg' && $tipo != 'jpeg' && $tipo != 'png' && $tipo != 'gif'
			) {
				http_response_code(500);
				echo json_encode(["mensaje"=>"El archivo no es una imagen"]);
				die();
			} else {
				$tmp = explode(".",$nombre);
				$extension = end($tmp);
				$src = 'noticia_ISFT179_'.time().'.'.$extension;
				if (!move_uploaded_file($tmp_name, $carpeta .'/' .$src)) {
					http_response_code(500);
					echo json_encode(["mensaje"=>"La noticia no se guardó"]);
				}
				$image = Image::make($carpeta .'/' .$src);
				$width = $image->width();
				$porcentajeHeight = 200 * 100 / $width;
				$height = $porcentajeHeight * $image->height() / 100;
				if ($height > 200) {
					$height = 200;
				}
				$image->resize(200, $height)->save($carpeta .'/' .$src);
			}
		} else {
			$src = null;
		}
		$noticia = new Noticia($titulo, $contenido, $src);
		$resultado = $noticia->guardar($datos->id, $datos->nombre_usuario);
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
