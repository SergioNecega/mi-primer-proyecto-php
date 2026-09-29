<?php
header('Content-Type: application/json');
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$nombre = trim($input['nombre'] ?? '');
$email  = trim($input['email'] ?? '');

if (empty($nombre) || empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El nombre y el email son obligatorios']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'El formato del correo no es válido']);
    exit;
}

try {
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (:nombre, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':email'  => $email
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Usuario registrado con éxito',
        'id'      => $pdo->lastInsertId()
    ]);
} catch (\PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al insertar registro']);
}