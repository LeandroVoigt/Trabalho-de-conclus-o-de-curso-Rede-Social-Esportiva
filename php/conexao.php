<?php
$host = 'localhost';
$db = 'sporthub';
$user = 'root';
$pass = 'OLESCSUB16';

// Criar conexão mysqli
$conn = new mysqli($host, $user, $pass, $db);

// Checar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

// Definir charset para evitar problemas com acentuação
$conn->set_charset("utf8mb4");
?>