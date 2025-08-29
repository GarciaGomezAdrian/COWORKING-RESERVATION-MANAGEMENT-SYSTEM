<?php

require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {

    public function reservas() {
        redireccionarSiNoEsCliente();

        global $pdo;

        $usuario_id = $_SESSION['usuario']['id'];
        $reservas = (new Reserva($pdo))->obtenerPorUsuario($usuario_id);

        require_once __DIR__ . '/../views/mis_reservas.php';
    }

    public function detalleReserva() {
        redireccionarSiNoEsCliente();

        global $pdo;
        $id = $_GET['id'] ?? null;
        if (!$id) redireccionar("?c=usuario&a=reservas");

        $reservaModel = new Reserva($pdo);
        $reserva = $reservaModel->obtenerDetalle($id, $_SESSION['usuario']['id']);

        if (!$reserva) redireccionar("?c=usuario&a=reservas");

        $puede_valorar = strtotime($reserva['fecha_fin']) < strtotime(date('d-m-Y')) &&
                         !$reservaModel->tieneValoracion($id);

        require_once __DIR__ . '/../views/detalle_reserva.php';
    }

    public function valorar() {
        redireccionarSiNoEsCliente();
        global $pdo;
    
        $reserva_id = $_POST['reserva_id'] ?? null;
        $mensaje = trim($_POST['mensaje'] ?? '');
    
        if (!$reserva_id || empty($mensaje)) {
            $_SESSION['error'] = "Debes escribir un mensaje para valorar.";
            redireccionar("?c=usuario&a=reservas");
            return;
        }
    
        if (strlen($mensaje) < 10) {
            $_SESSION['error'] = "La valoración debe tener al menos 10 caracteres.";
            redireccionar("?c=usuario&a=detalleReserva&id=" . urlencode($reserva_id));
            return;
        }
    
        $mensaje = htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8');
    
        $reservaModel = new Reserva($pdo);
        $reserva = $reservaModel->obtenerDetalle($reserva_id, $_SESSION['usuario']['id']);
    
        if ($reserva && strtotime($reserva['fecha_fin']) < strtotime(date('d-m-Y'))) {
            $stmt = $pdo->prepare("INSERT INTO valoraciones (usuario_id, espacio_id, mensaje, fecha_valoracion) VALUES (?, ?, ?, CURDATE())");
            $stmt->execute([$_SESSION['usuario']['id'], $reserva['espacio_id'], $mensaje]);
            $_SESSION['success'] = "Valoración registrada correctamente.";
        } else {
            $_SESSION['error'] = "No puedes valorar esta reserva.";
        }
    
        redireccionar("?c=usuario&a=detalleReserva&id=" . urlencode($reserva_id));
    }      

    public function perfil() {
        redireccionarSiNoEsCliente();

        global $pdo;
        $usuarioModel = new Usuario($pdo);
        $usuario = $usuarioModel->obtenerPorId($_SESSION['usuario']['id']);

        require_once __DIR__ . '/../views/usuario/perfil.php';
    }

    public function actualizarContrasena() {
        redireccionarSiNoEsCliente();
        global $pdo;
    
        $usuario_id = $_SESSION['usuario']['id'];
        $actual = $_POST['contrasena_actual'] ?? '';
        $nueva = $_POST['nueva_contrasena'] ?? '';
        $confirmar = $_POST['confirmar_contrasena'] ?? '';
    
        $usuarioModel = new Usuario($pdo);
        $usuario = $usuarioModel->obtenerPorId($usuario_id);
    
        if (!password_verify($actual, $usuario['contraseña'])) {
            $_SESSION['error'] = "La contraseña actual es incorrecta.";
            redireccionar("?c=usuario&a=perfil");
        }
    
        if ($nueva !== $confirmar) {
            $_SESSION['error'] = "Las nuevas contraseñas no coinciden.";
            redireccionar("?c=usuario&a=perfil");
        }
    
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,20}$/', $nueva)) {
            $_SESSION['error'] = "La nueva contraseña debe tener entre 8 y 20 caracteres e incluir mayúsculas, minúsculas, números y símbolos.";
            redireccionar("?c=usuario&a=perfil");
        }
    
        $nueva_hash = password_hash($nueva, PASSWORD_DEFAULT);
        $usuarioModel->actualizarContrasena($usuario_id, $nueva_hash);
    
        $_SESSION['success'] = "Contraseña actualizada correctamente.";
        redireccionar("?c=usuario&a=perfil");
    }
          
}
