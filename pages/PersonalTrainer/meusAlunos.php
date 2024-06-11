<?php
session_start();

// Verificar se o usuário está logado e tem o role_id 3 (Personal Trainer)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    // Redirecionar para a página de login ou exibir uma mensagem de erro
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

include '../../includes/header.php';
include '../../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

function fetchAlunos($personal_trainer_id, $conn) {
    $query = "SELECT su.id, su.nome, su.dataNascimento, su.email, su.contacto, su.morada,
              CASE WHEN pu.id IS NOT NULL AND pu.state = 1 THEN 'Sim' ELSE 'Não' END AS possui_plano
              FROM site_user su 
              INNER JOIN mensalidade_user mu ON su.id = mu.user_id 
              LEFT JOIN plano_user pu ON su.id = pu.id_user AND pu.state = 1
              WHERE mu.func_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $personal_trainer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


$personal_trainer_id = $_SESSION['user_id'];
$alunos = fetchAlunos($personal_trainer_id, $conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Alunos</title>
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
    </style>
</head>
<body>
    <div class="main-content">
        <div class="mycontainer">
            <h2>Meus Alunos</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Email</th>
                        <th>Contacto</th>
                        <th>Morada</th>
                        <th>Possui Plano de Treino</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($alunos as $aluno): ?>
                        <tr>
                            <td><?php echo $aluno['nome']; ?></td>
                            <td><?php echo $aluno['dataNascimento']; ?></td>
                            <td><?php echo $aluno['email']; ?></td>
                            <td><?php echo $aluno['contacto']; ?></td>
                            <td><?php echo $aluno['morada']; ?></td>
                            <td>
                                <span style="width: 30%; display: inline-block;"><?php echo $aluno['possui_plano']; ?></span>
                                <?php if ($aluno['possui_plano'] == 'Sim'): ?>
                                    <button onclick="window.location.href='ConsultarPlanoTreino.php?userId=<?php echo $aluno['id']; ?>'" style="width: 50%; display: inline-block;">Consultar Plano</button>
<!--                                     <button style="width: 50%; display: inline-block;" onclick="visualizarPlano(<?php echo $aluno['id']; ?>)">Visualizar Plano</button>
 -->                                <?php else: ?>
                                    <!-- <a href="CriarPlanoTreino.php?userId=<?php echo $aluno['id']; ?>" style="width: 50%; display: inline-block;">Criar Plano</a> -->
                                    <button onclick="window.location.href='CriarPlanoTreino.php?userId=<?php echo $aluno['id']; ?>'" style="width: 50%; display: inline-block;">Criar Plano</button>
                                <?php endif; ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function visualizarPlano(userId) {
            // Implemente a lógica para visualizar o plano de treino do aluno com o ID userId
            alert('Visualizar plano do aluno com ID ' + userId);
        }

        function criarPlano(userId) {
            // Implemente a lógica para criar um novo plano de treino para o aluno com o ID userId
            alert('Criar novo plano de treino para o aluno com ID ' + userId);
        }
    </script>
</body>
</html>
<?php include '../../includes/footer.php'; ?>
