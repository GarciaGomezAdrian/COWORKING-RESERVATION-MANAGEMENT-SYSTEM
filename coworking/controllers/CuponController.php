<?php

require_once __DIR__ . '/../models/Cupon.php';

class CuponController {
    public function validar() {
        global $pdo;

        $codigo = $_POST['codigo'] ?? '';
        $usuario_id = $_POST['usuario_id'] ?? null;

        if (!$codigo || !$usuario_id) {
            echo json_encode(['valido' => false, 'mensaje' => 'Datos incompletos.']);
            return;
        }

        $cupon = (new Cupon($pdo))->validarCupon($codigo, $usuario_id);

        if ($cupon) {
            echo json_encode([
                'valido' => true,
                'codigo' => $cupon['codigo'],
                'descuento' => $cupon['descuento'],
                'mensaje' => 'Cupón válido'
            ]);
        } else {
            echo json_encode(['valido' => false, 'mensaje' => 'Cupón inválido o caducado.']);
        }
    }
}