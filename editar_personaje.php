<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
include 'ap/config.php';

// Verificar si se ha recibido el ID del personaje por parámetro GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID de personaje no especificado.";
    exit();
}

$id_personaje = $_GET['id'];

// Obtener los datos del personaje a editar
$sql = "SELECT * FROM personajes WHERE id = :id";
$stmt = $conexion->prepare($sql);
$stmt->bindParam(':id', $id_personaje, PDO::PARAM_INT);

try {
    $stmt->execute();
    $personaje = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error al obtener los datos del personaje: " . $e->getMessage();
    exit();
}

// Procesar el formulario de edición cuando se envíe
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    // Actualizar los datos del personaje en la base de datos
    $sql_update = "UPDATE personajes SET nombre = :nombre, descripcion = :descripcion WHERE id = :id";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt_update->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $stmt_update->bindParam(':id', $id_personaje, PDO::PARAM_INT);

    try {
        if ($stmt_update->execute()) {
            echo "Personaje actualizado correctamente.";
            // Redirigir de vuelta al álbum después de la actualización
            header("Location: album.php");
            exit();
        } else {
            echo "Error al actualizar el personaje.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Editar Personaje</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Editar Personaje</h2>
        <form method="post" action="">
            <input type="hidden" name="id_personaje" value="<?php echo $personaje['id']; ?>">
            <label for="nombre">Nombre:</label><br>
            <input type="text" id="nombre" name="nombre" value="<?php echo $personaje['nombre']; ?>"><br><br>
            <label for="descripcion">Descripción:</label><br>
            <textarea id="descripcion" name="descripcion"><?php echo $personaje['descripcion']; ?></textarea><br><br>
            <input type="submit" value="Guardar Cambios">
        </form>
    </div>
</body>
</html>

<?php
$conexion = null;
?>
