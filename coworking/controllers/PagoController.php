<?php

require_once __DIR__ . '/../models/Espacio.php';
require_once __DIR__ . '/../models/Pago.php';
require_once __DIR__ . '/../models/Cupon.php';
require_once __DIR__ . '/../models/Reserva.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../includes/pdf_factura_template.php';
require_once __DIR__ . '/FacturaController.php';

class PagoController {
    public function iniciar() {
        redireccionarSiNoEsCliente();
        global $pdo;

        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'cliente') {
            $_SESSION['error'] = "Debes iniciar sesión como cliente.";
            redireccionar("?c=auth&a=login");
        }

        $espacio_id = $_GET['id'] ?? null;
        $fecha_inicio = $_GET['fecha_inicio'] ?? null;
        $fecha_fin = $_GET['fecha_fin'] ?? null;

        if (!$espacio_id || !$fecha_inicio || !$fecha_fin) {
            redireccionar("?c=home");
        }

        $espacio = (new Espacio($pdo))->obtenerPorId($espacio_id);
        $dias = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / 86400 + 1;
        $total = $espacio['precio_dia'] * $dias;

        require_once __DIR__ . '/../views/pago.php';
    }

    public function procesar() {
        global $pdo;
    
        $espacio_id    = intval($_POST['espacio_id'] ?? 0);
        $fecha_inicio  = trim($_POST['fecha_inicio'] ?? '');
        $fecha_fin     = trim($_POST['fecha_fin'] ?? '');
        $tarjeta       = trim($_POST['tarjeta'] ?? '');
        $codigo_cupon  = trim($_POST['cupon'] ?? '');
        $usuario_id    = $_SESSION['usuario']['id'];
    
        if (!$espacio_id || empty($fecha_inicio) || empty($fecha_fin) || empty($tarjeta)) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            redireccionar("?c=home");
            return;
        }
    
        if (!strtotime($fecha_inicio) || !strtotime($fecha_fin) || strtotime($fecha_inicio) > strtotime($fecha_fin)) {
            $_SESSION['error'] = "Las fechas seleccionadas no son válidas.";
            redireccionar("?c=pago&a=iniciar&id=$espacio_id&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin");
            return;
        }
    
        if (!preg_match('/^\d{12,19}$/', $tarjeta)) {
            $_SESSION['error'] = "Número de tarjeta inválido (debe contener entre 12 y 19 dígitos).";
            redireccionar("?c=pago&a=iniciar&id=$espacio_id&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin");
            return;
        }
    
        $espacio = (new Espacio($pdo))->obtenerPorId($espacio_id);
        if (!$espacio) {
            $_SESSION['error'] = "Espacio no encontrado.";
            redireccionar("?c=home");
            return;
        }
    
        $dias = (strtotime($fecha_fin) - strtotime($fecha_inicio)) / 86400 + 1;
        $total = $espacio['precio_dia'] * $dias;
    
        $descuento = 0;
        if ($codigo_cupon !== '') {
            $codigo_cupon = htmlspecialchars($codigo_cupon, ENT_QUOTES, 'UTF-8');
            $cuponModel = new Cupon($pdo);
            $cupon = $cuponModel->validarCupon($codigo_cupon, $usuario_id);
            if ($cupon) {
                $descuento = $total * ($cupon['descuento'] / 100);
            } else {
                $_SESSION['error'] = "Cupón inválido o caducado.";
                redireccionar("?c=pago&a=iniciar&id=$espacio_id&fecha_inicio=$fecha_inicio&fecha_fin=$fecha_fin");
                return;
            }
        }
    
        $totalFinal = $total - $descuento;
    
        $reservaModel = new Reserva($pdo);
        $reserva_id = $reservaModel->crear($usuario_id, $espacio_id, $fecha_inicio, $fecha_fin);
    
        $pagoModel = new Pago($pdo);
        $pagoModel->crear($reserva_id, $usuario_id, $totalFinal);
    
        $noti = new Notificacion($pdo);
        $noti->crear($usuario_id, "Has reservado el espacio '{$espacio['nombre']}' del $fecha_inicio al $fecha_fin por un total de $totalFinal €", 'confirmacion');
    
        $_GET['id'] = $reserva_id;
        $factura = new FacturaController();
        $factura->enviarFactura();
    
        $_SESSION['success'] = "Pago realizado correctamente. Se ha enviado la factura a tu correo.";
        redireccionar("?c=usuario&a=reservas");
    }
    
    public function validarCupon() {
        global $pdo;
        header('Content-Type: application/json');
    
        if (!isset($_POST['cupon']) || !isset($_SESSION['usuario']['id'])) {
            echo json_encode(['valido' => false]);
            exit;
        }
    
        $codigo = trim($_POST['cupon']);
        $usuario_id = $_SESSION['usuario']['id'];
    
        $cuponModel = new Cupon($pdo);
        $cupon = $cuponModel->validarCupon($codigo, $usuario_id);
    
        if ($cupon) {
            echo json_encode([
                'valido' => true,
                'descuento' => (float)$cupon['descuento']
            ]);
        } else {
            echo json_encode(['valido' => false]);
        }
    }    
}
