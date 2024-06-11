<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ginásio</title>
    <link rel="stylesheet" href="css/base.css">
    <style>
        ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            overflow: hidden;
            background-color: #f3f3f3;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left,
        .header-center,
        .header-right {
            display: flex;
            align-items: center;
        }

        .header-left li,
        .header-center li,
        .header-right li {
            float: none;
        }

        li a {
            display: block;
            color: #666;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        li a:hover:not(.active) {
            background-color: #ddd;
        }

        li a.active {
            color: white;
            background-color: #04AA6D;
        }

        .logo {
            width: 50px; /* Ajuste o tamanho conforme necessário */
            height: 200%; /* Mantém a proporção do logo */
            margin-right: 10px;
            margin-left: 100px
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body>
    <header>
        <ul>
            <div class="header-left">
                <li><img src="http://ave.dee.isep.ipp.pt/~1201049/Final/Img/LOGO.jpg" alt="Logo" class="logo"></li>
            </div>
            <div class="header-center">
                <li><a class="active" href="http://ave.dee.isep.ipp.pt/~1201049/Final/index.php">Home</a></li>
                <li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/about.php">About</a></li>
            </div>
            <div class="header-right">
                <?php
                session_start();
                if (isset($_SESSION['user_id'])) {
                    // Usuário está logado                    
                    // Verifica o role_id para adicionar links específicos
                    if ($_SESSION['role_id'] == 5) {
                        // Exemplo de links para Clientes
                        echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/admin/dashboard.php">Admin Dashboard</a></li>';
                    } elseif ($_SESSION['role_id'] == 3) {
                        // Exemplo de links para Personal Trainer
                        echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/PersonalTrainer/meusAlunos.php">Meus Alunos</a></li>';
                    } elseif ($_SESSION['role_id'] == 4) {
                        // Exemplo de links para Nutricionista
                        echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Nutricionista/meusClientes.php">Meus Clientes</a></li>';
                    } elseif ($_SESSION['role_id'] == 2) {
                        // Exemplo de links para Funcionários
                        echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/user/profile.php">Profile</a></li>';
                    } elseif ($_SESSION['role_id'] == 1) {
                        // Exemplo de links para Administradores
                        echo '<li class="dropdown">
                                <a href="javascript:void(0)">Admin</a>
                                <div class="dropdown-content">
                                    <a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Funcionario/Associar.php">Associar Staff</a>
                                    <a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Administrador/CriarFuncionario.php">Criar Funcionários</a>
                                    <a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/ManageClients.php">Manage Clients</a>
                                    <a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/Administrador/ManageStaff.php">Manage Staff</a>
                                </div>
                              </li>';
                    }
                    echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/user/profile.php">Profile</a></li>';
                    echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/auth/LogOut.php">Logout</a></li>';
                } else {
                    // Usuário não está logado
                    echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/login.php">Login</a></li>';
                    echo '<li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/auth/fe_registo.php">Register</a></li>';
                }
                ?>
            </div>
        </ul>
    </header>
    </body>
    </html>