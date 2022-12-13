<?php  

    require_once $_SERVER['DOCUMENT_ROOT'] . '/apps/api_formulario/db.php';

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");    

    $no_videos = $_REQUEST["archivos"];
    $lugar = $_REQUEST["lugar"];
    $tipo_inmueble = $_REQUEST["tipo_inmueble"];
    $direccion = $_REQUEST["direccion"];

    // $videos = $_FILES["file8"];

    $subidos = 0;

    $db = new Db();
	$dbConn = $db->connect();

    // Por cada archivo
    for ($i=1; $i <= $no_videos ; $i++) { 
        
        $video = $_FILES["file".$i];

        $tempPath = $_FILES['file'.$i]['tmp_name'];

        $id_archivo = uniqid();

        $uploadPath = $_SERVER['DOCUMENT_ROOT'] . "/apps/api_formulario/videos/".$id_archivo;
        // $archivo = "documents/".$id_archivo;
        // $nombre_archivo = $_FILES['file']['name'];
        // $tipo_archivo = $_FILES['file']['type'];

        if (move_uploaded_file($tempPath, $uploadPath)) {
            
            // Se ha subido el archivo
            $subidos++;

            // Registrar en la tabla
            try {
                
                $query = "  INSERT INTO CAT_VIDEOS_4F (TIPO_INMUEBLE, DIRECCION, LUGAR) 
                            VALUES ('$tipo_inmueble', '$direccion', '$lugar')";

                $stid = oci_parse($dbConn, $query);
                oci_execute();

            } catch (\Throwable $th) {
                //throw $th;
            }

            // return array($archivo, $nombre_archivo, $tipo_archivo);

        }	

        // echo json_encode($video);

    }

    echo json_encode($video);

    if (intval($subidos) == intval($no_videos)) {
        
        // echo json_encode('subidos ' . $subidos);

    }

    // echo json_encode($video);

    // echo json_encode($videos);

    // foreach ($videos as $video) {
        
    //     // echo json_encode($video);

    // }

    

?>