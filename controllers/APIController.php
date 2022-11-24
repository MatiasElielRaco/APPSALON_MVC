<?php

namespace Controllers;

use Model\Cita;
use Model\CitasServicios;
use Model\Servicio;

class APIController {
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }


    public static function guardar() {

        // Almacena la cita y devuelve el ID
        $cita = new Cita($_POST);
        $resultado = $cita->guardar();

        $id = $resultado["id"];
        
        

        // Almacena los servicios con el ID de la cita
        $idServicios = explode(",", $_POST["servicios"]);
        foreach($idServicios as $idServicio) {
            $args = [
                "citaID" => $id,
                "servicioID" => $idServicio,

            ];
            $citaServicio = new CitasServicios($args);
            $citaServicio->guardar();
        }

        echo json_encode(["resultado" => $resultado]);
    }

    public static function eliminar() {
        if($_SERVER["REQUEST_METHOD"] === "POST") {
            
            $id = $_POST["id"];
            $cita = Cita::find($id);
            $cita->eliminar();
            header("location:". $_SERVER["HTTP_REFERER"]);
        }
    }
}