<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $mensagem = trim($_POST['mensagem']);
    
    if (empty($nome) || empty($email) || empty($mensagem)) {
        $erro = "Preencha todos os campos.";
    } else {
        // Aqui você pode adicionar lógica para enviar email ou salvar no banco
        // Exemplo simples: apenas exibe uma mensagem
        $sucesso = "Mensagem enviada com sucesso! Obrigado, $nome.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envio - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <main>
        <section>
            <?php 
            if (isset($erro)) echo "<p style='color: red;'>$erro</p>";
            if (isset($sucesso)) echo "<p style='color: green;'>$sucesso</p>";
            ?>
            <p><a href="contacto.php">Voltar</a></p>
        </section>
    </main>
    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>