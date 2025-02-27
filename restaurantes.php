<?php
require_once 'config.php'; // Inclui o arquivo de configuração da conexão
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
<?php
    // Conectar ao banco de dados
    $conn = new mysqli("localhost", "root", "", "bairro_mesa");

    // Verificar a conexão
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    // Consultar os restaurantes
    $sql = "SELECT id, nome, descricao FROM restaurantes";
    $result = $conn->query($sql);
    ?>

    <main>
        <section id="restaurantes">
            <h2>Conheça os melhores restaurantes</h2>
            <div class="container">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="card">
                            <img src="image/<?= $row['imagem'] ?>" alt="<?= $row['nome'] ?>">
                            <h3><?= $row['nome'] ?></h3>
                            <p><?= $row['descricao'] ?></p>
                            <a href="detalhes.php?id=<?= $row['id'] ?>" class="btn">Ver mais</a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Nenhum restaurante encontrado.</p>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>