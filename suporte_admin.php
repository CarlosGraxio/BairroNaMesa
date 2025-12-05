<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['utilizador_id']) || $_SESSION['tipo'] !== 'admin') {
    echo "<script>alert('Acesso negado!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <title>Suporte Administrativo</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body class="support-body">
<div class="add-admin-container">
    <h1>Suporte Técnico</h1>

    <p>Para pedidos de suporte contacte:</p>
    <ul>
        <li><strong>Email:</strong> suporte@mercadobompreco.com</li>
        <li><strong>Telefone:</strong> +351 900 000 000</li>
    </ul>

    <a href="admin_painel.php" class="add-admin-back-link">Voltar</a>
</div>
</body>
</html>
