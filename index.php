<?php
$host = 'localhost';
$db   = 'mi_proyecto_db';
$user = 'dev_user';
$pass = 'password123';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $stmt = $pdo->query('SELECT * FROM usuarios');
    
    echo "<h1>Conexión exitosa a MariaDB</h1>";
    echo "<h3>Lista de usuarios guardados:</h3>";
    echo "<ul>";
    while ($row = $stmt->fetch()) {
        echo "<li><strong>" . htmlspecialchars($row['nombre']) . "</strong> - " . htmlspecialchars($row['email']) . "</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "<h2>Error de conexión a la base de datos:</h2>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
