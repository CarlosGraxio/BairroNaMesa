<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['utilizadores_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['utilizadores_id'];

// Handle adding/removing favorites
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $restaurante_id = filter_input(INPUT_POST, 'restaurante_id', FILTER_VALIDATE_INT);
    $action = $_POST['action'] ?? '';

    if ($restaurante_id && in_array($action, ['add', 'remove'])) {
        if ($action === 'add') {
            $sql = "INSERT INTO favoritos (utilizador_id, restaurante_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$user_id, $restaurante_id]);
        } elseif ($action === 'remove') {
            $sql = "DELETE FROM favoritos WHERE utilizadores_id = ? AND restaurante_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$utilizadores_id, $restaurante_id]);
        }
        header("Location: detalhes.php?id=$restaurante_id");
        exit();
    }
}

// Fetch user's favorites
$sql = "SELECT r.id, r.nome, r.descricao FROM favoritos f JOIN restaurantes r ON f.restaurante_id = r.id WHERE f.user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$favoritos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Favoritos - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src=".image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Meus Favoritos</h1>
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
            <h2>Restaurantes Favoritos</h2>
            <div class="container">
                <?php if ($favoritos): ?>
                    <?php foreach ($favoritos as $favorito): ?>
                        <div class="card">
                            <img src="get_imagem.php?id=<?= $favorito['id'] ?>" alt="<?= htmlspecialchars($favorito['nome']) ?>">
                            <h3><?= htmlspecialchars($favorito['nome']) ?></h3>
                            <p><?= htmlspecialchars($favorito['descricao']) ?></p>
                            <a href="detalhes.php?id=<?= $favorito['id'] ?>" class="btn">Ver mais</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Você ainda não adicionou nenhum restaurante aos favoritos.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>