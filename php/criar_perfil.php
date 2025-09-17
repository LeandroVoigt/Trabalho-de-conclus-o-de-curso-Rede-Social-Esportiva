<?php
session_start();
if (!isset($_SESSION['id']) || $_SESSION['usuario_tipo'] !== 'clube') {
    die("Você precisa estar logado como clube.");
}

include 'conexao.php';
$id_usuario_logado = $_SESSION['id'];

// Busca o perfil do clube
$sql = "SELECT * FROM clubes WHERE id_usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario_logado);
$stmt->execute();
$result = $stmt->get_result();
$perfil = $result->fetch_assoc(); // pode ser null se não existir

if (!$perfil) {
    // perfil inexistente → inicializa array vazio para não quebrar o HTML
    $perfil = [
        'id' => null,
        'nome' => '',
        'biografia' => '',
        'foto_perfil' => ''
    ];
}

$id_clube = $perfil['id'];

// ---------------------------
// Salvar perfil
// ---------------------------
if (isset($_POST['salvar'])) {
    $nome = $_POST['nome'];
    $biografia = $_POST['biografia'];
    $foto = $_FILES['foto'];

    if ($foto['error'] === UPLOAD_ERR_OK) {
        $foto_nome = time() . '_' . basename($foto['name']);
        $foto_caminho = __DIR__ . '/imagemperfilclube/' . $foto_nome;
        $foto_relativo = 'imagemperfilclube/' . $foto_nome;
        move_uploaded_file($foto['tmp_name'], $foto_caminho);
    } else {
        $foto_relativo = $_POST['foto_atual'] ?? '';
    }

    if ($perfil['id'] ?? false) {
        // EXISTENTE → UPDATE
        $sql = "UPDATE clubes SET nome=?, biografia=?, foto_perfil=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $biografia, $foto_relativo, $perfil['id']);
        $stmt->execute();
    } else {
        // NOVO → INSERT
        $sql = "INSERT INTO clubes (nome, biografia, foto_perfil, id_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nome, $biografia, $foto_relativo, $id_usuario_logado);
        $stmt->execute();
        $perfil['id'] = $stmt->insert_id; // agora $perfil['id'] existe
    }
}





// ---------------------------
// Ligas
// ---------------------------
if (isset($_POST['finalizar'])) {
    $liga = $_POST['liga_sugerida'] ?: $_POST['competicao'];
    $categoria = $_POST['categoria'];
    $logo = $_POST['logo_sugerida'] ?? null;

    if (!empty($liga) && !empty($categoria)) {
        $sql = "INSERT INTO ligas (id_clube, liga, categoria, logo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $id_clube, $liga, $categoria, $logo);
        $stmt->execute();
    }
}

if (isset($_POST['remover_liga'])) {
    $id_liga = $_POST['id_liga'];
    $sql = "DELETE FROM ligas WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_liga);
    $stmt->execute();
}

// ---------------------------
// Peneiras
// ---------------------------
$isOwner = true; // dono do clube

// Adicionar peneira
if ($isOwner && isset($_POST['add_peneira'])) {
    $categoria   = $_POST['categoria_peneira'];
    $data        = $_POST['data_peneira'];
    $local       = $_POST['local_peneira'];
    $informacoes = $_POST['informacoes_peneira'];
    $link        = $_POST['link_peneira'];

    $sql = "INSERT INTO peneiras (id_clube, categoria, data, local, informacoes, link_inscricao) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssss", $id_clube, $categoria, $data, $local, $informacoes, $link);
    $stmt->execute();
}

// Remover peneira
if ($isOwner && isset($_POST['remove_peneira'])) {
    $id_peneira = $_POST['id_peneira'];
    $sql = "DELETE FROM peneiras WHERE id=? AND id_clube=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_peneira, $id_clube);
    $stmt->execute();
}

// Buscar peneiras
$sql = "SELECT * FROM peneiras WHERE id_clube=? ORDER BY data ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_clube);
$stmt->execute();
$peneiras = $stmt->get_result();
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil do Clube</title>
  <link rel="stylesheet" href="../css/criar_perfil.css">
  <style>
    /* estilo dos blocos de ligas */
    .ligas-container {
      margin: 20px 0;
    }
    .ligas-container h3 {
      margin-bottom: 10px;
      font-size: 18px;
      font-weight: bold;
    }
    .ligas-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
      gap: 15px;
    }
    .liga-card {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px;
      border-radius: 10px;
      background: #f5fbff;
      box-shadow: 0 2px 6px rgba(0,0,0,0.08);
      cursor: pointer;
      transition: 0.2s;
    }
    .liga-card:hover {
      background: #eaf6ff;
    }
    .liga-card img {
      width: 35px;
      height: 35px;
      object-fit: contain;
    }
    .liga-card span {
      font-weight: 600;
      color: #002855;
      font-size: 15px;
    }
  </style>
</head>
<body>
  <div class="perfil-container">

    <header class="header">
      <div class="title">Sport HUB</div>
      <div class="buttons">
        <input type="text" id="searchInput" placeholder="Buscar clube..." class="search-input">
        <button id="notificacoes-btn">🔔 Notificações</button>
        <a href="ClubesPeneiras.php" class="profile-btn">👤 Principal</a>
      </div>
    </header>

    <form action="criar_perfil.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo isset($perfil) ? $perfil['id'] : ''; ?>">
      <input type="hidden" name="foto_atual" value="<?php echo isset($perfil) ? $perfil['foto_perfil'] : 'default-profile.png'; ?>">

      <div class="perfil-layout">  
        <div class="foto-perfil-container">
          <img src="<?php echo isset($perfil) ? $perfil['foto_perfil'] : 'imagemperfilclube/default-profile.png'; ?>" alt="Foto de Perfil" class="foto-perfil">
          <input type="file" name="foto" id="upload-foto" accept="image/*">
        </div>

        <div class="info-perfil-container">
          <input type="text" name="nome" class="nome-usuario" placeholder="Digite seu nome" value="<?php echo isset($perfil) ? $perfil['nome'] : ''; ?>"/>
          <textarea name="biografia" class="bio-usuario" placeholder="Digite sua biografia"><?php echo isset($perfil) ? $perfil['biografia'] : ''; ?></textarea>
        </div>
      </div>

      <button type="submit" name="salvar" class="botao-salvar">Salvar Perfil</button>
    </form>

    <!-- BLOCO DE LIGAS NO PERFIL -->
    <div class="ligas-container">
      <h3>Competições</h3>
      <div class="ligas-grid">
        
      </div>
    </div>

    <!-- Botão para adicionar liga -->
    <button class="botao-adicionar-liga">Adicionar Liga</button>

    <!-- Tabela de ligas -->
    <div class="tabela-ligas" style="display: none;">
      <form action="criar_perfil.php" method="POST">
        <table>
          <thead>
            <tr>
              <th>Ligas Sugeridas</th>
              <th>Competição</th>
              <th>Categoria</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <select name="liga_sugerida" class="liga-select">
                  <option value="">Selecione uma Liga</option>
                  <option value="Campeonato Catarinense Série A" data-img="logos/catarinense_a.png">Campeonato Catarinense Série A</option>
                  <option value="Campeonato Catarinense Série B" data-img="logos/catarinense_b.png">Campeonato Catarinense Série B</option>
                  <option value="Campeonato Brasileiro Série D" data-img="logos/brasileirao_d.png">Brasileirão Série D</option>
                  <option value="Copa Santa Catarina" data-img="logos/copa_sc.png">Copa Santa Catarina</option>
                </select>
              </td>
              <td><input type="text" name="competicao" id="competicao" placeholder="Digite a Competição"/></td>
              <td><input type="text" name="categoria" placeholder="Digite a Categoria"/></td>
            </tr>
          </tbody>
        </table>
        <button type="submit" name="finalizar" id="finalizar-liga">Finalizar</button>


        
      </form>
    
    </div>
    <h3>Peneiras</h3>
  
    <h3>Estádios</h3>

    <h3>Fotos</h3>
                  
    <h3>Contatos</h3>
                  
    <footer class="footer">
      <div class="footer-content">
        <div class="footer-left">
          <h4>Sport HUB</h4>
          <p>Conectando atletas, clubes e oportunidades pelo Brasil.</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2025 Sport HUB. Todos os direitos reservados.</p>
      </div>

  
      
    </footer>
  </div>

  <script>
    // botão de adicionar liga
    document.querySelector('.botao-adicionar-liga').addEventListener('click', function() {
      document.querySelector('.tabela-ligas').style.display = 'block';
    });

    // bloquear o campo "competição" quando escolher liga
    const selectLiga = document.querySelector('.liga-select');
    const campoCompeticao = document.getElementById('competicao');

    selectLiga.addEventListener('change', function () {
      if (selectLiga.value !== "") {
        campoCompeticao.value = selectLiga.value;
        campoCompeticao.readOnly = true; // trava digitação
      } else {
        campoCompeticao.value = "";
        campoCompeticao.readOnly = false; // libera caso não selecione
      }
    });
  </script>
</body>
</html>