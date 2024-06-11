<?php
session_start();

// Verificar se o usuário está logado e tem o role_id 3 (Personal Trainer)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
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
include '../../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

function fetchExercicios($conn) {
    $query = "SELECT * FROM exercicios";
    $result = $conn->query($query);
    $exercicios = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $exercicios[] = $row;
        }
    }
    return $exercicios;
}

$exercicios = fetchExercicios($conn);


// Processar o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter os dados do formulário
    $titulo = $_POST['titulo'];
    $exercicios = $_POST['exercicio'];
    $series = $_POST['series'];
    $reps = $_POST['reps'];

    

    // Inserir o plano de treino na tabela plano_user
    $query_plano_user = "INSERT INTO plano_user (id_user, titulo, escrito_por, state) VALUES (?, ?, ?, ?)";
    $stmt_plano_user = $conn->prepare($query_plano_user);

    // Definir o estado padrão como 1 (ativo)
    $state = 1;

    $stmt_plano_user->bind_param('isss', $userId, $titulo, $_SESSION['user_id'], $state);
    $stmt_plano_user->execute();


    // Obter o ID do plano inserido
    $plano_id = $stmt_plano_user->insert_id;

    // Inserir os exercícios do plano na tabela planos
    $query_planos = "INSERT INTO planos (plano_id, exercise_id, series, reps) VALUES (?, ?, ?, ?)";
    $stmt_planos = $conn->prepare($query_planos);

    // Iterar sobre os exercícios e inseri-los na tabela planos
    for ($i = 0; $i < count($exercicios); $i++) {
        $stmt_planos->bind_param('iiii', $plano_id, $exercicios[$i], $series[$i], $reps[$i]);
        $stmt_planos->execute();
    }

    // Verificar se a inserção foi bem-sucedida
    if ($stmt_plano_user->affected_rows > 0 && $stmt_planos->affected_rows > 0) {
        echo "<script>alert('Plano de treino criado com sucesso!');window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/PersonalTrainer/meusAlunos.php';</script>";
    } else {
        echo "<script>alert('Erro ao criar plano de treino. Por favor, tente novamente.');</script>";
    }

    // Fechar as declarações e a conexão
    $stmt_plano_user->close();
    $stmt_planos->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Plano de Treino</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
        .main-content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-results {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 20px;
        }
        #add-exercicio {
            margin-top: 10px;
        }
        select, input[type="number"] {
            width: 100%; /* Ocupa 100% da largura da célula */
            text-align: center;
            height: 40px;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <div class="mycontainer">
            <h2>Criar Plano de Treino</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?userId=' . $userId; ?>" method="post">
                <label for="titulo">Título do Plano:</label>
                <input type="text" id="titulo" name="titulo" required>

                <table id="exercicios-table">
                    <thead>
                        <tr>
                            <th>Exercício</th>
                            <th>Séries</th>
                            <th>Repetições</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="exercicio[]" required>
                                    <option value="">Selecione um exercício</option>
                                    <?php foreach ($exercicios as $exercicio): ?>
                                        <option value="<?php echo $exercicio['id']; ?>"><?php echo $exercicio['nome']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input type="number" name="series[]" min="1" required></td>
                            <td><input type="number" name="reps[]" min="1" required></td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" id="add-exercicio">Adicionar Exercício</button>
                <button type="submit">Criar Plano</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('add-exercicio').addEventListener('click', function() {
            var table = document.getElementById('exercicios-table').getElementsByTagName('tbody')[0];
            var newRow = table.insertRow(table.rows.length);
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            var cell3 = newRow.insertCell(2);
            cell1.innerHTML = "<select name='exercicio[]' required><option value=''>Selecione um exercício</option><?php foreach ($exercicios as $exercicio): ?><option value='<?php echo $exercicio['id']; ?>'><?php echo $exercicio['nome']; ?></option><?php endforeach; ?></select>";
            cell2.innerHTML = "<input type='number' name='series[]' min='1' required>";
            cell3.innerHTML = "<input type='number' name='reps[]' min='1' required>";
        });
    </script>
</body>
</html>
<?php include '../../includes/footer.php'; ?>

