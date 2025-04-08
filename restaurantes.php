<?php
require_once 'config.php';
if (!isset($pdo) || $pdo === null) {
    die("Erro: Conexão com o banco de dados não estabelecida.");
}

// Handle search query
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurantes - <?= $site_name ?></title>
    <link rel="stylesheet" href="style4.css">
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
            <div class="search-container">
                <form method="GET" action="">
                    <input type="text" name="search" placeholder="Pesquisar restaurantes..." 
                           value="<?= htmlspecialchars($search) ?>">
                    <button type="submit">Pesquisar</button>
                </form>
            </div>
            <div class="container">
                <?php
                // Consulta SQL ajustada para lidar com valores nulos na coluna 'nota'
                $sql = "SELECT r.id, r.nome, r.descricao, 
                        AVG(COALESCE(a.nota, 0)) as media_avaliacao, 
                        COUNT(a.id) as total_avaliacoes
                        FROM restaurantes r
                        LEFT JOIN avaliacoes a ON r.id = a.restaurante_id";
                if (!empty($search)) {
                    $sql .= " WHERE r.nome LIKE :search OR r.descricao LIKE :search";
                }
                $sql .= " GROUP BY r.id, r.nome, r.descricao";

                try {
                    $stmt = $pdo->prepare($sql);
                    
                    if (!empty($search)) {
                        $stmt->bindValue(':search', '%' . $search . '%');
                    }
                    
                    $stmt->execute();
                    
                    if ($stmt->rowCount() > 0) {
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="card">';
                            echo '<img src="get_imagem.php?id=' . $row['id'] . '" alt="' . htmlspecialchars($row['nome']) . '">';
                            echo '<h3>' . htmlspecialchars($row['nome']) . '</h3>';
                            echo '<p>' . htmlspecialchars($row['descricao']) . '</p>';
                            
                            // Exibir avaliações
                            echo '<div class="avaliacao">';
                            if ($row['total_avaliacoes'] > 0 && $row['media_avaliacao'] > 0) {
                                $media = round($row['media_avaliacao'], 1);
                                echo '<span class="estrelas">' . str_repeat('★', floor($media)) . str_repeat('☆', 5 - floor($media)) . '</span>';
                                echo '<span class="media"> ' . $media . ' (' . $row['total_avaliacoes'] . ' avaliações)</span>';
                            } else {
                                echo '<span>Sem avaliações ainda</span>';
                            }
                            echo '</div>';
                            
                            echo '<a href="detalhes.php?id=' . $row['id'] . '" class="btn">Ver mais</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Nenhum restaurante encontrado' . (!empty($search) ? ' para a pesquisa "' . htmlspecialchars($search) . '"' : '') . '.</p>';
                    }
                } catch (PDOException $e) {
                    echo '<p>Erro ao buscar restaurantes: ' . htmlspecialchars($e->getMessage()) . '</p>';
                    exit;
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