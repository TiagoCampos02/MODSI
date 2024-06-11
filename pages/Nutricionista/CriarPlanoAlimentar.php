<?php
session_start();

// Verificar se o usuário está logado e tem o role_id 4
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 4) {
    // Redirecionar para a página de login ou exibir uma mensagem de erro
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

$userId = isset($_GET['userId']) ? $_GET['userId'] : null;
if ($userId === null) {
    echo "<script>alert('ID do usuário não encontrado na URL.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

include '../../includes/header.php';
include '../../config/databasee.php';

// Consulta ao banco de dados para buscar o nome e o email do usuário
$sql = "SELECT nome, email FROM site_user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $userName = $user['nome'];
    $userEmail = $user['email'];
} else {
    echo "<script>alert('Usuário não encontrado.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sunday = $_POST['sunday'];
    $monday = $_POST['monday'];
    $tuesday = $_POST['tuesday'];
    $wednesday = $_POST['wednesday'];
    $thursday = $_POST['thursday'];
    $friday = $_POST['friday'];
    $saturday = $_POST['saturday'];
    $written_by = $_SESSION['user_id'];
    $state = 1;

    // Prepara e executa a consulta SQL para inserir os dados do plano de alimentação
    $sql = "INSERT INTO plano_alimentar_user (id_user, escrito_por, Domingo, Segunda, Terca, Quarta, Quinta, Sexta, Sabado, state)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql);
    $stmt_insert->bind_param("iisssssssi", $userId, $written_by, $sunday, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $state);

    if ($stmt_insert->execute()) {
        echo "<script>alert('Plano de Nutrição criado com sucesso!');window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Nutricionista/meusClientes.php';</script>";
    } else {
        echo "<script>alert('Erro ao criar plano de nutrição. Por favor, tente novamente.');</script>";
    }

    $stmt_insert->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plano de Alimentação</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
        .plano-container {
            padding: 2em;
            max-width: 80%;
            margin: 0 auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1em;
        }

        label {
            display: block;
            margin-bottom: 0.5em;
        }

        input, textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 0.75em 1.5em;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="plano-container">
            <form action="" method="POST" id="plan-form">
                <div class="form-group">
                    <label for="name">Nome:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userName); ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userEmail); ?>" readonly required>
                </div>
                <div class="form-group">
                    <label for="sunday">Domingo:</label>
                    <textarea id="sunday" name="sunday" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="monday">Segunda-feira:</label>
                    <textarea id="monday" name="monday" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="tuesday">Terça-feira:</label>
                    <textarea id="tuesday" name="tuesday" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="wednesday">Quarta-feira:</label>
                    <textarea id="wednesday" name="wednesday" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="thursday">Quinta-feira:</label>
                    <textarea id="thursday" name="thursday" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="friday">Sexta-feira:</label>
                    <textarea id="friday" name="friday" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="saturday">Sábado:</label>
                    <textarea id="saturday" name="saturday" rows="3" required></textarea>
                </div>
                <button type="submit">Criar Plano</button>
            </form>
        </div>
    </div>
</body>
</html>
<?php include '../../includes/footer.php'; ?>
