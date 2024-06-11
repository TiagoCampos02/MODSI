<?php
session_start();

// Verificar se o usuário está logado e tem o role_id 3 (Personal Trainer)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role_id']) || $_SESSION['role_id'] != 3) {
    // Redirecionar para a página de login ou exibir uma mensagem de erro
    echo "<script>alert('Você não tem permissão para acessar esta página.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

$userId = isset($_GET['userId']) ? $_GET['userId'] : null;
if ($userId === null) {
    echo "<script>alert('ID do usuário não encontrado na URL.'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
    exit();
}

include '../../config/databasee.php'; // Incluir o arquivo de configuração do banco de dados

// Função para desabilitar um plano de treino na tabela plano_user
function desabilitarPlanoTreino($userId, $conn) {
    // Consulta SQL para atualizar o campo state para 0
    $query = "UPDATE plano_user SET State = 0 WHERE id_user = ? AND State = 1";
    
    // Preparar e executar a consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    
    // Verificar se a atualização foi bem-sucedida
    if ($stmt->affected_rows > 0) {
        return "Plano de treino desabilitado com sucesso.";
    } else {
        return "Erro ao desabilitar o plano de treino. Por favor, tente novamente.";
    }
}

// Desabilitar o plano de treino
$result = desabilitarPlanoTreino($userId, $conn);

// Verificar o resultado da operação
if ($result === "Plano de treino desabilitado com sucesso.") {
    echo "<script>alert('Plano de treino desabilitado com sucesso!'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/PersonalTrainer/meusAlunos.php';</script>";
} else {
    echo "<script>alert('Erro ao desabilitar o plano de treino: " . $result . "'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/pages/PersonalTrainer/meusAlunos.php';</script>";
}

// Fechar a conexão com o banco de dados
$conn->close();
?>
