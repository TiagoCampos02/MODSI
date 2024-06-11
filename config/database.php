<?php
function db_connect() {
    $servername = "ave.dee.isep.ipp.pt";
    $username = "1201049";
    $password = "ZDY3MWU4YWU4ZjQ0";
    $dbname = "1201049_ModsiTeste";

    // Crie a conexão
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verifique a conexão
    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }

    return $conn;
}
?>
