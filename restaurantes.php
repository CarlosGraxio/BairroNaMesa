<?php
require_once 'config.php';
if (!isset($pdo) || $pdo === null) {
    die("Erro: Conexão com o banco de dados não estabelecida.");
}
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
                <li><a href="">Restaurantes</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="restaurantes">
            <h2>Conheça os melhores restaurantes</h2>
            <div class="container">
                <?php
                $sql = "SELECT id, nome, descricao, imagem FROM restaurantes";
                $stmt = $pdo->query($sql);
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="card">';
                        echo '<img src="image/' . ($row['imagem'] ?? 'default.jpg') . '" alt="' . htmlspecialchars($row['nome']) . '">';
                        echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
                        echo '<p>' . htmlspecialchars($row['descricao']) . '</p>';
                        echo '<a href="detalhes.php?id=' . $row['id'] . '" class="btn">Ver mais</a>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Nenhum restaurante encontrado.</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>