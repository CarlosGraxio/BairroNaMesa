<?php
session_start();

require_once 'config.php'; // Inclui o arquivo de configuração da conexão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND password=?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['user'] = $username;
        header("Location: index.php");
        exit();
    } else {
        echo "Usuário ou senha incorretos! <a href='index.php'>Tentar novamente</a>";
    }
    
    $stmt->close();
}
close();
?>
