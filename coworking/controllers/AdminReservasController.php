<?php

require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Notificacion.php';

require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AdminReservasController {
    public function index() {
        redireccionarSiNoEsAdmin();
        global $pdo;
    
        $cliente = $_GET['cliente'] ?? '';
        $fecha_inicio = $_GET['fecha_inicio'] ?? '';
        $fecha_fin = $_GET['fecha_fin'] ?? '';
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $por_pagina = 10;
    
        $reservaModel = new Reserva($pdo);
        $total = $reservaModel->contarFiltradas($cliente, $fecha_inicio, $fecha_fin);
        $reservas = $reservaModel->obtenerFiltradas($cliente, $fecha_inicio, $fecha_fin, $pagina, $por_pagina);
        $total_paginas = ceil($total / $por_pagina);
    
        require_once __DIR__ . '/../views/admin/reservas/index.php';
    }    

    public function editar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_GET['id'] ?? null;
        if (!$id) {
            $_SESSION['error'] = "ID de reserva no proporcionado.";
            redireccionar("?c=adminReservas&a=index");
        }

        $reserva = (new Reserva($pdo))->obtenerPorId($id);
        if (!$reserva) {
            $_SESSION['error'] = "Reserva no encontrada.";
            redireccionar("?c=adminReservas&a=index");
        }

        require_once __DIR__ . '/../views/admin/reservas/editar.php';
    }

    public function actualizar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_POST['id'] ?? null;
        $fecha_inicio = $_POST['fecha_inicio'] ?? '';
        $fecha_fin = $_POST['fecha_fin'] ?? '';
        $estado = $_POST['estado'] ?? '';

        if (!$id || !$fecha_inicio || !$fecha_fin || !$estado) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            redireccionar("?c=adminReservas&a=editar&id=$id");
        }

        (new Reserva($pdo))->actualizarAdmin($id, $fecha_inicio, $fecha_fin, $estado);
        $_SESSION['success'] = "Reserva actualizada correctamente.";
        redireccionar("?c=adminReservas&a=index");
    }

    public function cancelar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_GET['id'] ?? null;

        $reservaModel = new Reserva($pdo);
        $reserva = $reservaModel->obtenerPorId($id);

        if (!$reserva) {
            $_SESSION['error'] = "Reserva no encontrada.";
            redireccionar("?c=adminReservas&a=index");
        }

        $reservaModel->cancelar($id);

        $mensaje = "Ha sido cancelada tu reserva del espacio \"{$reserva['espacio']}\" del {$reserva['fecha_inicio']} al {$reserva['fecha_fin']} por un administrador.";
        $notificacion = new Notificacion($pdo);
        $notificacion->crear($reserva['usuario_id'], $mensaje, 'cancelación');

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'coworkingbookingsystem@gmail.com';
            $mail->Password   = 'cwnr glxs taee xqrj';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('coworkingbookingsystem@gmail.com', 'Coworking App');
            $mail->addAddress($reserva['email'], $reserva['nombre_completo']);
            $mail->Subject = 'Ha sido cancelada tu reserva';
            $mail->Body    = "Hola {$reserva['nombre_completo']},\n\n$mensaje\n\nGracias por usar Coworking App.";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar email de cancelación: " . $mail->ErrorInfo);
        }

        $_SESSION['success'] = "Reserva cancelada correctamente.";
        redireccionar("?c=adminReservas&a=index");
    }
}
