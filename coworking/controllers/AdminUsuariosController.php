<?php

require_once __DIR__ . '/../models/Usuario.php';

class AdminUsuariosController {
    public function index() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $busqueda = $_GET['busqueda'] ?? '';
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $por_pagina = 10;

        $usuarioModel = new Usuario($pdo);
        $total = $usuarioModel->contarConFiltro($busqueda);
        $usuarios = $usuarioModel->buscarConFiltro($busqueda, $pagina, $por_pagina);

        $total_paginas = ceil($total / $por_pagina);

        require_once __DIR__ . '/../views/admin/usuarios/index.php';
    }

    public function editar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$id) {
            $_SESSION['error'] = "ID de usuario no válido.";
            redireccionar("?c=adminusuarios");
            return;
        }

        $usuarioModel = new Usuario($pdo);
        $usuario = $usuarioModel->obtenerPorId($id);

        if (!$usuario) {
            $_SESSION['error'] = "Usuario no encontrado.";
            redireccionar("?c=adminusuarios");
            return;
        }

        require_once __DIR__ . '/../views/admin/usuarios/editar.php';
    }

    public function actualizar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id     = intval($_POST['id'] ?? 0);
        $nombre = htmlspecialchars(trim($_POST['nombre'] ?? ''));
        $email  = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
        $tipo   = $_POST['tipo_usuario'] ?? '';

        if (!$id || !$nombre || !$email || !in_array($tipo, ['cliente', 'admin'])) {
            $_SESSION['error'] = "Datos inválidos.";
            redireccionar("?c=adminusuarios&a=editar&id=$id");
            return;
        }

        $usuarioModel = new Usuario($pdo);
        $usuarioModel->actualizar($id, $nombre, $email, $tipo);

        $_SESSION['success'] = "Usuario actualizado correctamente.";
        redireccionar("?c=adminusuarios");
    }

    public function eliminar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = isset($_GET['id']) ? intval($_GET['id']) : null;
        if (!$id) {
            $_SESSION['error'] = "ID de usuario no válido.";
            redireccionar("?c=adminusuarios");
            return;
        }

        $usuarioModel = new Usuario($pdo);
        $usuarioModel->eliminar($id);

        $_SESSION['success'] = "Usuario eliminado correctamente.";
        redireccionar("?c=adminusuarios");
    }
}
