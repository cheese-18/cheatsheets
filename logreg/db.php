<?php

mysqli_report(MYSQLI_REPORT_OFF);
$conn = mysqli_connect('localhost', 'root', '', 'logreg');

if (!$conn) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed.'
    ]);
    exit;
}

mysqli_set_charset($conn, 'utf8mb4');
