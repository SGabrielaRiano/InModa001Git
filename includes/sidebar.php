<?php
// includes/sidebar.php
if (session_status() === PHP_SESSION_NONE) session_start();
$base = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\');
if ($base === '') $base = '.';
$nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Invitado';
$rol = isset($_SESSION['rol']) ? $_SESSION['rol'] : 'Vendedor';
?>
<aside class="sidebar">
    <div>
        <div class="sidebar-header">
            <img src="<?= $base ?>/images/inmoda-logo.png" alt="Logo" class="logo-main">
            <h2>InModa</h2>
        </div>

        <nav class="sidebar-nav">
            <ul>
                <li><a href="<?= $base ?>/index.php">Inicio</a></li>
                <li><a href="<?= $base ?>/modules/productos.php">Productos</a></li>
                <li><a href="<?= $base ?>/modules/inventario.php">Inventario</a></li>
                <li><a href="<?= $base ?>/modules/ventas.php">Ventas</a></li>
                <li><a href="<?= $base ?>/modules/proveedores.php">Proveedores</a></li>
                <li><a href="<?= $base ?>/modules/reportes.php">Reportes</a></li>
                <li><a href="<?= $base ?>/modules/notas.php">Notas</a></li>
                <?php if($rol === 'Administrador'): ?>
                    <li><a href="<?= $base ?>/modules/usuarios.php">Usuarios</a></li>
                <?php endif; ?>
                <li><a href="<?= $base ?>/profile.php">Perfil</a></li>
                <li><a href="<?= $base ?>/logout.php">Cerrar sesión</a></li>
            </ul>
        </nav>
    </div>

    <div class="sidebar-footer-icons">
        <span class="icon">📞</span>
        <span class="icon">⚙️</span>
        <span class="icon">🔒</span>
    </div>
</aside>
