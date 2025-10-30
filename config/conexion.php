<?php

$servername = "localhost";   // Servidor local (XAMPP)
$username   = "root";        // Usuario predeterminado de MySQL en XAMPP
$password   = "";            // Contraseña vacía 
$database   = "inmoda_db";   // Nombre exacto de la base de datos creada en phpMyAdmin

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar conexión
if ($conn->connect_error) {
    die("<h3 style='color:red; text-align:center; margin-top:20%;'>
    ❌ Error de conexión a la base de datos: " . $conn->connect_error . "<br>
    Verifica que el servidor MySQL esté activo y la base de datos 'inmoda_db' exista.
    </h3>");
} else {

}

// Configurar el conjunto de caracteres a UTF-8 para evitar errores con tildes o ñ
$conn->set_charset("utf8mb4");

// Función auxiliar opcional para ejecutar consultas de forma segura
function ejecutarConsulta($sql) {
    global $conn;
    $resultado = $conn->query($sql);
    if (!$resultado) {
        echo "<p style='color:red;'>⚠️ Error en la consulta SQL: " . $conn->error . "</p>";
    }
    return $resultado;
}
?>
