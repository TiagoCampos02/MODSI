<?php 
session_start();
include '../../includes/header.php'; 
include '../../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $contacto = $_POST['contacto'];
    $dataNascimento = $_POST['dataNascimento'];
    $morada = $_POST['morada'];
    $role_id = $_POST['role']; // ID da role selecionada no dropdown

    $hashed_password = password_hash('password', PASSWORD_DEFAULT);

    $sql = "INSERT INTO site_user (nome, passwrd, dataNascimento, email, contacto, morada, estado, role_id) 
    VALUES ('$nome', '$hashed_password', '$dataNascimento', '$email', '$contacto', '$morada', 1, '$role_id')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Utilizador criado com sucesso!'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/';</script>";
    } else {
        echo "<script>alert('Erro ao registrar utilizador: " . $conn->error . "'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Administrador/CriarFuncionario.php';</script>";
    }

    $conn->close();
}

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
    
        .update-div select {
            width: calc(100% - 20px); /* Largura total menos o padding */
            padding: 10px; /* Espaçamento interno */
            margin-bottom: 20px; /* Espaçamento entre os campos */
        }

        select:focus {
            outline: none; /* Remove a borda de foco */
            border-color: #04AA6D; /* Cor da borda quando o elemento está em foco */
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
            <label style="font-size:32px"> Registar Funcionário</label>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>

                    <!-- <label for="password">Palavra-Passe:</label>
                    <input type="password" id="password" name="password" required> -->

                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="contacto">Contacto:</label>
                    <input type="text" id="contacto" name="contacto" required>

                    <label for="dataNascimento">Data de Nascimento:</label>
                    <input type="date" id="dataNascimento" name="dataNascimento" required>

                    <label for="morada">Morada:</label> 
                    <input type="text" id="morada" name="morada" required>

                    <label for="role">Role:</label> 
                    <select id="role" name="role" required>
                        <option value="">Selecione uma role</option>
                        <?php
                        // Buscar as opções de role no banco de dados
                        $query = "SELECT * FROM role";
                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<option value='" . $row['id'] . "'>" . $row['nome'] . "</option>";
                            }
                        }
                        ?>
                    </select>

                    <button type="submit">Registar</button>

                    <?php if (isset($error_message)) { ?>
                        <p class="error"><?php echo $error_message; ?></p>
                    <?php } ?>
                    <?php if (isset($success_message)) { ?>
                        <p class="success"><?php echo $success_message; ?></p>
                    <?php } ?>
                </form>
        </div>
            <div class="image-div">
                <!-- Adicione conteúdo ou mantenha apenas a imagem de fundo -->
            </div>
        </div>
    </div>
    <!-- Adicione este código JavaScript -->
    <script>
        // Verificar se a mensagem de sucesso ou erro está definida e exibir o popup correspondente
        window.onload = function() {
            <?php if (isset($error_message)) { ?>
                alert("<?php echo $error_message; ?>");
            <?php } ?>
            <?php if (isset($success_message)) { ?>
                alert("<?php echo $success_message; ?>");
            <?php } ?>
        }
    </script>
</body>
</html>

<?php include '../../includes/footer.php'; ?>
