<?php
session_start();

// Verificar se o usuário está logado e tem o role_id 4 (Nutricionista)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 4) {
    // Redirecionar para a página de login ou exibir uma mensagem de erro
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

include '../../includes/header.php';
include '../../config/databasee.php';

function fetchClientes($nutricionista_id, $conn) {
    $query = "SELECT su.id, su.nome, su.dataNascimento, su.email, su.contacto, su.morada,
              CASE WHEN pa.id IS NOT NULL THEN 'Sim' ELSE 'Não' END AS possui_plano
              FROM site_user su 
              INNER JOIN mensalidade_user mu ON su.id = mu.user_id 
              LEFT JOIN (
                  SELECT id_user, MAX(id) AS last_plan_id
                  FROM plano_alimentar_user
                  WHERE state = 1
                  GROUP BY id_user
              ) AS last_plan ON su.id = last_plan.id_user
              LEFT JOIN plano_alimentar_user pa ON last_plan.id_user = pa.id_user AND last_plan.last_plan_id = pa.id
              WHERE mu.func_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $nutricionista_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}



$nutricionista_id = $_SESSION['user_id'];
$clientes = fetchClientes($nutricionista_id, $conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Clientes</title>
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
            <h2>Meus Clientes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Data de Nascimento</th>
                        <th>Email</th>
                        <th>Contacto</th>
                        <th>Morada</th>
                        <th>Possui Plano de Nutrição</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?php echo $cliente['nome']; ?></td>
                            <td><?php echo $cliente['dataNascimento']; ?></td>
                            <td><?php echo $cliente['email']; ?></td>
                            <td><?php echo $cliente['contacto']; ?></td>
                            <td><?php echo $cliente['morada']; ?></td>
                            <td>
                                <span style="width: 30%; display: inline-block;"><?php echo $cliente['possui_plano']; ?></span>
                                <?php if ($cliente['possui_plano'] == 'Sim'): ?>
                                    <button onclick="window.location.href='ConsultarPlanoAlimentar.php?userId=<?php echo $cliente['id']; ?>'" style="width: 50%; display: inline-block;">Consultar Plano</button>
                                <?php else: ?>
                                    <button onclick="window.location.href='CriarPlanoAlimentar.php?userId=<?php echo $cliente['id']; ?>'" style="width: 50%; display: inline-block;">Criar Plano de Nutrição</button>
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
            // Implemente a lógica para visualizar o plano de treino do cliente$cliente com o ID userId
            alert('Visualizar plano do cliente $cliente com ID ' + userId);
        }

        function criarPlano(userId) {
            // Implemente a lógica para criar um novo plano de treino para o cliente$cliente com o ID userId
            alert('Criar novo plano de treino para o cliente$cliente com ID ' + userId);
        }
    </script>
</body>
</html>
<?php include '../../includes/footer.php'; ?>
