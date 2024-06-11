<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ginásio</title>
    <link rel="stylesheet" href="../css/style.css"> <!-- Caminho relativo para o arquivo CSS -->
    <style>
        
    </style>
</head>
<body>
    <!-- Conteúdo do arquivo register.php -->
    

    <div class="container">
        <div class="registration-form">
            <h2>Registo de Utilizadores</h2>
            <form action="../controllers/process_register.php" method="post">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="username">Nome de Usuário:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="form-group">
                    <label for="dataNascimento">Data de Nascimento:</label>
                    <input type="date" id="dataNascimento" name="dataNascimento" required>
                </div>
                <div class="form-group">
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="contacto">Contacto:</label>
                    <input type="text" id="contacto" name="contacto">
                </div>
                <div class="form-group">
                    <label for="morada">Morada:</label>
                    <input type="text" id="morada" name="morada">
                </div>
                <button type="submit" class="btnfdd">Registrar</button>
            </form>
        </div>
        <div class="image">
        </div>
    </div>
</body>
</html>

