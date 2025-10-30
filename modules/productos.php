<?php
// modules/productos.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

// cargar lista de productos con JOIN para mostrar nombres de categoria/proveedor
$sql = "SELECT p.*, c.nombre AS categoria_nombre, prov.nombre AS proveedor_nombre
        FROM productos p
        LEFT JOIN categorias c ON p.categoria_id = c.id
        LEFT JOIN proveedores prov ON p.proveedor_id = prov.id
        ORDER BY p.id DESC";
$res = $mysqli->query($sql);
?>
<main class="main-content">
    <div class="main-header">
        <h1>Productos</h1>
        <a href="agregar_producto.php" class="btn-update">+ Nuevo producto</a>
    </div>

    <div class="data-table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th><th>Nombre</th><th>Categoría</th><th>Proveedor</th><th>Precio</th><th>Cantidad</th><th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $res->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['nombre']) ?></td>
                    <td><?= htmlspecialchars($row['categoria_nombre']) ?></td>
                    <td><?= htmlspecialchars($row['proveedor_nombre']) ?></td>
                    <td>$ <?= number_format($row['precio'],0,',','.') ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td>
                        <a href="editar_producto.php?id=<?= $row['id'] ?>">Editar</a> |
                        <a href="eliminar_producto.php?id=<?= $row['id'] ?>" onclick="return confirm('Eliminar producto?')">Eliminar</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
