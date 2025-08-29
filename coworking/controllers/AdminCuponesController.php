<?php

require_once __DIR__ . '/../models/Cupon.php';
require_once __DIR__ . '/../models/Notificacion.php';
require_once __DIR__ . '/../models/Usuario.php';

require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/../libs/PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AdminCuponesController {
    public function index() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $busqueda = $_GET['busqueda'] ?? '';
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $por_pagina = 10;

        $modelo = new Cupon($pdo);
        $total = $modelo->contarConFiltro($busqueda);
        $cupones = $modelo->buscarConFiltro($busqueda, $pagina, $por_pagina);

        $total_paginas = ceil($total / $por_pagina);

        require_once __DIR__ . '/../views/admin/cupones/index.php';
    }

    public function crear() {
        redireccionarSiNoEsAdmin();
        global $pdo;
        $usuarios = (new Usuario($pdo))->obtenerTodos();
        require_once __DIR__ . '/../views/admin/cupones/crear.php';
    }

    public function guardar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $codigo = $_POST['codigo'];
        $descuento = $_POST['descuento'];
        $fecha = $_POST['fecha_expiracion'];
        $usuario_id = $_POST['usuario_id'] !== '' ? $_POST['usuario_id'] : null;

        try {
            (new Cupon($pdo))->crear($codigo, $descuento, $fecha, $usuario_id);
        } catch (PDOException $e) {
            $_SESSION['error'] = "Ya existe un cupón con ese código.";
            redireccionar("?c=adminCupones&a=crear");
        }

        $notificacion = new Notificacion($pdo);
        $mensaje = "Tienes un cupón de descuento: $codigo - $descuento% de descuento (válido hasta $fecha).";

        if ($usuario_id) {
            $usuario = (new Usuario($pdo))->obtenerPorId($usuario_id);
            $notificacion->crear($usuario_id, $mensaje, 'cupón');
            $this->enviarEmail($usuario['email'], $usuario['nombre_completo'], $codigo, $descuento, $fecha);
        } else {
            $usuarios = (new Usuario($pdo))->obtenerSoloClientes();
            foreach ($usuarios as $u) {
                $notificacion->crear($u['id'], $mensaje, 'cupon');
                $this->enviarEmail($u['email'], $u['nombre_completo'], $codigo, $descuento, $fecha);
            }
        }

        $_SESSION['success'] = "Cupón creado correctamente y correos enviados.";
        redireccionar("?c=adminCupones&a=index");
    }

    private function enviarEmail($email, $nombre, $codigo, $descuento, $fecha) {
        $mail = new PHPMailer(true);

        try {
            $mail->CharSet = 'UTF-8';

            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'coworkingbookingsystem@gmail.com';
            $mail->Password   = 'cwnr glxs taee xqrj';
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('coworkingbookingsystem@gmail.com', 'Coworking App');
            $mail->addAddress($email, $nombre);

            $mail->isHTML(true);
            $mail->Subject = '¡Tienes un nuevo cupón de descuento!';
            $mail->Body    = "
                <p>Hola <strong>$nombre</strong>,</p>
                <p>Hemos creado un nuevo cupón para ti:</p>
                <ul>
                    <li><strong>Código:</strong> $codigo</li>
                    <li><strong>Descuento:</strong> $descuento%</li>
                    <li><strong>Válido hasta:</strong> $fecha</li>
                </ul>
                <p>¡Aprovecha tu descuento antes de que expire!</p>
                <p>El equipo de Coworking App</p>
            ";

            $mail->send();
        } catch (Exception $e) {
            error_log("Error al enviar cupón: {$mail->ErrorInfo}");
        }
    }

    public function editar() {
        redireccionarSiNoEsAdmin();
        global $pdo;
        $id = $_GET['id'];
        $cupon = (new Cupon($pdo))->obtenerPorId($id);
        $usuarios = (new Usuario($pdo))->obtenerTodos();
        require_once __DIR__ . '/../views/admin/cupones/editar.php';
    }

    public function actualizar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_POST['id'];
        $codigo = $_POST['codigo'];
        $descuento = $_POST['descuento'];
        $fecha = $_POST['fecha_expiracion'];
        $usuario_id = $_POST['usuario_id'] !== '' ? $_POST['usuario_id'] : null;

        (new Cupon($pdo))->actualizar($id, $codigo, $descuento, $fecha, $usuario_id);

        $_SESSION['success'] = "Cupón actualizado correctamente.";
        redireccionar("?c=adminCupones&a=index");
    }

    public function eliminar() {
        redireccionarSiNoEsAdmin();
        global $pdo;
        $id = $_GET['id'];
        (new Cupon($pdo))->eliminar($id);
        $_SESSION['success'] = "Cupón eliminado.";
        redireccionar("?c=adminCupones&a=index");
    }
}