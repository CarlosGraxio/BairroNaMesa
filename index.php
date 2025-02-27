<?php
require_once 'config.php'; // Inclui o arquivo de configuração da conexão com o banco de dados
?>

<!DOCTYPE html>
<html lang="pt-pt">
<head>
<link rel="stylesheet" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?></title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <header>
        <!-- Adicionando o logo -->
        <div class="logo">
            <img src="image/logo.png" alt="Logo do <?= $site_name ?>" style="height: 50px;">
        </div>

        <!-- Título do site -->
        <h1><?= $site_name ?></h1>

        <!-- Navegação -->
        <nav>
            <ul>
                <li><a href="">Início</a></li>
                <li><a href="restaurantes.php">Restaurantes</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contacto.php">Contacto</a></li>
                <li><a href="login.php" class="login-button">Login</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="Inicio">
            <h2>Bem-vindo ao <?= $site_name ?>!</h2>
            <p>Descubra os melhores restaurantes no seu bairro e explore sabores incríveis.</p>
        </section>
    </main>

    <footer>
        <p>&copy; <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>