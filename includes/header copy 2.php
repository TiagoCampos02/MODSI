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
        }

        li {
            float: left;
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
    </style>
</head>
<body>
    <header>
    <ul>
        <li><a class="active" href="http://ave.dee.isep.ipp.pt/~1201049/Final/index.php">Home</a></li>
        <!-- <li><a href="#news">News</a></li>
        <li><a href="#contact">Contact</a></li> -->
        <li><a href="#about">About</a></li>
        <li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/login.php">Login</a></li>
        <li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/mensalidades.php">Register</a></li>
        <li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/fe_registo.php">Registo</a></li>
        <li><a href="http://ave.dee.isep.ipp.pt/~1201049/Final/pages/auth/LogOut.php">Logout</a></li>
    </ul>
        <!-- <nav>
            <div class="wrapper">
                <div class="logo"><a href="#">Logo</a></div>
                <input type="radio" name="slider" id="menu-btn">
                <input type="radio" name="slider" id="close-btn">
                <ul class="nav-links">
                <label for="close-btn" class="btn close-btn"><i class="fas fa-times"></i></label>
                <li><a href="index.php">Início</a></li>
                <li><a href="pages/about.php">About</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="pages/register.php">Register</a></li>
                <li><a href="#">Feedback</a></li>
                </ul>
                <label for="menu-btn" class="btn menu-btn"><i class="fas fa-bars"></i></label>
            </div>
        </nav> -->
    </header>
