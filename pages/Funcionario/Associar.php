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

// Conecte-se à base de dados
$conn = db_connect();

// Consultar a tabela mensalidade_user para encontrar os usuários que possuem Nutricionista
$sql_users_with_nutricionista = "SELECT user_id FROM mensalidade_user WHERE user_mensalidade_id = 5 AND func_id = 0";
$result_users_with_nutricionista = $conn->query($sql_users_with_nutricionista);

$users_with_nutricionista = array();

if ($result_users_with_nutricionista->num_rows > 0) {
    // Percorrer os resultados
    while($row = $result_users_with_nutricionista->fetch_assoc()) {
        $user_id = $row['user_id'];

        // Consultar a tabela site_user para obter os detalhes do usuário
        $sql_site_user = "SELECT * FROM site_user WHERE id = $user_id";
        $result_site_user = $conn->query($sql_site_user);

        if ($result_site_user->num_rows > 0) {
            // Usuário encontrado, adicionar ao array de usuários com nutricionista
            $user_row = $result_site_user->fetch_assoc();
            $users_with_nutricionista[] = $user_row;
        }
    }
}

// Consultar a tabela mensalidade_user para encontrar os usuários que possuem Personal Trainer
$sql_users_with_personal_trainer = "SELECT user_id FROM mensalidade_user WHERE user_mensalidade_id = 4 AND func_id = 0";
$result_users_with_personal_trainer = $conn->query($sql_users_with_personal_trainer);

$users_with_personal_trainer = array();

if ($result_users_with_personal_trainer->num_rows > 0) {
    // Percorrer os resultados
    while($row = $result_users_with_personal_trainer->fetch_assoc()) {
        $user_id = $row['user_id'];

        // Consultar a tabela site_user para obter os detalhes do usuário
        $sql_site_user = "SELECT * FROM site_user WHERE id = $user_id";
        $result_site_user = $conn->query($sql_site_user);

        if ($result_site_user->num_rows > 0) {
            // Usuário encontrado, adicionar ao array de usuários com personal trainer
            $user_row = $result_site_user->fetch_assoc();
            $users_with_personal_trainer[] = $user_row;
        }
    }
}


// Consulta para buscar os Nutricionistas da tabela site_user
$sql_nutricionistas = "SELECT * FROM site_user WHERE role_id = 4 AND estado = 1";
$result_nutricionistas = $conn->query($sql_nutricionistas);

$nutricionistas = array();

if ($result_nutricionistas->num_rows > 0) {
    // Percorrer os resultados e armazenar em um array
    while($row = $result_nutricionistas->fetch_assoc()) {
        $nutricionistas[] = $row;
    }
}

// Consulta para buscar os Personal Trainers da tabela site_user
$sql_personal_trainers = "SELECT * FROM site_user WHERE role_id = 3 AND estado = 1";
$result_personal_trainers = $conn->query($sql_personal_trainers);

$personal_trainers = array();

if ($result_personal_trainers->num_rows > 0) {
    // Percorrer os resultados e armazenar em um array
    while($row = $result_personal_trainers->fetch_assoc()) {
        $personal_trainers[] = $row;
    }
}

// Função para atualizar o funcionário na tabela mensalidade_user
function update_funcionario($conn, $user_id, $funcionario_id, $user_mensalidade_id) {
    $sql = "UPDATE mensalidade_user SET func_id = $funcionario_id WHERE user_id = $user_id AND user_mensalidade_id = $user_mensalidade_id";
    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Processar o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['funcionario_id'])) {
    $user_id = $_POST['user_id'];
    $funcionario_id = $_POST['funcionario_id'];
    $user_mensalidade_id = isset($_POST['user_mensalidade_id']) ? $_POST['user_mensalidade_id'] : 0;

    // Verificar se a tabela é para Nutricionista ou Personal Trainer
    if ($user_mensalidade_id == 3) {
        if (update_funcionario($conn, $user_id, $funcionario_id, 5)) {
            echo "<script>alert('Funcionário atualizado com sucesso!');window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Funcionario/Associar.php';</script>";
        } else {
            echo "<script>alert('Erro ao atualizar o funcionário.');</script>";
        }
    } elseif ($user_mensalidade_id == 2) {
        if (update_funcionario($conn, $user_id, $funcionario_id, 4)) {
            echo "<script>alert('Funcionário atualizado com sucesso!'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Funcionario/Associar.php';</script>";

        } else {
            echo "<script>alert('Erro ao atualizar o funcionário.');</script>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Associar Nutricionista e Personal Trainer</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .main-content {
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px auto;
            max-width: 800px;
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f2f2f2;
        }
        form {
            display: flex;
            align-items: center;
        }
        select {
            padding: 6px;
            font-size: 16px;
            margin-right: 10px;
        }
        button {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .no-results {
            margin-top: 20px;
            color: #666;
        }
    </style>
</head>
<body>
<div class="main-content">
    <h2>Utilizadores para associar Nutricionista</h2>

    <?php if (!empty($users_with_nutricionista)): ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Role ID</th>
        <th>Funcionário</th>
      </tr>
      <?php foreach ($users_with_nutricionista as $user): ?>
      <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['nome']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td><?php echo $user['role_id']; ?></td>
        <td>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="user_mensalidade_id" value="3">
                <select name="funcionario_id">
                    <option value="">Selecione um funcionário</option>
                    <?php foreach ($nutricionistas as $nutricionista): ?>
                    <option value="<?php echo $nutricionista['id']; ?>"><?php echo $nutricionista['nome']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Atualizar</button>
            </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php else: ?>
    <p class="no-results">Sem resultados para exibir.</p>
    <?php endif; ?>
        <br><br>
    <h2>Utilizadores para associar Personal Trainer</h2>

    <?php if (!empty($users_with_personal_trainer)): ?>
    <table>
      <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Email</th>
        <th>Role ID</th>
        <th>Funcionário</th>
      </tr>
      <?php foreach ($users_with_personal_trainer as $user): ?>
      <tr>
        <td><?php echo $user['id']; ?></td>
        <td><?php echo $user['nome']; ?></td>
        <td><?php echo $user['email']; ?></td>
        <td><?php echo $user['role_id']; ?></td>
        <td>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="user_mensalidade_id" value="2">
                <select name="funcionario_id">
                    <option value="">Selecione um funcionário</option>
                    <?php foreach ($personal_trainers as $personal_trainer): ?>
                    <option value="<?php echo $personal_trainer['id']; ?>"><?php echo $personal_trainer['nome']; ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Atualizar</button>
            </form>
        </td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php else: ?>
    <p class="no-results">Sem resultados para exibir.</p>
    <?php endif; ?>
</div>
</body>
</html>


<?php include '../../includes/footer.php'; ?>