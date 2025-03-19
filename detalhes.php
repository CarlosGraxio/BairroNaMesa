<?php
require_once 'config.php';
session_start();
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: restaurantes.php");
    exit();
}

$sql = "SELECT * FROM restaurantes WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$restaurante = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$restaurante) {
    header("Location: restaurantes.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($restaurante['nome']) ?> - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1><?= htmlspecialchars($restaurante['nome']) ?></h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="restaurantes.php">Restaurantes</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section>
            <img src="image/<?= $restaurante['imagem'] ?? 'default.jpg' ?>" alt="<?= htmlspecialchars($restaurante['nome']) ?>" style="max-width: 300px;">
            <p><?= htmlspecialchars($restaurante['descricao']) ?></p>
            <p><strong>Localização:</strong> <?= htmlspecialchars($restaurante['localizacao']) ?></p>
            <p><strong>Preço Médio:</strong> <?= htmlspecialchars($restaurante['preco_medio']) ?></p>
        </section>
    </main>

    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>