<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o usuário está logado
if (!isset($_SESSION['id'])) {
    die("Usuário não autenticado.");
}

include 'conexao.php'; // conecta ao banco usando mysqli

$id_usuario = $_SESSION['id']; // id do usuário logado

// Busca clube associado ao usuário logado
$sql = "SELECT * FROM clubes WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da query: " . $conn->error);
}

$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc();

if ($perfil) {
    // Perfil já existe: redireciona para visualizar
    header("Location: visualizar_perfil.php?id=" . $perfil['id']);
    exit;
} else {
    // Perfil ainda não existe: redireciona para criar
    header("Location: criar_perfil.php");
    exit;
}
?>