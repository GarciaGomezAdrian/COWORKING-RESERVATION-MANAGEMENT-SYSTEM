<?php

require_once __DIR__ . '/../models/Espacio.php';

class AdminEspaciosController {
    public function index() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $busqueda = $_GET['busqueda'] ?? '';
        $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
        $por_pagina = 10;

        $espacioModel = new Espacio($pdo);
        $total = $espacioModel->contarConFiltro($busqueda);
        $espacios = $espacioModel->buscarConFiltro($busqueda, $pagina, $por_pagina);

        $total_paginas = ceil($total / $por_pagina);

        require_once __DIR__ . '/../views/admin/espacios/index.php';
    }

    public function crear() {
        redireccionarSiNoEsAdmin();
        require_once __DIR__ . '/../views/admin/espacios/crear.php';
    }

    public function guardar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $capacidad = (int)$_POST['capacidad'];
        $precio = (float)$_POST['precio_dia'];
        $ubicacion = trim($_POST['ubicacion']);

        if (!$nombre || !$capacidad || !$precio || !$ubicacion) {
            $_SESSION['error'] = "Todos los campos son obligatorios.";
            redireccionar("?c=adminEspacios&a=crear");
        }

        $espacioModel = new Espacio($pdo);
        $id = $espacioModel->crear($nombre, $descripcion, $capacidad, $precio, $ubicacion);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen'];
            $extensionesPermitidas = ['image/jpeg', 'image/png'];

            if (!in_array($imagen['type'], $extensionesPermitidas)) {
                $_SESSION['error'] = "Solo se permiten imágenes JPG o PNG.";
                redireccionar("?c=adminEspacios&a=crear");
            }

            [$ancho, $alto] = getimagesize($imagen['tmp_name']);
            if ($ancho > 800 || $alto > 800) {
                $_SESSION['error'] = "La imagen no debe superar los 800x800 píxeles.";
                redireccionar("?c=adminEspacios&a=crear");
            }

            $destino = __DIR__ . '/../public/img/espacios/' . $id . '.jpg';
            move_uploaded_file($imagen['tmp_name'], $destino);
        }

        $_SESSION['success'] = "Espacio creado correctamente.";
        redireccionar("?c=adminEspacios&a=index");
    }

    public function editar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_GET['id'] ?? null;
        if (!$id) redireccionar("?c=adminEspacios");

        $espacio = (new Espacio($pdo))->obtenerPorId($id);
        if (!$espacio) {
            $_SESSION['error'] = "Espacio no encontrado.";
            redireccionar("?c=adminEspacios");
        }

        require_once __DIR__ . '/../views/admin/espacios/editar.php';
    }

    public function actualizar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_POST['id'];
        $nombre = trim($_POST['nombre']);
        $descripcion = trim($_POST['descripcion']);
        $capacidad = (int)$_POST['capacidad'];
        $precio = (float)$_POST['precio_dia'];
        $ubicacion = trim($_POST['ubicacion']);

        if (!$id || !$nombre || !$capacidad || !$precio || !$ubicacion) {
            $_SESSION['error'] = "Datos inválidos.";
            redireccionar("?c=adminEspacios&a=editar&id=" . $id);
        }

        (new Espacio($pdo))->actualizar($id, $nombre, $descripcion, $capacidad, $precio, $ubicacion);

        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $_FILES['imagen'];
            $extensionesPermitidas = ['image/jpeg', 'image/png'];

            if (!in_array($imagen['type'], $extensionesPermitidas)) {
                $_SESSION['error'] = "Solo se permiten imágenes JPG o PNG.";
                redireccionar("?c=adminEspacios&a=editar&id=" . $id);
            }

            [$ancho, $alto] = getimagesize($imagen['tmp_name']);
            if ($ancho > 800 || $alto > 800) {
                $_SESSION['error'] = "La imagen no debe superar los 800x800 píxeles.";
                redireccionar("?c=adminEspacios&a=editar&id=" . $id);
            }

            $destino = __DIR__ . '/../public/img/espacios/' . $id . '.jpg';
            move_uploaded_file($imagen['tmp_name'], $destino);
        }

        $_SESSION['success'] = "Espacio actualizado correctamente.";
        redireccionar("?c=adminEspacios&a=index");
    }

    public function eliminar() {
        redireccionarSiNoEsAdmin();
        global $pdo;

        $id = $_GET['id'] ?? null;
        if (!$id) redireccionar("?c=adminEspacios");

        (new Espacio($pdo))->eliminar($id);
        $_SESSION['success'] = "Espacio eliminado correctamente.";
        redireccionar("?c=adminEspacios&a=index");
    }
}
