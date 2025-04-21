<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $erro = "Erro de validação. Tente novamente.";
    } else {
        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
        $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
        $senha = trim($_POST['senha']);
        $confirmar_senha = trim($_POST['confirmar_senha']);
        
        if (empty($nome) || empty($email) || empty($senha) || empty($confirmar_senha)) {
            $erro = "Preencha todos os campos.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erro = "Email inválido.";
        } elseif ($senha !== $confirmar_senha) {
            $erro = "As senhas não coincidem.";
        } elseif (strlen($senha) < 8) {
            $erro = "A senha deve ter pelo menos 8 caracteres.";
        } else {
            $sql = "SELECT id FROM utilizadores WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                $erro = "Email já registrado.";
            } else {
                $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
                $sql = "INSERT INTO utilizadores (nome, email, senha) VALUES (?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                if ($stmt->execute([$nome, $email, $senha_hash])) {
                    $sucesso = "Registro bem sucedido! <a href='login.php'>Faça login aqui</a>.";
                } else {
                    $erro = "Erro ao registrar. Tente novamente.";
                }
            }
        }
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registar - <?= $site_name ?></title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="login-container">
        <h1>Registar</h1>
        <?php 
        if (isset($erro)) echo "<p style='color: red;'>$erro</p>";
        if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>";
        ?>
        <form action="" method="post">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
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