<?php
require_once 'config.php'; // Inclui o arquivo de configuração da conexão
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Sobre Nós</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="restaurantes.php">Restaurantes</a></li>
                <li><a href="">Sobre</a></li>
                <li><a href="contacto.php">Contacto</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Quem Somos</h2>
            <p>Bem-vindo à nossa empresa! Nós somos dedicados a fornecer os melhores serviços e produtos para nossos clientes. Nossa missão é garantir a satisfação total de cada cliente.</p>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>