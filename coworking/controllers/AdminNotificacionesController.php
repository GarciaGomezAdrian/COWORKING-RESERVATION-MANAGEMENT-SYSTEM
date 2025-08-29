<?php

require_once __DIR__ . '/../models/Notificacion.php';

class AdminNotificacionesController {
    public function index() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $tipo = $_GET['tipo'] ?? '';
        $usuario_nombre = trim($_GET['usuario_nombre'] ?? '');
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $por_pagina = 10;

        $modelo = new Notificacion($pdo);

        $total = $modelo->contarConFiltro($tipo, $usuario_nombre, $fecha_inicio, $fecha_fin);
        $notificaciones = $modelo->filtrar($tipo, $usuario_nombre, $fecha_inicio, $fecha_fin, $pagina, $por_pagina);

        $total_paginas = ceil($total / $por_pagina);

        require_once __DIR__ . '/../views/admin/notificaciones/index.php';
    }
}
