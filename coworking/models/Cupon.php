<?php
class Cupon {
    private $db;
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function validarCupon($codigo, $usuario_id) {
        $stmt = $this->db->prepare("
            SELECT * FROM cupones 
            WHERE codigo = ? 
            AND (usuario_id IS NULL OR usuario_id = ?) 
            AND fecha_expiracion >= CURDATE()
            LIMIT 1
        ");
        $stmt->execute([$codigo, $usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function obtenerUltimoCuponValido($usuario_id) {
        $stmt = $this->db->prepare("SELECT * FROM cupones WHERE (usuario_id = ? OR usuario_id IS NULL) AND fecha_expiracion >= CURDATE() ORDER BY id DESC LIMIT 1");
        $stmt->execute([$usuario_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }    

    public function obtenerTodos() {
        $stmt = $this->db->query("
            SELECT c.*, u.nombre_completo 
            FROM cupones c 
            LEFT JOIN usuarios u ON c.usuario_id = u.id
            ORDER BY c.fecha_expiracion DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function obtenerPorId($id) {
        $stmt = $this->db->prepare("SELECT * FROM cupones WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function crear($codigo, $descuento, $fecha_expiracion, $usuario_id = null) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM cupones WHERE codigo = ?");
        $stmt->execute([$codigo]);
        if ($stmt->fetchColumn() > 0) {
            throw new Exception("El código del cupón ya existe.");
        }
    
        $stmt = $this->db->prepare("
            INSERT INTO cupones (codigo, descuento, fecha_expiracion, usuario_id)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$codigo, $descuento, $fecha_expiracion, $usuario_id]);
    }    
    
    public function actualizar($id, $codigo, $descuento, $fecha, $usuario_id) {
        $stmt = $this->db->prepare("UPDATE cupones SET codigo = ?, descuento = ?, fecha_expiracion = ?, usuario_id = ? WHERE id = ?");
        return $stmt->execute([$codigo, $descuento, $fecha, $usuario_id, $id]);
    }
    
    public function eliminar($id) {
        $stmt = $this->db->prepare("DELETE FROM cupones WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function contarConFiltro($busqueda) {
        $sql = "SELECT COUNT(*) FROM cupones WHERE codigo LIKE ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $busqueda . '%']);
        return $stmt->fetchColumn();
    }
    
    public function buscarConFiltro($busqueda, $pagina, $por_pagina) {
        $offset = ($pagina - 1) * $por_pagina;
    
        $sql = "
            SELECT c.*, u.nombre_completo 
            FROM cupones c 
            LEFT JOIN usuarios u ON c.usuario_id = u.id
            WHERE c.codigo LIKE ?
            ORDER BY c.fecha_expiracion DESC
            LIMIT $por_pagina OFFSET $offset
        ";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['%' . $busqueda . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}