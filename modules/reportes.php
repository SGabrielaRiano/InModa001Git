<?php
// modules/reportes.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

// Ventas totales últimos 7 días y detalle por día
$resTot = $mysqli->query("SELECT IFNULL(SUM(total),0) as sum_total FROM ventas WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$totSemana = $resTot->fetch_assoc()['sum_total'];

// detalle por día
$stmt = $mysqli->prepare("SELECT DATE(fecha) as dia, IFNULL(SUM(total),0) as total FROM ventas WHERE fecha >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY DATE(fecha) ORDER BY DATE(fecha) ASC");
$stmt->execute();
$r = $stmt->get_result();
$detalle = [];
while($row = $r->fetch_assoc()) $detalle[] = $row;
$stmt->close();

// productos más vendidos (últimos 30 días)
$stmt2 = $mysqli->prepare("SELECT p.nombre, SUM(vd.cantidad) as vendidos FROM venta_detalle vd JOIN productos p ON vd.producto_id = p.id JOIN ventas v ON vd.venta_id = v.id WHERE v.fecha >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY p.id ORDER BY vendidos DESC LIMIT 10");
$stmt2->execute();
$top = $stmt2->get_result();
$stmt2->close();
?>
<main class="main-content">
    <div class="main-header"><h1>Reportes y Análisis</h1></div>

    <div class="data-table-container">
        <h3>Ventas últimos 7 días</h3>
        <p>Total semana: <strong>$ <?= number_format($totSemana,0,',','.') ?></strong></p>
        <table class="data-table">
            <thead><tr><th>Fecha</th><th>Total día</th></tr></thead>
            <tbody>
                <?php foreach($detalle as $d): ?>
                    <tr><td><?= $d['dia'] ?></td><td>$ <?= number_format($d['total'],0,',','.') ?></td></tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <h3>Productos más vendidos (30 días)</h3>
        <table class="data-table">
            <thead><tr><th>Producto</th><th>Cantidad vendida</th></tr></thead>
            <tbody>
            <?php while($p = $top->fetch_assoc()): ?>
                <tr><td><?= htmlspecialchars($p['nombre']) ?></td><td><?= $p['vendidos'] ?></td></tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
