<?php
session_start();

// Verificar se o usuário está logado e tem o role_id 1 ou 2
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || ($_SESSION['role_id'] != 1)) {
    // Redirecionar para a página de login ou exibir uma mensagem de erro
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

include '../../includes/header.php';
include '../../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

// Variáveis para mensagens de sucesso e erro
$success_message = "";
$error_message = "";

// Verificar se o estado ou role foi atualizado
if ($_SERVER["REQUEST_METHOD"] == "POST" && (isset($_POST['update_estado']) || isset($_POST['update_role']))) {
    $user_id = $_POST['user_id'];
    if (isset($_POST['novo_estado'])) {
        $novo_estado = $_POST['novo_estado'];
        $query = "UPDATE site_user SET estado = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $novo_estado, $user_id);
    } else if (isset($_POST['novo_role'])) {
        $novo_role = $_POST['novo_role'];
        $query = "UPDATE site_user SET role_id = ? WHERE id = ?";
        $stmt->bind_param('ii', $novo_role, $user_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Atualização realizada com sucesso.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Erro ao atualizar: " . $stmt->error . "'); window.location.href = window.location.href;</script>";
    }

    $stmt->close();
}

// Função para buscar funcionários por role_id
function fetchStaffByRole($role_id, $conn) {
    $query = "SELECT id, nome, dataNascimento, email, contacto, morada, estado, role_id FROM site_user WHERE role_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $role_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Buscar roles
$query = "SELECT id, nome FROM role";
$result = $conn->query($query);
$roles = $result->fetch_all(MYSQLI_ASSOC);

// Buscar staff por role_id
$personal_trainers = fetchStaffByRole(3, $conn);
$nutricionistas = fetchStaffByRole(4, $conn);
$funcionarios = fetchStaffByRole(2, $conn);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Staff</title>
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
        .update-button, .role-button {
            background-color: #04AA6D;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            width: 50%;
        }
        .update-button:hover, .role-button:hover {
            background-color: #045f3b;
        }
        .estado-dropdown, .role-dropdown {
            width: 50%;
            padding: 10px;
            border: none;
            background-color: #f4f4f4;
            cursor: pointer;
        }
        .estado-dropdown:focus, .role-dropdown:focus {
            outline: none;
        }
        .no-results {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 20px;
        }
    </style>
    <script>
        function filterTable(inputId, tableId) {
            var input, filter, table, tr, td, i, j, txtValue;
            input = document.getElementById(inputId);
            filter = input.value.toUpperCase();
            table = document.getElementById(tableId);
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

            // Verificar se há resultados visíveis
            var visible = false;
            for (i = 1; i < tr.length; i++) {
                if (tr[i].style.display !== "none") {
                    visible = true;
                    break;
                }
            }
            document.getElementById(tableId + '-no-results').style.display = visible ? 'none' : 'block';
        }
    </script>
</head>
<body>
    <div class="main-content">
        <h1>Gestão de Staff</h1>

        <!-- Personal Trainers -->
        <h2>Personal Trainers</h2>
        <input type="text" id="searchPT" class="search-input" onkeyup="filterTable('searchPT', 'ptTable')" placeholder="Pesquisar...">
        <table id="ptTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>E-mail</th>
                    <th>Contacto</th>
                    <th>Morada</th>
                    <th>Estado</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($personal_trainers) > 0) { ?>
                    <?php foreach ($personal_trainers as $pt) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pt['nome']); ?></td>
                        <td><?php echo htmlspecialchars($pt['dataNascimento']); ?></td>
                        <td><?php echo htmlspecialchars($pt['email']); ?></td>
                        <td><?php echo htmlspecialchars($pt['contacto']); ?></td>
                        <td><?php echo htmlspecialchars($pt['morada']); ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $pt['id']; ?>">
                                <div class="action-container">
                                    <select name="novo_estado" class="estado-dropdown">
                                        <option value="1" <?php if ($pt['estado'] == 1) echo 'selected'; ?>>Ativo</option>
                                        <option value="0" <?php if ($pt['estado'] == 0) echo 'selected'; ?>>Inativo</option>
                                    </select>
                                    <button type="submit" name="update_estado" class="update-button">Atualizar</button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $pt['id']; ?>">
                                <div class="action-container">
                                    <select name="novo_role" class="role-dropdown">
                                        <?php foreach ($roles as $role) { ?>
                                        <option value="<?php echo $role['id']; ?>" <?php if ($pt['role_id'] == $role['id']) echo 'selected'; ?>><?php echo htmlspecialchars($role['nome']); ?></option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" name="update_role" class="role-button">Atualizar</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="no-results" id="ptTable-no-results">Sem resultados</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Nutricionistas -->
        <h2>Nutricionistas</h2>
        <input type="text" id="searchNut" class="search-input" onkeyup="filterTable('searchNut', 'nutTable')" placeholder="Pesquisar...">
        <table id="nutTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>E-mail</th>
                    <th>Contacto</th>
                    <th>Morada</th>
                    <th>Estado</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($nutricionistas) > 0) { ?>
                    <?php foreach ($nutricionistas as $nut) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($nut['nome']); ?></td>
                        <td><?php echo htmlspecialchars($nut['dataNascimento']); ?></td>
                        <td><?php echo htmlspecialchars($nut['email']); ?></td>
                        <td><?php echo htmlspecialchars($nut['contacto']); ?></td>
                        <td><?php echo htmlspecialchars($nut['morada']); ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $nut['id']; ?>">
                                <div class="action-container">
                                    <select name="novo_estado" class="estado-dropdown">
                                        <option value="1" <?php if ($nut['estado'] == 1) echo 'selected'; ?>>Ativo</option>
                                        <option value="0" <?php if ($nut['estado'] == 0) echo 'selected'; ?>>Inativo</option>
                                    </select>
                                    <button type="submit" name="update_estado" class="update-button">Atualizar</button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $nut['id']; ?>">
                                <div class="action-container">
                                    <select name="novo_role" class="role-dropdown">
                                        <?php foreach ($roles as $role) { ?>
                                        <option value="<?php echo $role['id']; ?>" <?php if ($nut['role_id'] == $role['id']) echo 'selected'; ?>><?php echo htmlspecialchars($role['nome']); ?></option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" name="update_role" class="role-button">Atualizar</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="no-results" id="nutTable-no-results">Sem resultados</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Funcionários -->
        <h2>Funcionários</h2>
        <input type="text" id="searchFunc" class="search-input" onkeyup="filterTable('searchFunc', 'funcTable')" placeholder="Pesquisar...">
        <table id="funcTable">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Data de Nascimento</th>
                    <th>E-mail</th>
                    <th>Contacto</th>
                    <th>Morada</th>
                    <th>Estado</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($funcionarios) > 0) { ?>
                    <?php foreach ($funcionarios as $func) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($func['nome']); ?></td>
                        <td><?php echo htmlspecialchars($func['dataNascimento']); ?></td>
                        <td><?php echo htmlspecialchars($func['email']); ?></td>
                        <td><?php echo htmlspecialchars($func['contacto']); ?></td>
                        <td><?php echo htmlspecialchars($func['morada']); ?></td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $func['id']; ?>">
                                <div class="action-container">
                                    <select name="novo_estado" class="estado-dropdown">
                                        <option value="1" <?php if ($func['estado'] == 1) echo 'selected'; ?>>Ativo</option>
                                        <option value="0" <?php if ($func['estado'] == 0) echo 'selected'; ?>>Inativo</option>
                                    </select>
                                    <button type="submit" name="update_estado" class="update-button">Atualizar</button>
                                </div>
                            </form>
                        </td>
                        <td>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="user_id" value="<?php echo $func['id']; ?>">
                                <div class="action-container">
                                    <select name="novo_role" class="role-dropdown">
                                        <?php foreach ($roles as $role) { ?>
                                        <option value="<?php echo $role['id']; ?>" <?php if ($func['role_id'] == $role['id']) echo 'selected'; ?>><?php echo htmlspecialchars($role['nome']); ?></option>
                                        <?php } ?>
                                    </select>
                                    <button type="submit" name="update_role" class="role-button">Atualizar</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="7" class="no-results" id="funcTable-no-results">Sem resultados</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>
<?php include '../../includes/footer.php'; ?>