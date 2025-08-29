<?php

require_once __DIR__ . '/../models/Usuario.php';

class AdminController {
    public function index() {
        redireccionarSiNoEsAdmin();
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
            $_SESSION['error'] = "Acceso denegado.";
            redireccionar("?c=auth&a=login");
            return;
        }

        require_once __DIR__ . '/../views/admin/index.php';
    }

    public function perfil() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $usuarioModel = new Usuario($pdo);
        $usuario = $usuarioModel->obtenerPorId($_SESSION['usuario']['id']);

        require_once __DIR__ . '/../views/admin/perfil.php';
    }

    public function actualizarContrasena() {
        redireccionarSiNoEsAdmin();
        global $pdo;
        
        $usuario_id = $_SESSION['usuario']['id'];
        $actual = $_POST['contrasena_actual'] ?? '';
        $nueva = $_POST['nueva_contrasena'] ?? '';
        $confirmar = $_POST['confirmar_contrasena'] ?? '';
    
        $usuarioModel = new Usuario($pdo);
        $usuario = $usuarioModel->obtenerPorId($usuario_id);
    
        if (!password_verify($actual, $usuario['contraseña'])) {
            $_SESSION['error'] = "La contraseña actual es incorrecta.";
            redireccionar("?c=admin&a=perfil");
        }
    
        if ($nueva !== $confirmar) {
            $_SESSION['error'] = "Las nuevas contraseñas no coinciden.";
            redireccionar("?c=admin&a=perfil");
        }
    
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,20}$/', $nueva)) {
            $_SESSION['error'] = "La nueva contraseña debe tener entre 8 y 20 caracteres e incluir mayúsculas, minúsculas, números y símbolos.";
            redireccionar("?c=admin&a=perfil");
        }
    
        $nueva_hash = password_hash($nueva, PASSWORD_DEFAULT);
        $usuarioModel->actualizarContrasena($usuario_id, $nueva_hash);

        $_SESSION['success'] = "Contraseña actualizada correctamente.";
        redireccionar("?c=admin&a=perfil");
    }
}
