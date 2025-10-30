<?php
// modules/usuarios.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
if ($_SESSION['rol'] !== 'Administrador') {
    header("Location: ../index.php");
    exit;
}
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $usuario = trim($_POST['usuario']); $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']); $rol = $_POST['rol']; $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $stmt = $mysqli->prepare("INSERT INTO usuarios (usuario, nombre, email, password, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $usuario, $nombre, $email, $pass, $rol);
    if ($stmt->execute()) $msg = "Usuario creado.";
    else $msg = "Error al crear usuario.";
    $stmt->close();
}
$users = $mysqli->query("SELECT id, usuario, nombre, email, rol, creado_en FROM usuarios ORDER BY id DESC");
?>
<main class="main-content">
    <div class="main-header"><h1>Usuarios</h1></div>
    <?php if($msg) echo "<div style='color:green'>$msg</div>"; ?>
    <div class="data-table-container">
        <form method="post" style="margin-bottom:15px;">
            <input name="usuario" placeholder="usuario" required>
            <input name="nombre" placeholder="nombre">
            <input name="email" placeholder="email">
            <select name="rol">
                <option value="Vendedor">Vendedor</option>
                <option value="Administrador">Administrador</option>
            </select>
            <input name="password" placeholder="contraseña" required>
            <button class="btn-update" type="submit" name="add_user">Crear</button>
        </form>

        <table class="data-table">
            <thead><tr><th>ID</th><th>Usuario</th><th>Nombre</th><th>Email</th><th>Rol</th></tr></thead>
            <tbody>
                <?php while($u = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['usuario']) ?></td>
                        <td><?= htmlspecialchars($u['nombre']) ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><?= $u['rol'] ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
