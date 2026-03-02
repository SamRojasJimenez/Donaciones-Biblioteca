<?php
header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding("UTF-8");
// Configuración de la base de datos
$host = getenv('DB_HOST') ?: 'db';
$db = getenv('DB_NAME') ?: 'biblioteca_db';
$user = getenv('DB_USER') ?: 'user_donaciones';
$pass = getenv('DB_PASS') ?: 'user_password';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
}
catch (\PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

// Lógica de Acciones (CRUD)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Insertar  donación
    if (isset($_POST['action']) && $_POST['action'] === 'insert') {
        $donante = $_POST['donante'] ?? '';
        $titulo = $_POST['titulo'] ?? '';
        if (!empty($donante) && !empty($titulo)) {
            $stmt = $pdo->prepare("INSERT INTO donaciones (donante, titulo_libro) VALUES (?, ?)");
            $stmt->execute([$donante, $titulo]);
        }
    }

    // Cambiar estado
    if (isset($_POST['action']) && $_POST['action'] === 'toggle_status') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("UPDATE donaciones SET procesado_inventario = NOT procesado_inventario WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Eliminar
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM donaciones WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Obtener donaciones
$donaciones = $pdo->query("SELECT * FROM donaciones ORDER BY fecha_donacion DESC")->fetchAll();

// Leer reporte  por Python
$reporte = "Cargando reporte...";
if (file_exists('reports/reporte.txt')) {
    $reporte = file_get_contents('reports/reporte.txt');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Control de Donaciones - Biblioteca</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #a93321;
            --secondary: #4f46e5;
            --bg: #0f172a;
            --card-bg: #2db692;
            --text: #000000;
            --accent: #39403e;
            --danger: #ef4444;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            padding: 2rem;
            line-height: 1.6;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .profile-card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            gap: 2rem;
            margin-bottom: 3rem;
            border: 1px solid #334155;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
        }

        .section-title {
            color: var(--primary);
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
        }

        .card {
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
        }

        form {
            display: grid;
            gap: 1rem;
            grid-template-columns: 1fr 1fr auto;
        }

        input {
            background: #0f172a;
            border: 1px solid #334155;
            padding: 0.8rem;
            color: white;
            border-radius: 0.5rem;
        }

        button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        button:hover {
            background: var(--secondary);
            transform: translateY(-2px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        th, td {
            text-align: left;
            padding: 1rem;
            border-bottom: 1px solid #334155;
        }

        .badge {
            padding: 0.2rem 0.6rem;
            border-radius: 1rem;
            font-size: 0.8rem;
        }

        .badge-success { background: var(--accent); color: white; }
        .badge-pending { background: #f59e0b; color: white; }

        .report-box {
            background: #000;
            color: #10b981;
            padding: 1.5rem;
            font-family: 'Courier New', monospace;
            border-radius: 0.5rem;
            border-left: 4px solid #10b981;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1 style="color: white;">Donaciones a la biblioteca de la Universidad</h1>
        </header>

        <div class="grid" style="display:grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <!-- Gestión de Donaciones -->
            <div class="main-content">
                <div class="card">
                    <h3 class="section-title">Nueva Donación</h3>
                    <form method="POST">
                        <input type="hidden" name="action" value="insert">
                        <input type="text" name="donante" placeholder="Nombre del donante" required>
                        <input type="text" name="titulo" placeholder="Título del libro" required>
                        <button type="submit">Agregar</button>
                    </form>
                </div>

                <div class="card">
                    <h3 class="section-title">Inventario de Libros</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Libro</th>
                                <th>Donante</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($donaciones as $row): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['titulo_libro']); ?></td>
                                <td><?php echo htmlspecialchars($row['donante']); ?></td>
                                <td>
                                    <span class="badge <?php echo $row['procesado_inventario'] ? 'badge-success' : 'badge-pending'; ?>">
                                        <?php echo $row['procesado_inventario'] ? 'Procesado' : 'Pendiente'; ?>
                                    </span>
                                </td>
                                <td>
                                    <form method="POST" style="display:inline; grid-template-columns: auto;">
                                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                        <button type="submit" name="action" value="toggle_status" style="background:#475569; padding: 0.4rem 0.8rem; font-size: 0.8rem;">ESTADO</button>
                                        <button type="submit" name="action" value="delete" style="background:var(--danger); padding: 0.4rem 0.8rem; font-size: 0.8rem;">ELIMINAR</button>
                                    </form>
                                </td>
                            </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Reporte generado por Python -->
            <div class="sidebar">
                <div class="card">
                    <div class="report-box"><?php echo htmlspecialchars($reporte); ?></div>
                    <button onclick="window.location.reload();" style="margin-top:1rem; width:100%; background:#334155;">Actualizar Reporte</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
