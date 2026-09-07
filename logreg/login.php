<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'POST requests only.']);
    exit;
}

require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

$email = strtolower(trim($data['email'] ?? ''));
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Please fill in every field.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
    exit;
}

$stmt = mysqli_prepare($conn, 'SELECT name, password FROM users WHERE email = ?');

if (!$stmt) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error.']);
    exit;
}

mysqli_stmt_bind_param($stmt, 's', $email);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $name, $hash);

if (mysqli_stmt_fetch($stmt) && password_verify($password, $hash)) {
    echo json_encode([
        'success' => true,
        'message' => 'Login successful.',
        'name' => $name
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password.']);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
