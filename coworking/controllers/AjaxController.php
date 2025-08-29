<?php

require_once __DIR__ . '/../models/Cupon.php';

class AjaxController {
    public function validarCupon() {
        if (!isset($_SESSION['usuario'])) {
            echo json_encode(['error' => 'Usuario no autenticado']);
            exit;
        }

        global $pdo;
        $codigo = $_POST['codigo'] ?? '';
        $usuario_id = $_SESSION['usuario']['id'];

        $cupon = (new Cupon($pdo))->validarCupon($codigo, $usuario_id);

        if (!$cupon) {
            echo json_encode(['error' => 'Cupón no válido o caducado']);
        } else {
            echo json_encode([
                'codigo' => $cupon['codigo'],
                'descuento' => $cupon['descuento']
            ]);
        }
    }
}
