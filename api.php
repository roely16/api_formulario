<?php
    
    error_reporting(E_ERROR | E_PARSE);
    
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");

    class Api extends Rest{

        public $dbConn;

		public function __construct(){

			parent::__construct();

			$db = new Db();
			$this->dbConn = $db->connect();

        }

        public function registrar(){

            $persona = (object) $this->param['persona'];
            $usuario = (object) $this->param['usuario'];

            try {
                
                $query = "  INSERT INTO CAT_4F(ID_PERSONA, TIPO_INMUEBLE, TIPO_INSTITUCION, NOMBRE_INSTITUCION, PERSONAL_FIJO, PERSONAL_TEMPORAL, PERSONAS_DISCAPACIDAD, ZONA, COLONIA, DIRECCION, NIVELES)
                            VALUES ('$persona->id_persona', '$usuario->TIPO_INMUEBLE', '$usuario->TIPO_INSTITUCION', '$usuario->NOMBRE_INSTITUCION', '$usuario->PERSONAL_FIJO', '$usuario->PERSONAL_TEMPORAL', '$usuario->PERSONAS_DISCAPACIDAD', '$usuario->ZONA', '$usuario->COLONIA', '$usuario->DIRECCION', '$usuario->NIVELES')";    

                $stid = oci_parse($this->dbConn, $query);

                if (false === oci_execute($stid)) {
                   
                    // Error, la persona ya esta registrada
                    $err = oci_error($stid);

					$str_error = "La persona ya esta registrada.";

					$this->throwError($err["code"], $str_error);
                }

                $this->returnResponse(SUCCESS_RESPONSE, $persona);

            } catch (\Throwable $th) {
                
                $this->throwError(JWT_PROCESSING_ERROR, $e->getMessage());

            }

        }

        public function  obtener_inmuebles(){

            $query = "  SELECT *
                        FROM CAT_TIPO_INMUEBLE";

            $stid = oci_parse($this->dbConn, $query);
            oci_execute($stid);

            $tipos = [];

            while ($data = oci_fetch_array($stid, OCI_ASSOC)) {

                $data["text"] = $data["NOMBRE"];
                $data["value"] = $data["ID"];

                $tipos [] = $data;

            }

            $this->returnResponse(SUCCESS_RESPONSE, $tipos);

        }

        public function subir_videos(){

            

        }

    }

?>