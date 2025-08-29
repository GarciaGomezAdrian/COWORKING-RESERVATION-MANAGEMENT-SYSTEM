<?php
class Notificacion {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function crear($usuario_id, $mensaje, $tipo) {
        $stmt = $this->db->prepare("INSERT INTO notificaciones (usuario_id, mensaje, tipo_notificacion) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $mensaje, $tipo]);
    }

    public function obtenerTodasConUsuario() {
        $stmt = $this->db->query("
            SELECT n.*, u.nombre_completo 
            FROM notificaciones n 
            JOIN usuarios u ON n.usuario_id = u.id 
            ORDER BY n.fecha_envio DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }  

    public function filtrar($tipo, $usuario_nombre, $fecha_inicio, $fecha_fin, $pagina = 1, $por_pagina = 10) {
        $sql = "
            SELECT n.*, u.nombre_completo
            FROM notificaciones n
            JOIN usuarios u ON n.usuario_id = u.id
            WHERE 1 = 1
        ";
        $params = [];
    
        if ($tipo !== '') {
            $sql .= " AND tipo_notificacion = ?";
            $params[] = $tipo;
        }
    
        if ($usuario_nombre !== '') {
            $sql .= " AND u.nombre_completo LIKE ?";
            $params[] = '%' . $usuario_nombre . '%';
        }
    
        if ($fecha_inicio !== '') {
            $sql .= " AND DATE(n.fecha_envio) >= ?";
            $params[] = $fecha_inicio;
        }
    
        if ($fecha_fin !== '') {
            $sql .= " AND DATE(n.fecha_envio) <= ?";
            $params[] = $fecha_fin;
        }
    
        $sql .= " ORDER BY n.fecha_envio DESC LIMIT " . intval($por_pagina) . " OFFSET " . intval(($pagina - 1) * $por_pagina);
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    
    
    public function contarConFiltro($tipo, $usuario_nombre, $fecha_inicio, $fecha_fin) {
        $sql = "SELECT COUNT(*) as total
                FROM notificaciones n
                JOIN usuarios u ON n.usuario_id = u.id
                WHERE 1 = 1";
        $params = [];
    
        if ($tipo !== '') {
            $sql .= " AND LOWER(n.tipo_notificacion) = LOWER(?)";
            $params[] = $tipo;
        }
    
        if ($usuario_nombre !== '') {
            $sql .= " AND LOWER(u.nombre_completo) LIKE LOWER(?)";
            $params[] = "%" . $usuario_nombre . "%";
        }
    
        if ($fecha_inicio !== '') {
            $sql .= " AND DATE(n.fecha_envio) >= ?";
            $params[] = $fecha_inicio;
        }
    
        if ($fecha_fin !== '') {
            $sql .= " AND DATE(n.fecha_envio) <= ?";
            $params[] = $fecha_fin;
        }
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }    
}
