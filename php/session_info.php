<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['status' => 'erro']);
    exit;
}

echo json_encode([
    'status' => 'ok',
    'tipo' => $_SESSION['usuario_tipo'], // atleta ou clube
    'nome' => $_SESSION['usuario_nome']
]);