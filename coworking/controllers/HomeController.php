<?php
class HomeController {
    public function index() {
        global $pdo;
        require_once __DIR__ . '/../models/Espacio.php';
        $espacioModel = new Espacio($pdo);

        $espacios = [];
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';

        if ($fecha_inicio && $fecha_fin) {
            $espacios = $espacioModel->obtenerDisponibles($fecha_inicio, $fecha_fin);
        } else {
            $espacios = $espacioModel->obtenerTodos();
        }

        require_once __DIR__ . '/../views/home.php';
    }
}
