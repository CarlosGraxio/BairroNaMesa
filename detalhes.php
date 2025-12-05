<?php
require_once 'config.php';
session_start();

// Validar ID
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id || $id <= 0) {
    header("Location: restaurantes.php");
    exit();
}

// Buscar restaurante
$sql = "SELECT r.*, c.nome AS categoria 
        FROM restaurantes r 
        LEFT JOIN categorias c ON r.categoria_id = c.id 
        WHERE r.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$restaurante = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$restaurante) {
    header("Location: restaurantes.php");
    exit();
}

// Buscar avaliações
$sql = "SELECT a.comentario, a.nota, a.data_avaliacao, u.nome 
        FROM avaliacoes a 
        JOIN utilizadores u ON a.user_id = u.id 
        WHERE a.restaurante_id = ? 
        ORDER BY a.data_avaliacao DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$avaliacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verificar favoritos
$is_favorito = false;
if (isset($_SESSION['utilizadores_id'])) {
    $sql = "SELECT id FROM favoritos 
            WHERE utilizadores_id = ? AND restaurante_id = ?";
    $stmt = $pdo->prepare($sql);
    $is_favorito = $stmt->fetch() !== false;
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
        <img src="get_imagem.php?id=<?= $restaurante['id'] ?>" 
             alt="<?= htmlspecialchars($restaurante['nome']) ?>" 
             style="max-width: 300px;">

        <p><strong>Categoria:</strong> <?= htmlspecialchars($restaurante['categoria']) ?></p>
        <p><?= htmlspecialchars($restaurante['descricao']) ?></p>
        <p><strong>Localização:</strong> <?= htmlspecialchars($restaurante['localizacao']) ?></p>
        <p><strong>Preço Médio:</strong> <?= htmlspecialchars($restaurante['preco_medio']) ?></p>

        <!-- Botão de favoritos (agora no sítio certo) -->
        <?php if (isset($_SESSION['utilizadores_id'])): ?>
            <form action="favoritos.php" method="post">
                <input type="hidden" name="restaurante_id" value="<?= $restaurante['id'] ?>">
                <input type="hidden" name="action" value="<?= $is_favorito ? 'remove' : 'add' ?>">
                <button type="submit" class="btn">
                    <?= $is_favorito ? 'Remover dos Favoritos' : 'Adicionar aos Favoritos' ?>
                </button>
            </form>
        <?php endif; ?>

        <p><a href="reservar.php?restaurante_id=<?= $restaurante['id'] ?>" class="btn">Fazer Reserva</a></p>

        <?php if (isset($_SESSION['utilizadores_id'])): ?>
            <p><a href="avaliar.php?restaurante_id=<?= $restaurante['id'] ?>" class="btn">Avaliar Restaurante</a></p>
        <?php endif; ?>
    </section>

    <section>
        <h2>Avaliações</h2>

        <?php if ($avaliacoes): ?>
            <?php foreach ($avaliacoes as $avaliacao): ?>
                <div class="avaliacao">
                    <p><strong><?= htmlspecialchars($avaliacao['nome']) ?></strong> 
                       (Nota: <?= $avaliacao['nota'] ?>/5)</p>
                    <p><?= htmlspecialchars($avaliacao['comentario']) ?></p>
                    <p><small><?= date('d/m/Y H:i', strtotime($avaliacao['data_avaliacao'])) ?></small></p>
                </div>
            <?php endforeach; ?>

        <?php else: ?>
            <p>Ainda não há avaliações para este restaurante.</p>
        <?php endif; ?>
    </section>
</main>

<footer>
    <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
</footer>
</body>
</html>
