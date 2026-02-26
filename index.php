<?php
$host = 'db';
$db = 'biblioteca_db';
$user = 'user_donaciones';
$pass = 'user_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
}
catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


if (isset($_POST['agregar'])) {
    $donante = $_POST['donante'];
    $libro = $_POST['titulo'];

    $sql = "INSERT INTO donaciones (donante, titulo_libro) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$donante, $libro]);
}

if (isset($_GET['cambiar'])) {
    $id = $_GET['cambiar'];
    $pdo->query("UPDATE donaciones SET procesado_inventario = NOT procesado_inventario WHERE id = $id");
}

$query = $pdo->query("SELECT * FROM donaciones");
$donaciones = $query->fetchAll(PDO::FETCH_ASSOC);

$reporte = "No hay reporte";
if (file_exists("reports/reporte.txt")) {
    $reporte = file_get_contents("reports/reporte.txt");
}

?>
<!DOCTYPE html>
<html>

<body>
    <h1>Control de Donaciones de Libros</h1>
    
    
    <pre><?php echo $reporte; ?></pre>

    <hr>

    <h2>Registrar nueva donación</h2>
    <form method="POST">
        Nombre Donante: <input type="text" name="donante"> <br>
        Título del Libro: <input type="text" name="titulo"> <br>
        <input type="submit" name="agregar" value="Guardar Donación">
    </form>

    <hr>

    <h2>Inventario de Libros Donados</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Donante</th>
            <th>Libro</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($donaciones as $d): ?>
        <tr>
            <td><?php echo $d['id']; ?></td>
            <td><?php echo $d['donante']; ?></td>
            <td><?php echo $d['titulo_libro']; ?></td>
            <td><?php echo($d['procesado_inventario']) ? "Procesado" : "Pendiente"; ?></td>
            <td>
                <a href="?cambiar=<?php echo $d['id']; ?>">Cambiar Estado</a>
            </td>
        </tr>
        <?php
endforeach; ?>
    </table>

</body>
</html>
