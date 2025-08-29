<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Obtener el controlador y la acción desde los parámetros GET

$controlador = $_GET['c'] ?? 'home';
$accion = $_GET['a'] ?? 'index';

// Sanitizar los parámetros

$controlador = preg_replace('/[^a-zA-Z0-9_]/', '', $controlador);
$accion = preg_replace('/[^a-zA-Z0-9_]/', '', $accion);

// Construir el nombre de la clase del controlador y la ruta del archivo
$controladorClass = ucfirst($controlador) . 'Controller';
$archivo = __DIR__ . "/controllers/$controladorClass.php";

// Verificar si el archivo del controlador existe

if (file_exists($archivo)) {
    require_once $archivo;

    if (class_exists($controladorClass)) {
        $objeto = new $controladorClass();

        if (method_exists($objeto, $accion)) {

            if ($controladorClass === 'AjaxController') {
                header('Content-Type: application/json');
            }

            $objeto->$accion();
        } else {
            echo "<h2>⚠️ Acción '$accion' no encontrada en el controlador '$controladorClass'.</h2>";
        }
    } else {
        echo "<h2>⚠️ Clase controlador '$controladorClass' no definida.</h2>";
    }
} else {
    echo "<h2>⚠️ Archivo controlador '$archivo' no encontrado.</h2>";
}
// Fin del archivo router.php