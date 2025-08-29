<?php

class Usuario {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function obtenerPorEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $email, $passwordHash) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre_completo, email, contraseña, tipo_usuario) VALUES (?, ?, ?, 'cliente')");
        return $stmt->execute([$nombre, $email, $passwordHash]);
    }

    public function obtenerTodos() {
        $stmt = $this->db->query("SELECT * FROM usuarios ORDER BY fecha_registro DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function actualizar($id, $nombre, $email, $tipo) {
        $stmt = $this->db->prepare("UPDATE usuarios SET nombre_completo = ?, email = ?, tipo_usuario = ? WHERE id = ?");
        return $stmt->execute([$nombre, $email, $tipo, $id]);
    }

    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function obtenerSoloClientes() {
        $stmt = $this->db->query("SELECT * FROM usuarios WHERE tipo_usuario = 'cliente'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarConFiltro($busqueda, $pagina, $por_pagina) {
        $offset = ($pagina - 1) * $por_pagina;
        $sql = "
            SELECT * FROM usuarios
            WHERE nombre_completo LIKE :busqueda OR email LIKE :busqueda
            ORDER BY fecha_registro DESC
            LIMIT $por_pagina OFFSET $offset
        ";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['busqueda' => "%$busqueda%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function contarConFiltro($busqueda) {
        $busqueda = "%$busqueda%";
    
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM usuarios 
            WHERE nombre_completo LIKE ? OR email LIKE ?
        ");
        $stmt->execute([$busqueda, $busqueda]);
        return $stmt->fetchColumn();
    }

    public function actualizarDatosPropios($id, $nombre, $email) {
        $stmt = $this->db->prepare("UPDATE usuarios SET nombre_completo = ?, email = ? WHERE id = ?");
        return $stmt->execute([$nombre, $email, $id]);
    }
    
    public function actualizarContrasena($id, $hash) {
        $stmt = $this->db->prepare("UPDATE usuarios SET contraseña = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }    
}
