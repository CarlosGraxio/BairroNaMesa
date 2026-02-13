<?php
require_once 'config.php';
session_start();

// Ativar erros PDO para debug (podes remover depois)
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$erro = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $erro = "Preencha todos os campos.";
    } else {
        $sql = "SELECT id, nome, senha FROM utilizadores WHERE email = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar se o utilizador existe e a password está correcta
        if ($user && password_verify($senha, $user['senha'])) {
            // Guardar dados na sessão
            $_SESSION['utilizadores_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];

            header("Location: index.php");
            exit();
        } else {
            $erro = "Email ou senha incorretos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login<?= isset($site_name) ? " - $site_name" : "" ?></title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <?php if (!empty($erro)): ?>
            <p style="color: red;"><?= $erro ?></p>
        <?php endif; ?>

        <form action="" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Insira o seu email" required>

            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Insira a sua senha" required>

            <button type="submit">Entrar</button>
        </form>

        <p>Não tem uma conta? <a href="registar.php">Registar</a></p>
        <p><a href="javascript:history.back()">Voltar</a></p>
    </div>
</body>
</html>
