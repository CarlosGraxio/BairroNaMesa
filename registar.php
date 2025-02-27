<?php
require_once 'config.php'; // Inclui o arquivo de configuração da conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="register-container">
        <h1>Registar</h1>

        <?php
        require_once 'config.php';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nome = trim($_POST['nome']);
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha']);
            $confirmar_senha = trim($_POST['confirmar_senha']);
            
            if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
                echo "<p style='color: red;'>Preencha todos os campos.</p>";
            } elseif ($senha !== $confirmar_senha) {
                echo "<p style='color: red;'>As senhas não coincidem.</p>";
            } else {
                $conn = new mysqli("localhost", "root", "", "Bairro_Mesa");
                
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }
                
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO utilizadores (nome, email, senha) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $nome, $email, $senha_hash);
                
                if ($stmt->execute()) {
                    echo "<p style='color: green;'>Registro bem sucedido! <a href='login.php'>Faça login aqui</a>.</p>";
                } else {
                    echo "<p style='color: red;'>Erro ao registrar. Tente novamente.</p>";
                }
                
                $stmt->close();
                $conn->close();
            }
        }
        ?>

        <form action="" method="post">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" placeholder="Insira o seu nome" required>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Insira o seu email" required>
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Insira a sua senha" required>
            <label for="confirmar_senha">Confirmar Senha</label>
            <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Confirme a sua senha" required>
            <button type="submit">Registar</button>
        </form>
        <p>Já tem uma conta? <a href="login.php">Login</a></p>
        <p><a href="javascript:history.back()">Voltar</a></p>
    </div>
</body>
</html>
