<?php

require_once __DIR__ . '/../models/Espacio.php';

class EspacioController {
    public function ver() {
        global $pdo;
        $id = $_GET['id'] ?? null;
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';

        if (!$id) {
            redireccionar("?c=home");
        }

        $modelo = new Espacio($pdo);
        $espacio = $modelo->obtenerPorId($id);
        $diasOcupados = $modelo->obtenerDiasReservados($id);

        // Obtener valoraciones
        $valoraciones = $modelo->obtenerValoraciones($id);

        require_once __DIR__ . '/../views/espacio.php';
    }
}
