<?php

class Valoracion {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function filtrar($usuario_nombre, $espacio_nombre, $fecha_inicio, $fecha_fin, $pagina, $por_pagina) {
        $sql = "
            SELECT v.*, u.nombre_completo, e.nombre AS espacio
            FROM valoraciones v
            JOIN usuarios u ON v.usuario_id = u.id
            JOIN espacios e ON v.espacio_id = e.id
            WHERE 1=1
        ";
        $params = [];

        if ($usuario_nombre !== '') {
            $sql .= " AND u.nombre_completo LIKE ?";
            $params[] = "%$usuario_nombre%";
        }

        if ($espacio_nombre !== '') {
            $sql .= " AND e.nombre LIKE ?";
            $params[] = "%$espacio_nombre%";
        }

        if ($fecha_inicio !== '') {
            $sql .= " AND DATE(v.fecha_valoracion) >= ?";
            $params[] = $fecha_inicio;
        }

        if ($fecha_fin !== '') {
            $sql .= " AND DATE(v.fecha_valoracion) <= ?";
            $params[] = $fecha_fin;
        }

        $offset = ($pagina - 1) * $por_pagina;
        $sql .= " ORDER BY v.fecha_valoracion DESC LIMIT $por_pagina OFFSET $offset";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarConFiltro($usuario_nombre, $espacio_nombre, $fecha_inicio, $fecha_fin) {
        $sql = "
            SELECT COUNT(*) 
            FROM valoraciones v
            JOIN usuarios u ON v.usuario_id = u.id
            JOIN espacios e ON v.espacio_id = e.id
            WHERE 1=1
        ";
        $params = [];

        if ($usuario_nombre !== '') {
            $sql .= " AND u.nombre_completo LIKE ?";
            $params[] = "%$usuario_nombre%";
        }

        if ($espacio_nombre !== '') {
            $sql .= " AND e.nombre LIKE ?";
            $params[] = "%$espacio_nombre%";
        }

        if ($fecha_inicio !== '') {
            $sql .= " AND DATE(v.fecha_valoracion) >= ?";
            $params[] = $fecha_inicio;
        }

        if ($fecha_fin !== '') {
            $sql .= " AND DATE(v.fecha_valoracion) <= ?";
            $params[] = $fecha_fin;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM valoraciones WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
