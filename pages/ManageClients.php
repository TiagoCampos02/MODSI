<?php
session_start();
include '../includes/header.php';
include '../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1 && $_SESSION['role_id'] != 2)) {
    // Redirecione para a página de login ou exiba uma mensagem de erro
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

// Variáveis para mensagens de sucesso e erro
$success_message = "";
$error_message = "";

// Verificar se o estado foi atualizado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_estado'])) {
    $user_id = $_POST['user_id'];
    $novo_estado = $_POST['novo_estado'];

    // Atualizar o estado do cliente na base de dados
    $query = "UPDATE site_user SET estado = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $novo_estado, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Estado do cliente atualizado com sucesso.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Erro ao atualizar o estado do cliente: " . $stmt->error . "'); window.location.href = window.location.href;</script>";
    }

    $stmt->close();
}

// Buscar clientes com role_id = 5
$query = "SELECT id, nome, dataNascimento, email, contacto, morada, estado FROM site_user WHERE role_id = 5";
$result = $conn->query($query);
$clientes = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerir Clientes</title>
    <link rel="stylesheet" href="../../css/base.css">
    <style>
        .main-content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
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
        .search-input {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .action-container {
            display: flex;
            gap: 10px;
        }
        .update-button {
            background-color: #04AA6D;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 50%;
        }
        .update-button:hover {
            background-color: #045f3b;
        }
        .estado-dropdown {
            width: 50%;
            padding: 10px;
            border: none;
            background-color: #f4f4f4;
            cursor: pointer;
        }
        .estado-dropdown:focus {
            outline: none;
        }
        .alert {
            padding: 10px;
            margin-bottom: 20px;
            color: white;
        }
        .success {
            background-color: #4CAF50;
        }
        .error {
            background-color: #f44336;
        }
    </style>
    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("clientesTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) {
                tr[i].style.display = "none";
                td = tr[i].getElementsByTagName("td");
                for (j = 0; j < td.length; j++) {
                    if (td[j]) {
                        txtValue = td[j].textContent || td[j].innerText;
                        if (txtValue.toUpperCase().indexOf(filter) > -1) {
                            tr[i].style.display = "";
                            break;
                        }
                    }
                }
            }
        }
    </script>
</head>
<body>
    <div class="main-content">
        <h1>Gerir Clientes</h1>
        <input type="text" id="searchInput" class="search-input" onkeyup="filterTable()" placeholder="Pesquisar...">

        <table id="clientesTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>E-mail</th>
                    <th>Contacto</th>
                    <th>Morada</th>
                    <th>Estado</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['dataNascimento']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['email']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['contacto']); ?></td>
                    <td><?php echo htmlspecialchars($cliente['morada']); ?></td>
                    <td><?php echo $cliente['estado'] == 1 ? 'Ativo' : 'Inativo'; ?></td>
                    <td>
                        <form method="post" style="display: inline;">
                            <input type="hidden" name="user_id" value="<?php echo $cliente['id']; ?>">
                            <div class="action-container">
                                <select name="novo_estado" class="estado-dropdown">
                                    <option value="1" <?php if ($cliente['estado'] == 1) echo 'selected'; ?>>Ativo</option>
                                    <option value="0" <?php if ($cliente['estado'] == 0) echo 'selected'; ?>>Inativo</option>
                                </select>
                                <button type="submit" name="update_estado" class="update-button">Atualizar</button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php include '../includes/footer.php'; ?>
