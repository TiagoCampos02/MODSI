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

// Função para buscar os detalhes do plano de treino do aluno
function fetchPlanoTreino($userId, $conn) {
    // Consultar o banco de dados para obter os detalhes do plano de treino do aluno
    $query = "SELECT p.id AS plano_id, p.id_user, p.titulo, su_personal_trainer.nome AS nome_personal_trainer, su_cliente.nome AS nome_cliente, su_cliente.email, e.nome AS exercicio, e.grupo_muscular, e.descricao, pl.series, pl.reps 
              FROM plano_user p
              INNER JOIN site_user su_personal_trainer ON p.Escrito_por = su_personal_trainer.id AND p.state = 1
              INNER JOIN site_user su_cliente ON p.id_user = su_cliente.id
              INNER JOIN planos pl ON p.id = pl.plano_id
              INNER JOIN exercicios e ON pl.exercise_id = e.id
              WHERE p.id_user = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Buscar os detalhes do plano de treino do aluno
$planoTreino = fetchPlanoTreino($userId, $conn);

// Fechar a conexão com o banco de dados
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Plano de Treino</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
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

    </style>
</head>
<body>
    <div class="main-content">
        <div class="mycontainer">
            <h2>Plano de Treino: <?php echo $planoTreino[0]['titulo']; ?> </h2>
            <h2>Aluno: <?php echo $planoTreino[0]['nome_cliente']; ?> (<?php echo $planoTreino[0]['email']; ?>) - Escrito por <?php echo $planoTreino[0]['nome_personal_trainer']; ?></h2>
            <table>
                <thead>
                    <tr>
                        <!-- <th>Título</th> -->
                        <th>Exercício</th>
                        <th>Grupo Muscular</th>
                        <th>Descrição</th>
                        <th>Séries</th>
                        <th>Repetições</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planoTreino as $exercicio): ?>
                        <tr>
                            <!-- <td><?php echo $exercicio['titulo']; ?></td> -->
                            <td><?php echo $exercicio['exercicio']; ?></td>
                            <td><?php echo $exercicio['grupo_muscular']; ?></td>
                            <td><?php echo $exercicio['descricao']; ?></td>
                            <td><?php echo $exercicio['series']; ?></td>
                            <td><?php echo $exercicio['reps']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button onclick="window.location.href='DesabilitarPlano.php?userId=<?php echo $planoTreino[0]['id_user']; ?>'" style="height:50px;width: 100%; display: inline-block;">Desabilitar Plano</button>
        </div>
    </div>
</body>
</html>
<?php include '../../includes/footer.php'; ?>
