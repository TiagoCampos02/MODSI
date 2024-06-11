<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Conecte-se à base de dados
    $conn = db_connect();

    // Recupere os dados do formulário e escape os caracteres especiais
/*     $email = mysqli_real_escape_string($conn, $_POST['email']);
 */        $email = $_POST['email'];

        $password = $_POST['password'];

    // Consulta para selecionar o usuário com o email fornecido
    $sql = "SELECT * FROM site_user WHERE email = '$email' AND estado = 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // O email foi encontrado, verificar a senha
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['passwrd'])) {
            // Cria a sessão
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nome'] = $row['nome'];
            $_SESSION['dataNascimento'] = $row['dataNascimento'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['contacto'] = $row['contacto'];
            $_SESSION['morada'] = $row['morada'];
            $_SESSION['role_id'] = $row['role_id'];
            $_SESSION['estado'] = $row['estado'];

            // Redirecionar para a página do painel de controle após o login
            header("Location: http://ave.dee.isep.ipp.pt/~1201049/Final/");
            exit();
        } else {
            // Senha incorreta
            echo "<script>alert('Senha incorreta.'); window.location.href = 'login.php';</script>";
        }
    } else {
        // Email não encontrado
        echo "<script>alert('Dados Inválidos'); window.location.href = 'login.php';</script>";
    }

    // Feche a conexão
    $conn->close();
}
?>
