<?php
// modules/proveedores.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_proveedor'])) {
    $nombre = trim($_POST['nombre']); $nit = trim($_POST['nit']);
    $tel = trim($_POST['telefono']); $correo = trim($_POST['correo']); $dir = trim($_POST['direccion']);
    $stmt = $mysqli->prepare("INSERT INTO proveedores (nombre, nit, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $nit, $tel, $correo, $dir);
    $stmt->execute(); $stmt->close();
}
$res = $mysqli->query("SELECT * FROM proveedores ORDER BY id DESC");
?>
<main class="main-content">
    <div class="main-header"><h1>Proveedores</h1></div>

    <div class="data-table-container">
        <form method="post" style="margin-bottom:15px;">
            <input name="nombre" placeholder="Nombre proveedor" required>
            <input name="nit" placeholder="NIT">
            <input name="telefono" placeholder="Teléfono">
            <input name="correo" placeholder="Correo">
            <input name="direccion" placeholder="Dirección">
            <button class="btn-update" type="submit" name="add_proveedor">Agregar</button>
        </form>

        <table class="data-table">
            <thead><tr><th>ID</th><th>Nombre</th><th>Contacto</th></tr></thead>
            <tbody>
                <?php while($p = $res->fetch_assoc()): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['telefono'].' / '.$p['correo']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
