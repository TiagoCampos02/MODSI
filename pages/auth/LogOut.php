<?php
session_start();

// Destruir todas as variáveis de sessão
$_SESSION = array();

// Se deseja destruir completamente a sessão, apague também o cookie de sessão.
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destrua a sessão.
session_destroy();
echo "<script>alert('Logout efetuado com sucesso!'); window.location.href = 'http://ave.dee.isep.ipp.pt/~1201049/Final/index.php';</script>";
exit;
?>
