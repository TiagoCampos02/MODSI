<?php
$servername = "ave.dee.isep.ipp.pt";
$username = "1201049";
$password = "ZDY3MWU4YWU4ZjQ0";
$dbname = "1201049_ModsiTeste";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>