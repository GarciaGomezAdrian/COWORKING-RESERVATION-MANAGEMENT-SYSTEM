<?php
session_start();
require_once '../config/db.php';
require_once '../models/Cupon.php';

header('Content-Type: application/json');

$codigo = $_POST['codigo'] ?? '';
$usuario_id = $_SESSION['usuario']['id'] ?? null;

if (!$codigo || !$usuario_id) {
    echo json_encode(['success' => false, 'mensaje' => 'Cupón inválido.']);
    exit;
}

$cuponModel = new Cupon($pdo);
$cupon = $cuponModel->validarCupon($codigo, $usuario_id);

if ($cupon) {
    echo json_encode([
        'success' => true,
        'codigo' => $cupon['codigo'],
        'descuento' => $cupon['descuento']
    ]);
} else {
    echo json_encode(['success' => false, 'mensaje' => 'Cupón no válido o caducado.']);
}
