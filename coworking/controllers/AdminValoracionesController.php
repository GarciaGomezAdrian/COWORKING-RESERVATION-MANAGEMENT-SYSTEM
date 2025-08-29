<?php

require_once __DIR__ . '/../models/Valoracion.php';

class AdminValoracionesController {
    public function index() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $usuario_nombre = trim($_GET['usuario_nombre'] ?? '');
        $espacio_nombre = trim($_GET['espacio_nombre'] ?? '');
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $por_pagina = 10;

        $modelo = new Valoracion($pdo);
        $total = $modelo->contarConFiltro($usuario_nombre, $espacio_nombre, $fecha_inicio, $fecha_fin);
        $valoraciones = $modelo->filtrar($usuario_nombre, $espacio_nombre, $fecha_inicio, $fecha_fin, $pagina, $por_pagina);
        $total_paginas = ceil($total / $por_pagina);

        require_once __DIR__ . '/../views/admin/valoraciones/index.php';
    }

    public function eliminar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_GET['id'] ?? null;
        if ($id) {
            $modelo = new Valoracion($pdo);
            $modelo->eliminar($id);
            $_SESSION['success'] = "Valoración eliminada correctamente.";
        }
        redireccionar("?c=adminValoraciones");
    }
}
