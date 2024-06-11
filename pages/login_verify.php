<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirecionar para a página de login se o usuário não estiver autenticado
    header("Location: login.php");
    exit();
}

// Restante do código da página restrita aqui
?>