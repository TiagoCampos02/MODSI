<?php include '../../includes/header.php'; ?>

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

// Conectar-se à base de dados
$conn = db_connect();

// Consulta SQL para obter as aulas disponíveis
$sql = "SELECT id, nome FROM tipos_aulas";
$result = $conn->query($sql);

// Array para armazenar as aulas disponíveis
$aulas_disponiveis = array();
$aulas_nome = array();

// Verifica se a consulta retornou algum resultado
if ($result->num_rows > 0) {
    // Percorre os resultados e armazena as aulas disponíveis no array
    while ($row = $result->fetch_assoc()) {
        $aulas_disponiveis[$row['id']] = $row;
        $aulas_nome[$row['id']] = $row['nome'];
    }
}

// Consulta SQL para obter o horário existente
$sql_horario = "SELECT * FROM horario_aulas";
$result_horario = $conn->query($sql_horario);

// Array para armazenar o horário existente
$horario_existente = array();

if ($result_horario->num_rows > 0) {
    // Percorre os resultados e armazena o horário existente no array
    while ($row = $result_horario->fetch_assoc()) {
        $horario_existente[$row['hora_id']][$row['dia_semana']] = $row['id_aula'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Horário Semanal</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Consultar Horário Semanal</h2>
    <table>
        <tr>
            <th>Hora</th>
            <th>Domingo</th>
            <th>Segunda-feira</th>
            <th>Terça-feira</th>
            <th>Quarta-feira</th>
            <th>Quinta-feira</th>
            <th>Sexta-feira</th>
            <th>Sábado</th>
        </tr>
        <?php for ($i = 1; $i <= 15; $i++): ?>
            <tr>
                <td><?php echo date('H:i', strtotime('7:00') + ($i - 1) * 3600); ?> - <?php echo date('H:i', strtotime('8:00') + ($i - 1) * 3600); ?></td>
                <?php for ($j = 1; $j <= 7; $j++): ?>
                    <td>
                        <?php 
                        if (isset($horario_existente[$i][$j])) {
                            $aula_id = $horario_existente[$i][$j];
                            echo $aulas_nome[$aula_id];
                        } else {
                            echo "N/A";
                        }
                        ?>
                    </td>
                <?php endfor; ?>
            </tr>
        <?php endfor; ?>
    </table>
</div>
</body>
</html>
<?php include '../../includes/footer.php'; ?>