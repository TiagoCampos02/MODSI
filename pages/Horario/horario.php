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

// Verifica se a consulta retornou algum resultado
if ($result->num_rows > 0) {
    // Percorre os resultados e armazena as aulas disponíveis no array
    while ($row = $result->fetch_assoc()) {
        $aulas_disponiveis[] = $row;
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

// Processar os dados do formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Loop através das seleções de aulas e atualizar na base de dados
    for ($i = 1; $i <= 15; $i++) {
        for ($j = 1; $j <= 7; $j++) {
            // Verificar se a aula foi selecionada
            $aula_id = $_POST["aula_" . $i . "_" . $j];
            if (!empty($aula_id)) {
                // Atualizar na base de dados
                $dia_semana = $j;
                $sql = "UPDATE horario_aulas SET id_aula = '$aula_id' WHERE dia_semana = '$dia_semana' AND hora_id = '$i'";
                if ($conn->query($sql) !== TRUE) {
                    echo "Erro ao atualizar dados: " . $conn->error;
                }
            }
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preencher Horário Semanal</title>
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

        select {
            width: 100%;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Preencher Horário Semanal</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                            <select name="aula_<?php echo $i; ?>_<?php echo $j; ?>">
                                <option value="">Selecione uma aula</option>
                                <?php foreach ($aulas_disponiveis as $aula): ?>
                                    <?php 
                                    $selected = "";
                                    if (isset($horario_existente[$i][$j]) && $horario_existente[$i][$j] == $aula['id']) {
                                        $selected = "selected";
                                    }
                                    ?>
                                    <option value="<?php echo $aula['id']; ?>" <?php echo $selected; ?>><?php echo $aula['nome']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    <?php endfor; ?>
                </tr>
            <?php endfor; ?>
        </table>
        <button type="submit">Enviar</button>
    </form>
</div>
</body>
</html>
