<?php
require_once 'config/database.php';

class AuthController {
    
    public function registerUser($nome, $hashed_password, $username, $dataNascimento, $email, $contacto, $morada) {
        require_once 'config/database.php';
    
        // Conectar-se ao banco de dados
        $conn = new mysqli($servername, $username_db, $password_db, $dbname);
    
        // Verificar conexão
        if ($conn->connect_error) {
            die("Falha na conexão com o banco de dados: " . $conn->connect_error);
        }
    
        // Preparar e executar a consulta SQL para inserir o usuário no banco de dados
        $sql = "INSERT INTO site_user (nome, passwrd, username, dataNascimento, email, contacto, morada, estado, role_id) 
                VALUES ('$nome', '$hashed_password', '$username', '$dataNascimento', '$email', '$contacto', '$morada', 1, 1)";
    
        if ($conn->query($sql) === TRUE) {
            echo "Usuário registrado com sucesso!";
        } else {
            echo "Erro ao registrar usuário: " . $conn->error;
        }
    
        // Fechar conexão com o banco de dados
        $conn->close();
    }    

    public function loginUser($email, $password) {
        // Coloque aqui a lógica para autenticar o usuário e iniciar a sessão
    }

    public function logoutUser() {
        // Coloque aqui a lógica para encerrar a sessão do usuário
    }

    // Outras funções relacionadas à autenticação podem ser adicionadas aqui

}

?>
