<?php

class Reserva {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function crear($usuario_id, $espacio_id, $inicio, $fin) {
        $stmt = $this->db->prepare("INSERT INTO reservas (usuario_id, espacio_id, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$usuario_id, $espacio_id, $inicio, $fin]);
        return $this->db->lastInsertId();
    }

    public function obtenerPorUsuario($usuario_id) {
        $stmt = $this->db->prepare("
            SELECT r.id, r.espacio_id, r.estado, e.nombre, r.fecha_inicio, r.fecha_fin
            FROM reservas r
            JOIN espacios e ON r.espacio_id = e.id
            WHERE r.usuario_id = ?
            ORDER BY r.fecha_inicio DESC
        ");
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function obtenerDetalle($reserva_id, $usuario_id) {
        $stmt = $this->db->prepare("
            SELECT r.*, e.nombre, e.ubicacion, e.descripcion, e.precio_dia, e.id as espacio_id
            FROM reservas r
            JOIN espacios e ON r.espacio_id = e.id
            WHERE r.id = ? AND r.usuario_id = ?
        ");
        $stmt->execute([$reserva_id, $usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function tieneValoracion($reserva_id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM valoraciones 
            WHERE espacio_id = (
                SELECT espacio_id FROM reservas WHERE id = ?
            )
            AND usuario_id = (
                SELECT usuario_id FROM reservas WHERE id = ?
            )
        ");
        $stmt->execute([$reserva_id, $reserva_id]);
        return $stmt->fetchColumn() > 0;
    }

    public function obtenerTodas() {
        $stmt = $this->db->query("
            SELECT r.*, u.nombre_completo, e.nombre AS espacio 
            FROM reservas r 
            JOIN usuarios u ON r.usuario_id = u.id 
            JOIN espacios e ON r.espacio_id = e.id 
            ORDER BY r.fecha_reserva DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.nombre_completo, u.email, e.nombre AS espacio 
            FROM reservas r 
            JOIN usuarios u ON r.usuario_id = u.id 
            JOIN espacios e ON r.espacio_id = e.id 
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function actualizarAdmin($id, $inicio, $fin, $estado) {
        $stmt = $this->db->prepare("UPDATE reservas SET fecha_inicio = ?, fecha_fin = ?, estado = ? WHERE id = ?");
        return $stmt->execute([$inicio, $fin, $estado, $id]);
    }
    
    public function cancelar($id) {
        $stmt = $this->db->prepare("UPDATE reservas SET estado = 'cancelada' WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function contarFiltradas($cliente = '', $fecha_inicio = '', $fecha_fin = '') {
        $sql = "
            SELECT COUNT(*) 
            FROM reservas r
            JOIN usuarios u ON r.usuario_id = u.id
            WHERE 1=1
        ";
        $params = [];
    
        if ($cliente !== '') {
            $sql .= " AND (u.nombre_completo LIKE ? OR u.email LIKE ?)";
            $params[] = "%$cliente%";
            $params[] = "%$cliente%";
        }
    
        if ($fecha_inicio !== '') {
            $sql .= " AND r.fecha_inicio >= ?";
            $params[] = $fecha_inicio;
        }
    
        if ($fecha_fin !== '') {
            $sql .= " AND r.fecha_fin <= ?";
            $params[] = $fecha_fin;
        }
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    public function obtenerFiltradas($cliente = '', $fecha_inicio = '', $fecha_fin = '', $pagina = 1, $por_pagina = 10) {
        $offset = ($pagina - 1) * $por_pagina;
    
        $sql = "
            SELECT r.*, u.nombre_completo, e.nombre AS espacio
            FROM reservas r
            JOIN usuarios u ON r.usuario_id = u.id
            JOIN espacios e ON r.espacio_id = e.id
            WHERE 1=1
        ";
        $params = [];
    
        if ($cliente !== '') {
            $sql .= " AND (u.nombre_completo LIKE ? OR u.email LIKE ?)";
            $params[] = "%$cliente%";
            $params[] = "%$cliente%";
        }
    
        if ($fecha_inicio !== '') {
            $sql .= " AND r.fecha_inicio >= ?";
            $params[] = $fecha_inicio;
        }
    
        if ($fecha_fin !== '') {
            $sql .= " AND r.fecha_fin <= ?";
            $params[] = $fecha_fin;
        }
    
        $sql .= " ORDER BY r.fecha_reserva DESC LIMIT $por_pagina OFFSET $offset";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
}
