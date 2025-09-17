<?php  
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "OLESCSUB16";
$dbname = "sporthub";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebe os dados do formulário
$nome = $_POST['name'];
$cpf = $_POST['last_name'];
$nascimento = $_POST['birthdate'];
$email = $_POST['email'];
$senha = $_POST['password'];
$confirmarSenha = $_POST['confirm_password'];
$tipo = $_POST['gender']; // 'atleta' ou 'clube'

// Validação de senha
if ($senha !== $confirmarSenha) {
    echo "<script>alert('Senhas não coincidem!'); window.history.back();</script>";
    exit();
}

// Verifica se o e-mail já está cadastrado
$stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "<script>alert('Este e-mail já está cadastrado.'); window.history.back();</script>";
    exit();
}
$stmt->close();

// Criptografa a senha
$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

// Prepara o insert com segurança
$stmt = $conn->prepare("INSERT INTO usuarios (nome, cpf_cnpj, nascimento, email, senha, tipo) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $nome, $cpf, $nascimento, $email, $senhaHash, $tipo);

if ($stmt->execute()) {
    echo "<script>
            alert('Cadastro realizado com sucesso!');
            window.location.href = '../index.html';
          </script>";
    exit();
} else {
    echo "<script>alert('Erro ao cadastrar: " . $stmt->error . "'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>