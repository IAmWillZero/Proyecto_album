<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
include 'ap/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $imagen = $_POST['imagen'];
    $descripcion = $_POST['descripcion'];

    // Preparar la consulta SQL para insertar el personaje
    $sql = "INSERT INTO personajes (nombre, imagen, descripcion) VALUES (:nombre, :imagen, :descripcion)";
    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':imagen', $imagen, PDO::PARAM_STR);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);

    try {
        if ($stmt->execute()) {
            echo "Personaje agregado correctamente.";
            header("Location: album.php");
        } else {
            echo "Error al agregar el personaje.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
