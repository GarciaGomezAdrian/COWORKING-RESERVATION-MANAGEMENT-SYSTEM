<?php

require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    public function login() {
        require_once __DIR__ . '/../views/login.php';
    }

    public function registro() {
        require_once __DIR__ . '/../views/register.php';
    }

    public function loginPost() {
        global $pdo;

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "Debes introducir un correo electrónico válido.";
            redireccionar("?c=auth&a=login");
            return;
        }

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = "Debes introducir un correo electrónico y contraseña.";
            redireccionar("?c=auth&a=login");
            return;
        }

        $usuarioModel = new Usuario($pdo);
        $usuario = $usuarioModel->obtenerPorEmail($email);

        if ($usuario && password_verify($password, $usuario['contraseña'])) {
            session_regenerate_id(true);
            $_SESSION['usuario'] = $usuario;
            $_SESSION['success'] = "Inicio de sesión con éxito.";
            redireccionar("?c=home&a=index");
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectas.";
            redireccionar("?c=auth&a=login");
        }
    }

    public function registroPost() {
        global $pdo;

        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $nombre = htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8');

        if (empty($nombre) || empty($email) || empty($password)) {
            $_SESSION['error'] = "Debes rellenar el formulario.";
            redireccionar("?c=auth&a=registro");
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "El formato del correo electrónico no es válido.";
            redireccionar("?c=auth&a=registro");
            return;
        }

        if (
            strlen($password) < 8 || strlen($password) > 20 ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W_]/', $password)
        ) {
            $_SESSION['error'] = "La contraseña debe tener entre 8 y 20 caracteres, e incluir mayúsculas, minúsculas, números y símbolos.";
            redireccionar("?c=auth&a=registro");
            return;
        }

        $usuarioModel = new Usuario($pdo);

        if ($usuarioModel->obtenerPorEmail($email)) {
            $_SESSION['error'] = "Este correo ya está en uso.";
            redireccionar("?c=auth&a=registro");
            return;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $usuarioModel->crear($nombre, $email, $hash);

        $_SESSION['success'] = "Cuenta creada exitosamente, ya puedes iniciar sesión.";
        redireccionar("?c=auth&a=login");
    }

    public function logout() {
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['success'] = "Sesión cerrada correctamente.";
        redireccionar("?c=auth&a=login");
    }
}
