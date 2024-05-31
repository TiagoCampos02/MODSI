<?php
// Conectar-se ao banco de dados
$servername = "ave.dee.isep.ipp.pt";
$username = "1201049";
$password = "ZDY3MWU4YWU4ZjQ0";
$dbname = "1201049_ModsiTeste";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados: " . $conn->connect_error);
}

// Recuperar os dados do formulário
$nome = $_POST['nome'];
$password = $_POST['password'];
$username = $_POST['username'];
$dataNascimento = $_POST['dataNascimento'];
$email = $_POST['email'];
$contacto = $_POST['contacto'];
$morada = $_POST['morada'];

// Encriptar a senha
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Inserir os dados no banco de dados
// Preparar e executar a consulta SQL para inserir o usuário no banco de dados
$sql = "INSERT INTO site_user (nome, passwrd, username, dataNascimento, email, contacto, morada, estado, role_id) 
VALUES ('$nome', '$hashed_password', '$username', '$dataNascimento', '$email', '$contacto', '$morada', 1, 1)";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Usuário criado com sucesso!'); window.location.href = '../index.php';</script>";
} else {
    echo "<script>alert('Erro ao registrar usuário: " . $conn->error . "'); window.location.href = 'register.php';</script>";
}

// Fechar conexão com o banco de dados
$conn->close();
?>
