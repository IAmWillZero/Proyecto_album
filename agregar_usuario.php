<?php
include 'ap/config.php'; // Asegúrate de que la ruta a config.php es correcta

// Verifica si la conexión se estableció correctamente
if (!$conexion) {
    die("Error: No se ha podido establecer la conexión a la base de datos.");
}

// Nombre de usuario y contraseña a insertar
$username = "user"; // Cambia "user" por el nombre de usuario que deseas
$password = password_hash("admin", PASSWORD_DEFAULT); // Cambia "admin" por la contraseña deseada

// Selecciona la base de datos
$dbname = "album_personajes"; // Reemplaza con el nombre de tu base de datos
$conexion->exec("USE `$dbname`");

try {
    // Prepara la consulta SQL
    $sql = "INSERT INTO usuarios (nombre_usuario, password) VALUES (:username, :password)";
    $stmt = $conexion->prepare($sql);
    
    // Bind de parámetros
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);

    // Ejecuta la consulta
    if ($stmt->execute()) {
        echo "Usuario añadido exitosamente.";
    } else {
        echo "Error: No se pudo añadir el usuario.";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
