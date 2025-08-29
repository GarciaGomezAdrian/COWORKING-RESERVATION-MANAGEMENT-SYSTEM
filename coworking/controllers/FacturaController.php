<?php

require_once __DIR__ . '/../libs/fpdf/fpdf.php';
require_once __DIR__ . '/../models/Reserva.php';

require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/Exception.php';

require_once __DIR__ . '/../includes/pdf_factura_template.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class FacturaController {
    public function generar() {
        redireccionarSiNoEsCliente();
        global $pdo;

        $id = $_GET['id'] ?? null;
        if (!$id) redireccionar("?c=usuario&a=reservas");

        $reservaModel = new Reserva($pdo);
        $reserva = $reservaModel->obtenerDetalle($id, $_SESSION['usuario']['id']);

        if (!$reserva) redireccionar("?c=usuario&a=reservas");

        generarFacturaPDF($reserva);
    }

    public function enviarFactura() {
        redireccionarSiNoEsCliente();
        global $pdo;
    
        $id = $_GET['id'] ?? null;
        if (!$id) redireccionar("?c=usuario&a=reservas");
    
        $reservaModel = new Reserva($pdo);
        $reserva = $reservaModel->obtenerDetalle($id, $_SESSION['usuario']['id']);
        
        if (!$reserva) redireccionar("?c=usuario&a=reservas");
        
        $filePath = __DIR__ . '/../facturas/Factura_' . $id . '.pdf';
        if (!is_dir(dirname($filePath))) mkdir(dirname($filePath), 0777, true);
        generarFacturaPDF($reserva, true, $filePath); 
    
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
            $mail->addAddress($_SESSION['usuario']['email'], $_SESSION['usuario']['nombre_completo']);
            $mail->Subject = 'Factura de tu reserva';
            $mail->Body    = 'Adjunto encontrarás el PDF con los detalles de tu reserva. ¡Gracias por confiar en nosotros!';
            $mail->addAttachment($filePath);
    
            $mail->send();
            $_SESSION['success'] = "Factura enviada correctamente a tu correo.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    
        redireccionar("?c=usuario&a=detalleReserva&id=$id");
    }
    
}
