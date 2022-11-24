<?php

namespace Controllers;

use Model\AdminCita;
use Model\Cita;
use Model\Servicio;
use MVC\Router;

class AdminController {
    public static function index( Router $router ) {

        isAdmin();

        $fecha = $_GET["fecha"] ?? date("Y-m-d");
        $fechas = explode("-", $fecha);
        if(!checkdate ($fechas[1], $fechas[2], $fechas[0])) {
            header("location: /404");
        }

        // Consultar la base de datos
        $consulta = "SELECT citas.id, citas.hora, CONCAT(usuarios.nombre, ' ', usuarios.apellido) as cliente,  ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio ";
        $consulta .= " FROM citas ";
        $consulta .= " LEFT OUTER JOIN usuarios ON citas.usuarioID=usuarios.id ";
        $consulta .= " LEFT OUTER JOIN citasservicios ON citasservicios.citaID=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ON servicios.id=citasservicios.servicioID ";
        $consulta .= " WHERE fecha = '${fecha}' ";

        $citas = AdminCita::SQL($consulta);

        if ($citas === []){         
            $alertas = Cita::setAlerta("error", "No hay citas en esta fecha");

        }
            $alertas = Cita::getAlertas();

        $router->render("admin/index", [
            "nombre" => $_SESSION["nombre"],
            "citas" => $citas,
            "fecha" => $fecha,
            "alertas" => $alertas
        ]);
    }

}

    