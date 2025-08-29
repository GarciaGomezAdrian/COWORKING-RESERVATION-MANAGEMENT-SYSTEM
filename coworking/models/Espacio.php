<?php

class Espacio {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM espacios WHERE disponible = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerDisponibles($fecha_inicio, $fecha_fin) {
        $sql = "SELECT * FROM espacios WHERE disponible = 1 AND id NOT IN (
                    SELECT espacio_id FROM reservas 
                    WHERE (fecha_inicio <= ? AND fecha_fin >= ?)
                    OR (fecha_inicio <= ? AND fecha_fin >= ?)
                    OR (fecha_inicio >= ? AND fecha_fin <= ?)
                )";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $fecha_fin, $fecha_fin,
            $fecha_inicio, $fecha_inicio,
            $fecha_inicio, $fecha_fin
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM espacios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerDiasReservados($espacio_id) {
        $stmt = $this->db->prepare("SELECT fecha_inicio, fecha_fin FROM reservas 
                                    WHERE espacio_id = ? AND estado = 'confirmada'");
        $stmt->execute([$espacio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerValoraciones($espacio_id) {
        $stmt = $this->db->prepare("SELECT v.mensaje, v.fecha_valoracion, u.nombre_completo 
                                    FROM valoraciones v
                                    JOIN usuarios u ON v.usuario_id = u.id
                                    WHERE v.espacio_id = ?
                                    ORDER BY v.fecha_valoracion DESC");
        $stmt->execute([$espacio_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerTodosAdmin() {
        $stmt = $this->db->query("SELECT * FROM espacios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarConFiltro($busqueda, $pagina, $por_pagina) {
        $offset = ($pagina - 1) * $por_pagina;
        $sql = "SELECT * FROM espacios WHERE nombre LIKE ? ORDER BY id DESC LIMIT $por_pagina OFFSET $offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $busqueda . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarConFiltro($busqueda) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM espacios WHERE nombre LIKE ?");
        $stmt->execute(['%' . $busqueda . '%']);
        return $stmt->fetchColumn();
    }

    public function crear($nombre, $descripcion, $capacidad, $precio_dia, $ubicacion) {
        $stmt = $this->db->prepare("INSERT INTO espacios (nombre, descripcion, capacidad, precio_dia, ubicacion) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $capacidad, $precio_dia, $ubicacion]);
        return $this->db->lastInsertId();
    }

    public function actualizar($id, $nombre, $descripcion, $capacidad, $precio, $ubicacion) {
        $stmt = $this->db->prepare("UPDATE espacios SET nombre = ?, descripcion = ?, capacidad = ?, precio_dia = ?, ubicacion = ? WHERE id = ?");
        return $stmt->execute([$nombre, $descripcion, $capacidad, $precio, $ubicacion, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM espacios WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
