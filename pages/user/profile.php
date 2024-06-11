<?php 
session_start();
include '../../includes/header.php'; 
include '../../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

$user_id = $_SESSION['user_id'];

// Preparar e executar a consulta SQL
$query = "SELECT * FROM site_user WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Verificar se a senha antiga corresponde à senha armazenada no banco de dados
    $query = "SELECT passwrd FROM site_user WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stored_password_hash = $result->fetch_assoc()['passwrd'];

    if (password_verify($old_password, $stored_password_hash)) {
        // Verificar se a nova senha e a confirmação da senha correspondem
        if ($new_password == $confirm_password) {
            // Criar o hash da nova senha
            $new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);

            // Atualizar os dados do usuário
            $nome = $_POST['nome'];
            $email = $_POST['email'];
            $contacto = $_POST['contacto'];
            $morada = $_POST['morada'];

            $query = "UPDATE site_user SET nome=?, email=?, contacto=?, morada=?, passwrd=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('sssssi', $nome, $email, $contacto, $morada, $new_password_hash, $user_id);
            $stmt->execute();

            // Redirecionar para a página de perfil ou exibir uma mensagem de sucesso
            header("Location: http://ave.dee.isep.ipp.pt/~1201049/Final/index.php");
            exit();
        } else {
            $error_message = "A nova senha e a confirmação da senha não correspondem.";
        }
    } else {
        $error_message = "A senha antiga está incorreta.";
    }
}


// Fechar a conexão
$stmt->close();
$conn->close();


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registo</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
        .main-content {
            display: flex;
            height: 100vh; /* Altura de 100% da viewport */
        }

        .mycontainer {
            display: flex;
            flex: 1;
        }

        .update-div, .image-div {
            flex: 1;
            padding: 20px;
        }

        .update-div {
            background-color: #f4f4f4; /* Cor de fundo para a seção de atualização */
            display: flex;
            flex-direction: column;
            align-items: center; /* Centraliza os itens horizontalmente */
            justify-content: center; /* Centraliza os itens verticalmente */
        }

        .update-div form {
            width: 300px; /* Largura do formulário */
            text-align: center; /* Alinha o texto dos rótulos e botões ao centro */
        }

        .update-div label {
            margin-bottom: 10px; /* Espaçamento entre os rótulos */
            display: block; /* Garante que cada rótulo apareça em uma linha separada */
        }

        .update-div input, .update-div button {
            width: calc(100% - 20px); /* Largura total menos o padding */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 10px; /* Espaçamento entre os campos */
        }

        .update-div button {
            background-color: #04AA6D;
            color: white;
            border: none;
            cursor: pointer;
        }

        .update-div button:hover {
            background-color: #045f3b;
        }
    

        .image-div {
            background-image: url('../../Img/back.jpg'); /* Caminho para a imagem de fundo */
            background-size: cover; /* Ajusta a imagem para cobrir todo o fundo */
            background-position: center; /* Centraliza a imagem */
            background-repeat: no-repeat; /* Evita a repetição da imagem */
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="mycontainer">
        <div class="update-div">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo $user['nome']; ?>" required>

                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label for="contacto">Contacto:</label>
                <input type="text" id="contacto" name="contacto" value="<?php echo $user['contacto']; ?>" required>

                <label for="morada">Morada:</label>
                <input type="text" id="morada" name="morada" value="<?php echo $user['morada']; ?>" required>

                <label for="old_password">Senha Antiga:</label>
                <input type="password" id="old_password" name="old_password" required>

                <label for="new_password">Nova Senha:</label>
                <input type="password" id="new_password" name="new_password" required>

                <label for="confirm_password">Confirme Nova Senha:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>

                <button type="submit">Atualizar</button>
            </form>

            <?php if (isset($error_message)) { ?>
                <p><?php echo $error_message; ?></p>
            <?php } ?>
        </div>
            <div class="image-div">
                <!-- Adicione conteúdo ou mantenha apenas a imagem de fundo -->
            </div>
        </div>
    </div>
</body>
</html>

<?php include '../../includes/footer.php'; ?>
