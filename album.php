<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}
include 'ap/config.php';

// Si se envía una solicitud POST para eliminar un personaje
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    $id_personaje = $_POST['id_personaje'];

    if ($_POST['accion'] == 'eliminar') {
        // Preparar la consulta SQL para eliminar el personaje por su ID
        $sql_delete = "DELETE FROM personajes WHERE id = :id";
        $stmt_delete = $conexion->prepare($sql_delete);
        $stmt_delete->bindParam(':id', $id_personaje, PDO::PARAM_INT);

        try {
            if ($stmt_delete->execute()) {
                echo "Personaje eliminado correctamente.";
                // Redirigir o refrescar la página después de eliminar
                header("Location: album.php");
                exit();
            } else {
                echo "Error al eliminar el personaje.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif ($_POST['accion'] == 'editar') {
        // Redirigir a la página de edición de personaje con el ID
        header("Location: editar_personaje.php?id=" . $id_personaje);
        exit();
    }
}

// Obtener la lista actualizada de personajes después de eliminar
$sql = "SELECT * FROM personajes";
$result = $conexion->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Álbum de Personajes</title>
    <link rel="stylesheet" type="text/css" href="Styles/styles_album.css">
</head>
<body>
    <div class="container">
        <h2>Álbum de Personajes</h2>
        <div class="album">
            <?php
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="personaje">';
                    echo '<img src="' . $row["imagen"] . '" alt="' . $row["nombre"] . '">';
                    echo '<h3>' . $row["nombre"] . '</h3>';
                    echo '<p>' . $row["descripcion"] . '</p>';
                    // Agregar formulario para editar el personaje
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id_personaje" value="' . $row["id"] . '">';
                    echo '<input type="hidden" name="accion" value="editar">';
                    echo '<input type="submit" value="Editar">';
                    echo '</form>';
                    // Agregar formulario para eliminar el personaje
                    echo '<form method="post" action="">';
                    echo '<input type="hidden" name="id_personaje" value="' . $row["id"] . '">';
                    echo '<input type="hidden" name="accion" value="eliminar">';
                    echo '<input type="submit" value="Eliminar">';
                    echo '</form>';
                    echo '</div>';
                }
            } else {
                echo "No hay personajes en el álbum.";
            }
            ?>
        </div>

        <?php if (isset($_SESSION['username'])): ?>
            <a href="agregar_personaje.html" class="btn-agregar">Agregar Personaje</a>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
$conexion = null;
?>
