<?php
// modules/inventario.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$res = $mysqli->query("SELECT i.*, p.nombre as producto FROM inventario i LEFT JOIN productos p ON i.producto_id = p.id ORDER BY i.fecha_movimiento DESC LIMIT 200");
?>
<main class="main-content">
    <div class="main-header"><h1>Movimientos de Inventario</h1></div>
    <div class="data-table-container">
        <table class="data-table">
            <thead><tr><th>Fecha</th><th>Producto</th><th>Movimiento</th><th>Cantidad</th></tr></thead>
            <tbody>
            <?php while($r = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= $r['fecha_movimiento'] ?></td>
                    <td><?= htmlspecialchars($r['producto']) ?></td>
                    <td><?= $r['movimiento'] ?></td>
                    <td><?= $r['cantidad'] ?></td>
                </tr>
            <?php endwhile; ?> 
            </tbody>
        </table>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
