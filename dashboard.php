<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$sql = "SELECT is_admin FROM utilizadores WHERE id = ?";
$stmt = $pdo->prepare($sql);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user || !$user['is_admin']) {
    header("Location: index.php");
    exit();
}

// Fetch data
$sql = "SELECT COUNT(*) FROM restaurantes";
$total_restaurantes = $pdo->query($sql)->fetchColumn();
$sql = "SELECT COUNT(*) FROM utilizadores";
$total_utilizadores = $pdo->query($sql)->fetchColumn();
$sql = "SELECT COUNT(*) FROM reservas WHERE status = 'pendente'";
$total_reservas = $pdo->query($sql)->fetchColumn();
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Painel de Administração</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="restaurantes.php">Restaurantes</a></li>
                <li><a href="utilizadores.php">Utilizadores</a></li>
                <li><a href="reservas.php">Reservas</a></li>
                <li><a href="logout.php">Sair</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Resumo</h2>
            <p><strong>Restaurantes:</strong> <?= $total_restaurantes ?></p>
            <p><strong>Utilizadores:</strong> <?= $total_utilizadores ?></p>
            <p><strong>Reservas Pendentes:</strong> <?= $total_reservas ?></p>
        </section>
    </main>
    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>