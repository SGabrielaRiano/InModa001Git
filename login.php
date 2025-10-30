<?php
// login.php
session_start();
require_once "config/conexion.php";

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];

    $stmt = $mysqli->prepare("SELECT id, usuario, password, nombre, rol FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $row = $res->fetch_assoc()) {
        if (password_verify($clave, $row['password'])) {
            // login ok
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['nombre'] = $row['nombre'];
            $_SESSION['rol'] = $row['rol'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Login - InModa</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <div class="logo-top">
                <img src="images/inmoda-logo.png" alt="InModa Logo">
            </div>
            <div class="welcome-section">
                <span class="hand-wave-icon">👋</span>
                <h1>Bienvenida a InModa</h1>
                <p>Ingresa con tu usuario y contraseña</p>
            </div>
            <?php if($error): ?>
                <div style="color: var(--color-red); margin-bottom:10px;"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" class="login-form">
                <div class="form-group">
                    <label for="usuario">Usuario</label>
                    <input id="usuario" name="usuario" type="text" required>
                </div>
                <div class="form-group">
                    <label for="clave">Contraseña</label>
                    <input id="clave" name="clave" type="password" required>
                </div>
                <button class="btn-acceder" type="submit">Acceder</button>
            </form>
        </div>
    </div>
    <div class="help-question-mark">?</div>
</body>
</html>
