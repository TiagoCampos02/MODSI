<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ginásio</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        
    </style>
</head>
<body>
    <main>
        <div class="container-form">
            <div class="left">
                <h2>Iniciar Sessão</h2>
                <form action="login_process.php" method="post">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <div class="button-container">
                        <button type="submit" class="btnfdd">Iniciar Sessão</button>
                    </div>
                </form>
            </div>
            <div class="right">
            </div>
        </div>
    </main>
</body>
</html>

<?php include '../includes/footer.php'; ?>

    <!-- <div class="container">
        <div class="registration-form">
            <h2>Registo de Utilizadores</h2>
            <form action="process_register.php" method="post">
                <div class="form-group">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-group">
                    <label for="password">Senha:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm-password">Confirmar Palavra-Passe:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
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
    </div> -->

