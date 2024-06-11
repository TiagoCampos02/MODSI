<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 5) {
    // Redirecionar para a página de login ou exibir uma mensagem de erro
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];


include '../../includes/header.php';
include '../../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

// Função para buscar os detalhes do plano de treino do aluno
function fetchPlanoAlimentar($userId, $conn) {
    // Consultar o banco de dados para obter os detalhes do plano de treino do aluno
    $query = "SELECT pau.id_user, pau.domingo, pau.segunda, pau.terca, pau.quarta, pau.quinta, pau.sexta, pau.sabado, su.nome AS nome_cliente, su.email AS email_cliente, nutri.nome AS nome_nutricionista
            FROM plano_alimentar_user pau
            INNER JOIN site_user su ON pau.id_user = su.id
            LEFT JOIN site_user nutri ON pau.escrito_por = nutri.id
            WHERE pau.id_user = ? AND pau.state = 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}


// Buscar os detalhes do plano de treino do aluno
$planoAlimentar = fetchPlanoAlimentar($user_id, $conn);

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
            <?php if (!empty($planoAlimentar)): ?>
            <h2>Plano de Nutrição:</h2>
            <h2><?php echo $planoAlimentar[0]['nome_cliente']; ?> (<?php echo $planoAlimentar[0]['email_cliente']; ?>) - Escrito por <?php echo $planoAlimentar[0]['nome_nutricionista']; ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Domingo</th>
                        <th>Segunda</th>
                        <th>Terça</th>
                        <th>Quarta</th>
                        <th>Quinta</th>
                        <th>Sexta</th>
                        <th>Sábado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($planoAlimentar as $exercicio): ?>
                        <tr>
                            <td><?php echo $exercicio['domingo']; ?></td>
                            <td><?php echo $exercicio['segunda']; ?></td>
                            <td><?php echo $exercicio['terca']; ?></td>
                            <td><?php echo $exercicio['quarta']; ?></td>
                            <td><?php echo $exercicio['quinta']; ?></td>
                            <td><?php echo $exercicio['sexta']; ?></td>
                            <td><?php echo $exercicio['sabado']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p>Sem Dados</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
<?php include '../../includes/footer.php'; ?>
