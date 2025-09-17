<!DOCTYPE html>
<html lang="pt-br">
<head>



    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($perfil['nome_clube']); ?></title>
    <link rel="stylesheet" href="">

    

</head>
<body>
    <div class="profile-header">
        <img src="<?php echo htmlspecialchars($perfil['escudo_url']); ?>" class="profile-pic" alt="Escudo">
        <div class="profile-info">
            <h2><?php echo htmlspecialchars($perfil['nome_clube']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($perfil['biografia'])); ?></p>
        </div>
    </div>

    <section class="competitions">
        <h3>Competições</h3>
        <p><?php echo nl2br(htmlspecialchars($perfil['competicoes'])); ?></p>
    </section>

    <section class="tryouts">
        <h3>Peneiras</h3>
        <p><?php echo nl2br(htmlspecialchars($perfil['peneiras'])); ?></p>
    </section>

    <section class="stadiums">
        <h3>Estádios</h3>
        <p><?php echo nl2br(htmlspecialchars($perfil['estadios'])); ?></p>
    </section>

    <section class="fotos-section">
        <h3>Fotos</h3>
        <div class="fotos-grid">
            <?php
            $fotos = explode(',', $perfil['fotos']);
            foreach ($fotos as $foto) {
                echo "<img src='" . trim($foto) . "' class='foto-post'>";
            }
            ?>
        </div>
    </section>

    <section class="contatos">
        <h2>Contatos</h2>
        <p>Email: <a href="mailto:<?php echo htmlspecialchars($perfil['email_contato']); ?>"><?php echo htmlspecialchars($perfil['email_contato']); ?></a></p>
        <p>Site oficial: <a href="<?php echo htmlspecialchars($perfil['site_contato']); ?>" target="_blank"><?php echo htmlspecialchars($perfil['site_contato']); ?></a></p>
        <p>Instagram: <a href="https://instagram.com/<?php echo htmlspecialchars($perfil['instagram_contato']); ?>" target="_blank">@<?php echo htmlspecialchars($perfil['instagram_contato']); ?></a></p>
    </section>

    
</body>
</html>

<?php
session_start();
include 'conexao.php';

$id_usuario = $_SESSION['id'];

$sql = "SELECT * FROM perfil_clube WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$id_usuario]);

if ($stmt->rowCount() == 0) {
    header("Location: criar_perfil.php");
    exit;
}

$perfil = $stmt->fetch(PDO::FETCH_ASSOC);
?>

