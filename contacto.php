<?php
require_once 'config.php'; // Inclui o arquivo de configuração da conexão
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto</title>
    <link rel="stylesheet" href="style1.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>
        <h1>Contacto</h1>
        <nav>
            <ul>
                <li><a href="index.php">Início</a></li>
                <li><a href="restaurantes.php">Restaurantes</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="">Contacto</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section>
            <h2>Entre em contacto connosco</h2>
            <form action="envio.php" method="post">
                <input type="text" name="nome" placeholder="Nome" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="mensagem" rows="5" placeholder="Mensagem" required></textarea>
                <input type="submit" value="Enviar Mensagem">
            </form>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>