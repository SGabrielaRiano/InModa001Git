<?php
// modules/editar_producto.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0; 
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $referencia = trim($_POST['referencia']);
    $categoria = ($_POST['categoria'] ?: null);
    $proveedor = ($_POST['proveedor'] ?: null);
    $precio = (float)$_POST['precio'];
    $cantidad = (int)$_POST['cantidad'];
    $desc = trim($_POST['descripcion']);
    $stmt = $mysqli->prepare("UPDATE productos SET nombre=?, referencia=?, categoria_id=?, proveedor_id=?, precio=?, cantidad=?, descripcion=? WHERE id=?");
    $stmt->bind_param("ssiiisii", $nombre, $referencia, $categoria, $proveedor, $precio, $cantidad, $desc, $id);
    if ($stmt->execute()) $msg = "Producto actualizado.";
    else $msg = "Error al actualizar.";
    $stmt->close();
}

$stmt = $mysqli->prepare("SELECT * FROM productos WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$res = $stmt->get_result();
$product = $res->fetch_assoc();
$stmt->close();

$cats = $mysqli->query("SELECT id, nombre FROM categorias");
$provs = $mysqli->query("SELECT id, nombre FROM proveedores");
?>
<main class="main-content">
    <div class="main-header"><h1>Editar producto</h1></div>
    <?php if($msg) echo "<div style='color:green'>$msg</div>"; ?>
    <div class="data-table-container">
        <form method="post">
            <label>Nombre</label><br>
            <input name="nombre" value="<?= htmlspecialchars($product['nombre']) ?>" required><br><br>

            <label>Referencia</label><br>
            <input name="referencia" value="<?= htmlspecialchars($product['referencia']) ?>"><br><br>

            <label>Categoría</label><br>
            <select name="categoria">
                <option value="">-- Seleccionar --</option>
                <?php while($c = $cats->fetch_assoc()): ?>
                    <option value="<?= $c['id'] ?>" <?= $product['categoria_id']==$c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nombre']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Proveedor</label><br>
            <select name="proveedor">
                <option value="">-- Seleccionar --</option>
                <?php while($p = $provs->fetch_assoc()): ?>
                    <option value="<?= $p['id'] ?>" <?= $product['proveedor_id']==$p['id'] ? 'selected' : '' ?>><?= htmlspecialchars($p['nombre']) ?></option>
                <?php endwhile; ?>
            </select><br><br>

            <label>Precio</label><br>
            <input name="precio" type="number" step="0.01" value="<?= $product['precio'] ?>"><br><br>

            <label>Cantidad</label><br>
            <input name="cantidad" type="number" value="<?= $product['cantidad'] ?>"><br><br>

            <label>Descripción</label><br>
            <textarea name="descripcion"><?= htmlspecialchars($product['descripcion']) ?></textarea><br><br>

            <button class="btn-update" type="submit">Actualizar</button>
        </form>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
