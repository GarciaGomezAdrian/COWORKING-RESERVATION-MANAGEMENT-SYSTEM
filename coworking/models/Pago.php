<?php
class Pago {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function crear($reserva_id, $usuario_id, $monto) {
        $stmt = $this->db->prepare("INSERT INTO pagos (reserva_id, usuario_id, monto, estado_pago) VALUES (?, ?, ?, 'confirmado')");
        $stmt->execute([$reserva_id, $usuario_id, $monto]);
    }
}
