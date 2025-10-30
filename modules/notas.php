<?php
// modules/notas.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";
include "../includes/header.php";
include "../includes/sidebar.php";

$uid = $_SESSION['user_id'];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardar_nota'])) {
    $titulo = trim($_POST['titulo']);
    $contenido = trim($_POST['contenido']);
    $stmt = $mysqli->prepare("INSERT INTO notas (usuario_id, titulo, contenido) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $uid, $titulo, $contenido);
    if ($stmt->execute()) $msg = "Nota guardada.";
    $stmt->close();
}
$notas = $mysqli->prepare("SELECT id, titulo, contenido, creado_en FROM notas WHERE usuario_id = ? ORDER BY id DESC");
$notas->bind_param("i", $uid);
$notas->execute();
$notasRes = $notas->get_result();
?>
<main class="main-content">
    <div class="main-header"><h1>Mis Notas</h1></div>
    <?php if($msg) echo "<div style='color:green'>$msg</div>"; ?>
    <div class="data-table-container">
        <form method="post">
            <label>Título</label><br>
            <input name="titulo" required><br><br>

            <label>Contenido</label><br>
            <textarea name="contenido" required></textarea><br><br>

            <button class="btn-update" type="submit" name="guardar_nota">Guardar nota</button>
        </form>

        <h3>Notas guardadas</h3>
        <ul>
            <?php while($n = $notasRes->fetch_assoc()): ?>
                <li><strong><?= htmlspecialchars($n['titulo']) ?></strong> — <?= htmlspecialchars(substr($n['contenido'],0,120)) ?>... <em>(<?= $n['creado_en'] ?>)</em></li>
            <?php endwhile; ?>
        </ul>
    </div>
</main>
<?php include "../includes/footer.php"; ?>
