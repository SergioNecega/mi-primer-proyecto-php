<?php
header('Content-Type: application/json');
require_once 'conexion.php';

try {
    $stmt = $pdo->query("SELECT id, nombre, email, creado_en FROM usuarios ORDER BY id DESC");
    $usuarios = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'data'    => $usuarios
    ]);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error al obtener usuarios'
    ]);
}
