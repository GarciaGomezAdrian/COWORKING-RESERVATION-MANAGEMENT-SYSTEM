<?php


function mostrarMensaje($tipo, $mensaje) {
    echo "<div class='alert alert-$tipo' role='alert'>$mensaje</div>";
}

function redireccionar($ruta) {
    header("Location: $ruta");
    exit();
}

function soloAdmin() {
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'administrador') {
        $_SESSION['error'] = "Acceso denegado. Solo administradores.";
        redireccionar("?c=auth&a=login");
    }
}

function usuarioAutenticado() {
    return isset($_SESSION['usuario']);
}

function esCliente() {
    return usuarioAutenticado() && $_SESSION['usuario']['tipo_usuario'] === 'cliente';
}

function esAdmin() {
    return usuarioAutenticado() && $_SESSION['usuario']['tipo_usuario'] === 'admin';
}

function redireccionarSiNoAutenticado() {
    if (!usuarioAutenticado()) {
        $_SESSION['error'] = "Debes iniciar sesión.";
        redireccionar("?c=auth&a=login");
    }
}

function redireccionarSiNoEsCliente() {
    if (!esCliente()) {
        $_SESSION['error'] = "Acceso restringido a clientes.";
        redireccionar("?c=home");
    }
}

function redireccionarSiNoEsAdmin() {
    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] !== 'admin') {
        $_SESSION['error'] = "Acceso denegado.";
        redireccionar("?c=auth&a=login");
        exit;
    }
}

function formatearFecha($fecha) {
    return date('d/m/Y', strtotime($fecha));
}
