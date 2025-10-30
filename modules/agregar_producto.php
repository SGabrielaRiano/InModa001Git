<?php
// modules/agregar_producto.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $referencia = trim($_POST['referencia']);
    $categoria = ($_POST['categoria'] ?: null);
    $proveedor = ($_POST['proveedor'] ?: null);
    $precio = (float)$_POST['precio'];
    $cantidad = (int)$_POST['cantidad'];
    $desc = trim($_POST['descripcion']);

    $stmt = $mysqli->prepare("INSERT INTO productos (nombre, referencia, categoria_id, proveedor_id, precio, cantidad, descripcion) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssiiids", $nombre, $referencia, $categoria, $proveedor, $precio, $cantidad, $desc);
    if ($stmt->execute()) {
        // registrar movimiento de inventario
        $pid = $stmt->insert_id;
        $stmt2 = $mysqli->prepare("INSERT INTO inventario (producto_id, movimiento, cantidad) VALUES (?, 'Entrada', ?)");
        $stmt2->bind_param("ii", $pid, $cantidad);
        $stmt2->execute();
        $stmt2->close();

        $mensaje = "Producto agregado correctamente.";
    } else {
        $mensaje = "Error al agregar producto.";
    }
    $stmt->close();
}

// cargar categorias y proveedores
$cats = $mysqli->query("SELECT id, nombre FROM categorias");
$provs = $mysqli->query("SELECT id, nombre FROM proveedores");
?>
<main class="main-content">
    <div class="main-header"><h1>Agregar producto</h1></div>
    <?php if($mensaje) echo "<div style='color:green'>$mensaje</div>"; ?>
    <div class="data-table-container">
        <form method="post">
            <label>Nombre</label><br>
            <input name="nombre" required><br><br>

            <label>Referencia</label><br>
            <input name="referencia"><br><br>

            <label>Categoría</label><br>
            <select name="categoria">
                <option value="">-- Seleccionar --</option>
                <?php while($c = $cats->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Proveedor</label><br>
            <select name="proveedor">
                <option value="">-- Seleccionar --</option>
                <?php while($p = $provs->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Precio</label><br>
            <input name="precio" type="number" step="0.01" value="0"><br><br>

            <label>Cantidad</label><br>
            <input name="cantidad" type="number" value="0"><br><br>

            <label>Descripción</label><br>
            <textarea name="descripcion"></textarea><br><br>

            <button class="btn-update" type="submit">Guardar producto</button>
        </form>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
