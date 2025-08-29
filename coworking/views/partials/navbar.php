<?php if (!isset($ocultar_navbar) || !$ocultar_navbar): ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="?c=home">Coworking</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">
                <?php if (!isset($_SESSION['usuario'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?c=auth&a=login">Iniciar sesión</a>
                    </li>
                <?php elseif ($_SESSION['usuario']['tipo_usuario'] === 'cliente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?c=usuario&a=reservas">Mis reservas</a>
                    </li>
                <?php elseif ($_SESSION['usuario']['tipo_usuario'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?c=admin&a=index">Panel de Administración</a>
                    </li>
                <?php endif; ?>
            </ul>
            <?php if (isset($_SESSION['usuario'])): ?>
                <span class="navbar-text me-3">
                    <?= $_SESSION['usuario']['tipo_usuario'] === 'admin' ? 'Administrador:' : 'Bienvenido:' ?>
                    <a href="<?= $_SESSION['usuario']['tipo_usuario'] === 'admin' ? '?c=admin&a=perfil' : '?c=usuario&a=perfil' ?>">
                        <?= htmlspecialchars($_SESSION['usuario']['nombre_completo']) ?>
                    </a>
                </span>

                <a class="btn btn-outline-light" href="?c=auth&a=logout">Cerrar sesión</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
<?php endif; ?>
