
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <a href="?c=home" class="btn btn-secondary mb-3">Inicio</a>
    <div class="card mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h5 class="card-title text-center">Iniciar Sesión</h5>
            <?php
                if (isset($_SESSION['error'])) mostrarMensaje('danger', $_SESSION['error']);
                if (isset($_SESSION['success'])) mostrarMensaje('success', $_SESSION['success']);
                unset($_SESSION['error'], $_SESSION['success']);
            ?>
            <form method="POST" action="?c=auth&a=loginPost">
                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Contraseña</label>
                    <input type="password" name="password" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
            </form>
            <div class="mt-3 text-center">
                ¿No tienes cuenta? <a href="?c=auth&a=registro">Regístrate aquí</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
