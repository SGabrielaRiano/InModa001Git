<?php
// index.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once "config/conexion.php";
include "includes/header.php";
include "includes/sidebar.php";
?>
<main class="main-content">
    <div class="main-header">
        <h1>Panel principal</h1>
        <div>
            <strong><?= htmlspecialchars($_SESSION['nombre']) ?></strong> — <?= htmlspecialchars($_SESSION['rol']) ?>
        </div>
    </div>

    <section class="data-table-container">
        <h2>Resumen rápido</h2>
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
            <!-- total productos -->
            <?php
            $res = $mysqli->query("SELECT COUNT(*) as total FROM productos");
            $totalP = ($res) ? $res->fetch_assoc()['total'] : 0;
            $res2 = $mysqli->query("SELECT SUM(total) as ventas_semana FROM ventas WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
            $ventasSemana = ($res2) ? $res2->fetch_assoc()['ventas_semana'] : 0;
            ?>
            <div class="card" style="min-width:200px">
                <h3>Productos</h3>
                <p style="font-size:1.6em;"><?= $totalP ?></p>
            </div>
            <div class="card" style="min-width:200px">
                <h3>Ventas (últimos 7 días)</h3>
                <p style="font-size:1.6em;">$ <?= number_format((float)$ventasSemana, 0, ',', '.') ?></p>
            </div>
            <div class="card" style="min-width:200px">
                <h3>Notas</h3>
                <?php
                $stmt = $mysqli->prepare("SELECT COUNT(*) as n FROM notas WHERE usuario_id = ?");
                $stmt->bind_param("i", $_SESSION['user_id']);
                $stmt->execute();
                $stmt->bind_result($notasCount);
                $stmt->fetch();
                $stmt->close();
                ?>
                <p style="font-size:1.6em;"><?= $notasCount ?></p>
            </div>
        </div>
    </section>

    <section class="data-table-container">
        <h2>Accesos rápidos</h2>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <a class="btn-update" href="modules/productos.php">Administrar Productos</a>
            <a class="btn-update" href="modules/ventas.php">Registrar Venta</a>
            <a class="btn-update" href="modules/reportes.php">Ver Reportes</a>
        </div>
    </section>
</main>
<?php include "includes/footer.php"; ?>
