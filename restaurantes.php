<?php
require_once 'config.php';
if (!isset($pdo) || $pdo === null) {
    die("Erro: Conexão com o banco de dados não estabelecida.");
}

$categoria_id = filter_input(INPUT_GET, 'categoria_id', FILTER_VALIDATE_INT);
$where = $categoria_id ? "WHERE r.categoria_id = ?" : "";
$params = $categoria_id ? [$categoria_id] : [];

$sql = "SELECT r.id, r.nome, r.descricao, c.nome AS categoria FROM restaurantes r LEFT JOIN categorias c ON r.categoria_id = c.id $where";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$restaurantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch categories
$sql = "SELECT id, nome FROM categorias ORDER BY nome";
$stmt = $pdo->query($sql);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes - <?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Restaurantes</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                 <li><a href="perfil.php">Perfil</a></li>
                <li><a href="restaurantes.php">Restaurantes</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="restaurantes">
            <h2>Conheça os melhores restaurantes</h2>
            <form action="" method="get">
                <label for="categoria_id">Filtrar por Categoria</label>
                <select name="categoria_id" id="categoria_id" onchange="this.form.submit()">
                    <option value="">Todas as Categorias</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>" <?= $categoria_id == $categoria['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($categoria['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <div class="container">
                <?php if ($restaurantes): ?>
                    <?php foreach ($restaurantes as $restaurante): ?>
                        <div class="card">
                            <img src="get_imagem.php?id=<?= $restaurante['id'] ?>" alt="<?= htmlspecialchars($restaurante['nome']) ?>">
                            <h3><?= htmlspecialchars($restaurante['nome']) ?></h3>
                            <p><strong>Categoria:</strong> <?= htmlspecialchars($restaurante['categoria']) ?></p>
                            <p><?= htmlspecialchars($restaurante['descricao']) ?></p>
                            <a href="detalhes.php?id=<?= $restaurante['id'] ?>" class="btn">Ver mais</a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Nenhum restaurante encontrado.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>