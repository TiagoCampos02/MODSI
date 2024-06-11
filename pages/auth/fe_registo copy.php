<?php 
session_start();
include '../../includes/header.php'; ?>
<?php
// Função para conectar ao banco de dados
function db_connect() {
    $servername = "ave.dee.isep.ipp.pt";
    $username = "1201049";
    $password = "ZDY3MWU4YWU4ZjQ0";
    $dbname = "1201049_ModsiTeste";

    // Cria a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifica a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    return $conn;
}

// Conecte-se à base de dados
$conn = db_connect();

// Busca as opções de mensalidades e serviços
$sql_mensalidades = "SELECT * FROM mensalidades WHERE id_mensalidade = 1";
$result_mensalidades = $conn->query($sql_mensalidades);

$sql_servicos = "SELECT * FROM mensalidades WHERE id_mensalidade = 2 OR id_mensalidade = 3";
$result_servicos = $conn->query($sql_servicos);

// Verifica se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera os dados do formulário
    $mensalidade = $_POST["plan"];
    $servicosSelecionados = isset($_POST["services"]) ? $_POST["services"] : [];

    // Recuperar os dados do formulário
    $nome = $_POST['nome'];
    $password = $_POST['password'];
    $dataNascimento = $_POST['dataNascimento'];
    $email = $_POST['email'];
    $contacto = $_POST['contacto'];
    $morada = $_POST['morada'];

    // Encriptar a senha
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Inserir os dados no banco de dados
    // Preparar e executar a consulta SQL para inserir o usuário no banco de dados
    $sql = "INSERT INTO site_user (nome, passwrd, dataNascimento, email, contacto, morada, estado, role_id) 
    VALUES ('$nome', '$hashed_password', '$dataNascimento', '$email', '$contacto', '$morada', 1, 5)";

    if ($conn->query($sql) === TRUE) {
        // Obtém o ID do usuário inserido
        $user_id = $conn->insert_id;

        // Insere a mensalidade na tabela de mensalidades
        $sql_mensalidade = "INSERT INTO mensalidade_user (user_id, user_mensalidade_id, func_id) VALUES ('$user_id', '$mensalidade', 0)";
        if ($conn->query($sql_mensalidade) !== TRUE) {
            echo "Erro ao inserir na tabela de mensalidades: " . $conn->error;
        }

        // Insere os serviços selecionados na tabela de mensalidades
        foreach ($servicosSelecionados as $servico) {
            $sql_servico = "INSERT INTO mensalidade_user (user_id, user_mensalidade_id, func_id) VALUES ('$user_id', '$servico', 0)";
            if ($conn->query($sql_servico) !== TRUE) {
                echo "Erro ao inserir na tabela de mensalidades: " . $conn->error;
            }
        }

        // Cria a sessão
        $_SESSION['user_id'] = $user_id;
        $_SESSION['nome'] = $nome;
        $_SESSION['dataNascimento'] = $dataNascimento;
        $_SESSION['email'] = $email;
        $_SESSION['contacto'] = $contacto;
        $_SESSION['morada'] = $morada;
        $_SESSION['role_id'] = 1;
        $_SESSION['estado'] = 1;

        echo "<script>alert('Utilizador criado com sucesso!'); window.location.href = '../index.php';</script>";
    } else {
        echo "<script>alert('Erro ao registrar utilizador: " . $conn->error . "'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/fe_registo.php';</script>";
    }

    // Fecha a conexão com o banco de dados
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registo</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
        div.mycontainer {
            width:100%;
            overflow:auto;
        }
        div.mycontainer .left-div, .registro-utilizadores {
            width: 45%;  
            float: left;
            margin: 2.5%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            height:100%;
        }

        .registro-utilizadores h2 {
            margin-bottom: 5px; /* Adicione um espaçamento entre o título e o formulário, se desejado */
        }

        .registro-utilizadores form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 50%;
        }

        .registro-utilizadores label, input {
            width: 80%;
            text-align: center;
            padding: 5px;
            margin: 5px;
        }

        .registro-utilizadores .button {
            margin-top: 20px;
            margin-bottom: 20px; /* Adicione um espaçamento entre o título e o formulário, se desejado */
            width: 50%; /* Defina a largura do botão */
        }

        .cards-container {
            margin-bottom: 20px;
            width: 80%;
            display: flex;
            flex-direction: column;
            text-align: center;
        }

        .card {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: border-color 0.3s ease;
        }

        .card label {
            width: 80%;
            text-align: center;
            padding: 5px;
        }

        .card input {
            width: auto;
            text-align: center;
            padding: 5px;
        }

        .card:hover {
            border-color: #333;
        }

        .total-price {
            margin-top: 10px;
            font-size: 18px;
        }

        /* Media query for narrow screens */
        @media (max-width: 700px) {
            div.mycontainer .left-div, .registro-utilizadores {
                width: 90%;
                float: none;
                margin: 2.5% auto;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="mycontainer">
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm()">
            <div class="left-div">
                    <!-- Mensalidades -->
                    <div class="cards-container">
                        <h2>Mensalidades</h2>
                        <br>
                        <?php if ($result_mensalidades->num_rows > 0): ?>
                            <?php while($row = $result_mensalidades->fetch_assoc()): ?>
                                <div class="card" data-price="<?php echo htmlspecialchars($row['preco']); ?>">
                                    <input type="radio" name="plan" id="plan_<?php echo htmlspecialchars($row['id_mensalidade']); ?>" value="<?php echo htmlspecialchars($row['id_mensalidade']); ?>" required>
                                    <label for="plan_<?php echo htmlspecialchars($row['id_mensalidade']); ?>">
                                        <strong><?php echo htmlspecialchars($row['titulo']); ?></strong> - <?php echo htmlspecialchars($row['preco']); ?> € <br>
                                        <?php echo htmlspecialchars($row['descricao']); ?>
                                    </label>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>Nenhuma mensalidade disponível</p>
                        <?php endif; ?>
                    </div>

                    <!-- Serviços Adicionais -->
                    <div class="cards-container">
                        <h2>Serviços Adicionais</h2>
                        <?php if ($result_servicos->num_rows > 0): ?>
                            <?php while($row = $result_servicos->fetch_assoc()): ?>
                                <div class="card" data-price="<?php echo htmlspecialchars($row['preco']); ?>">
                                    <input type="checkbox" name="services[]" id="service_<?php echo htmlspecialchars($row['id_mensalidade']); ?>" value="<?php echo htmlspecialchars($row['id_mensalidade']); ?>" onchange="calculateTotal()">
                                    <label for="service_<?php echo htmlspecialchars($row['id_mensalidade']); ?>">
                                        <strong><?php echo htmlspecialchars($row['titulo']); ?></strong> - <?php echo htmlspecialchars($row['preco']); ?> € <br>
                                        <?php echo htmlspecialchars($row['descricao']); ?>
                                    </label>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>Nenhum serviço adicional disponível</p>
                        <?php endif; ?>
                    </div>

                    <!-- Preço Total -->
                    <div class="total-price">
                        <strong>Total:</strong><span id="total-price">0.00</span> €
                    </div>
            </div>
            <div class="registro-utilizadores">
                <!-- Dados Pessoais-->
                <h2>Dados Pessoais</h2>
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nome" required>
                    <label for="password">Palavra-Passe:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="confirm-password">Confirmar Palavra-Passe:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required>
                    <label for="email">E-mail:</label>
                    <input type="email" id="email" name="email" required>
                    <label for="contacto">Contacto:</label>
                    <input type="text" id="contacto" name="contacto">
                    <label for="dataNascimento">Data de Nascimento:</label>
                    <input type="date" id="dataNascimento" name="dataNascimento" required>
                    <label for="morada">Morada:</label>
                    <input type="text" id="morada" name="morada">
                    <button type="submit" class="button">Registar</button>
            </div>
            </form>
        </div>
    </div>
    <script>
        // Função para calcular o preço total
        function calculateTotal() {
            var total = 0;
            // Somar o preço da mensalidade selecionada
            var selectedPlan = document.querySelector('.cards-container .card input[type="radio"]:checked');
            if (selectedPlan) {
                total += parseFloat(selectedPlan.parentNode.getAttribute('data-price'));
            }
            // Somar o preço dos serviços adicionais selecionados
            var checkboxes = document.querySelectorAll('.cards-container .card input[type="checkbox"]:checked');
            checkboxes.forEach(function(checkbox) {
                total += parseFloat(checkbox.parentNode.getAttribute('data-price'));
            });
            // Exibir o preço total
            document.getElementById('total-price').textContent = total.toFixed(2);
        }

        // Função para validar o formulário
        function validateForm() {
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                alert('As palavras-passe não correspondem.');
                return false;
            }
            return true;
        }

        // Adiciona evento de clique para calcular o total quando uma mensalidade é selecionada
        document.querySelectorAll('.cards-container .card input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', calculateTotal);
        });
    </script>
</body>
</html>
<?php include '../../includes/footer.php'; ?>
