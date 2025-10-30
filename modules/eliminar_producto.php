<?php
// modules/eliminar_producto.php
session_start();
if (!isset($_SESSION['user_id'])) header("Location: ../login.php");
require_once "../config/conexion.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id) {
    $stmt = $mysqli->prepare("DELETE FROM productos WHERE id=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();
}
header("Location: productos.php");
exit;
