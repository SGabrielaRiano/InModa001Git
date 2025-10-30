<?php
// modules/ventas.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

// cargar productos
$prods = $mysqli->query("SELECT id, nombre, precio, cantidad FROM productos ORDER BY nombre ASC");
$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
    $producto_id = (int)$_POST['producto_id'];
    $cantidad = (int)$_POST['cantidad'];
    // obtener precio
    $stmt = $mysqli->prepare("SELECT precio, cantidad FROM productos WHERE id = ?");
    $stmt->bind_param("i",$producto_id); $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$r || $r['cantidad'] < $cantidad) {
        $mensaje = "Stock insuficiente.";
    } else {
        $precio_unit = $r['precio'];
        $total = $precio_unit * $cantidad;
        $mysqli->begin_transaction();
        try {
            $stmt = $mysqli->prepare("INSERT INTO ventas (usuario_id, total) VALUES (?, ?)");
            $stmt->bind_param("id", $_SESSION['user_id'], $total);
            $stmt->execute();
            $venta_id = $stmt->insert_id;
            $stmt->close();

            $stmt2 = $mysqli->prepare("INSERT INTO venta_detalle (venta_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
            $stmt2->bind_param("iiid", $venta_id, $producto_id, $cantidad, $precio_unit);
            $stmt2->execute();
            $stmt2->close();

            // actualizar inventario
            $stmt3 = $mysqli->prepare("UPDATE productos SET cantidad = cantidad - ? WHERE id = ?");
            $stmt3->bind_param("ii", $cantidad, $producto_id);
            $stmt3->execute();
            $stmt3->close();

            // registrar movimiento
            $stmt4 = $mysqli->prepare("INSERT INTO inventario (producto_id, movimiento, cantidad) VALUES (?, 'Salida', ?)");
            $stmt4->bind_param("ii", $producto_id, $cantidad);
            $stmt4->execute();
            $stmt4->close();

            $mysqli->commit();
            $mensaje = "Venta registrada correctamente. Total: $".number_format($total,0,',','.');
        } catch (Exception $e) {
            $mysqli->rollback();
            $mensaje = "Error al registrar venta.";
        }
    }
}
?>
<main class="main-content">
    <div class="main-header"><h1>Registrar Venta</h1></div>
    <?php if($mensaje) echo "<div style='color:green;margin-bottom:10px;'>$mensaje</div>"; ?>
    <div class="data-table-container">
        <form method="post">
            <label>Producto</label><br>
            <select name="producto_id" required>
                <option value="">-- Seleccionar --</option>
                <?php while($p = $prods->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?> (Stock: <?= $p['cantidad'] ?>)</option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Cantidad</label><br>
            <input name="cantidad" type="number" value="1" min="1" required><br><br>

            <button class="btn-update" type="submit">Confirmar Venta</button>
        </form>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
