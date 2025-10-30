<?php
// profile.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: login.php");
require_once "config/conexion.php";
include "includes/header.php";
include "includes/sidebar.php";

$uid = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $stmt = $mysqli->prepare("UPDATE usuarios SET nombre=?, email=? WHERE id=?");
    $stmt->bind_param("ssi", $nombre, $email, $uid);
    $stmt->execute();
    $stmt->close();
    $_SESSION['nombre'] = $nombre;
    $msg = "Perfil actualizado.";
}
$stmt = $mysqli->prepare("SELECT usuario, nombre, email, rol, creado_en FROM usuarios WHERE id=?");
$stmt->bind_param("i", $uid);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();
?>
<main class="main-content">
    <div class="main-header"><h1>Mi Perfil</h1></div>
    <?php if(isset($msg)) echo "<div style='color:green'>$msg</div>"; ?>
    <div class="data-table-container">
        <form method="post">
            <label>Usuario</label><br>
            <input disabled value="<?= htmlspecialchars($user['usuario']) ?>"><br><br>

            <label>Nombre</label><br>
            <input name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>"><br><br>

            <label>Email</label><br>
            <input name="email" value="<?= htmlspecialchars($user['email']) ?>"><br><br>

            <button class="btn-update" type="submit" name="update_profile">Actualizar</button>
        </form>
    </div>
</main>
<?php include "includes/footer.php"; ?>
