<?php
require_once 'config.php';

// Configurar cookies de sessão antes de session_start()
$secure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

// Gerar token CSRF se não existir
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica token CSRF
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'], $token)) {
        $erro = "Pedido inválido (CSRF).";
    } else {
        // Obter e validar inputs
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if (!$email || empty($senha)) {
            $erro = "Preencha correctamente o email e a senha.";
        } else {
            // Preparar e executar query
            $sql = "SELECT id, nome, senha, role FROM utilizadores WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $utilizadores = $stmt->fetch();

            // Verificar existência e password
            if ($utilizadores && password_verify($senha, $utilizadores['senha'])) {
                // Regenerar id de sessão para prevenir session fixation
                session_regenerate_id(true);

                // Guardar dados essenciais na sessão
                $_SESSION['utilizadores_id'] = $utilizadores['id'];
                $_SESSION['nome'] = $utilizadores['nome'];
                $_SESSION['role'] = $utilizadores['role'];

                // Redirecionar segundo role
                if ($user['role'] === 'admin') {
                    header("Location: admin/index.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                // Mensagem genérica para não revelar se o email existe
                $erro = "Email ou senha incorretos.";
            }
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
    <style>
        /* Assegurar largura 820px e alinhamento à esquerda conforme preferes */
        .login-container { width: 820px; margin: 20px 0; text-align: left; }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <?php if (!empty($erro)): ?>
            <p style="color: red;"><?= htmlspecialchars($erro, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <form action="" method="post" autocomplete="off" novalidate>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') ?>">

            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" placeholder="Insira o seu email" required><br><br>

            <label for="senha">Senha</label><br>
            <input type="password" id="senha" name="senha" placeholder="Insira a sua senha" required><br><br>

            <button type="submit">Entrar</button>
        </form>

        <p>Não tem uma conta? <a href="login.php">login</a></p>
        <p><a href="javascript:history.back()">Voltar</a></p>
    </div>
</body>
</html>
