<?php
// Pasta de destino
$targetDir = "uploads/";

if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

if (isset($_FILES["imagem"]) && $_FILES["imagem"]["error"] == 0) {
    $extensao = pathinfo($_FILES["imagem"]["name"], PATHINFO_EXTENSION);

    // Gera nome único (ex: img_64e6a91f7d2c3.jpg)
    $novoNome = "img_" . uniqid() . "." . strtolower($extensao);
    $caminhoFinal = $targetDir . $novoNome;

    // Move arquivo para a pasta de uploads
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminhoFinal)) {
        echo "Upload realizado com sucesso!<br>";
        echo "Caminho para salvar no banco: <b>" . $caminhoFinal . "</b>";
        
        // Aqui você faria a inserção no banco de dados
        // Exemplo:
        // $sql = "INSERT INTO imagens (caminho) VALUES ('$caminhoFinal')";
    } else {
        echo "Erro ao mover o arquivo!";
    }
} else {
    echo "Nenhum arquivo enviado ou erro no upload.";
}
?>
