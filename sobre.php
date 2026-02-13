<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - <?= $site_name ?></title>
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
            <p>Bem-vindo ao Bairro na Mesa, o seu guia definitivo para encontrar os melhores restaurantes ao redor! Se você é um amante da boa gastronomia ou está simplesmente procurando um lugar novo para comer, estamos aqui para ajudar.</p>
            <p>Com uma vasta seleção de restaurantes, desde os mais tradicionais até as opções mais inovadoras, nosso site permite que você explore cardápios, leia avaliações reais de outros clientes, e descubra os pratos mais recomendados por quem já experimentou.</p>
            <p>Com Bairro na Mesa, encontrar o restaurante ideal nunca foi tão fácil. Faça a sua pesquisa agora e descubra onde saborear a próxima grande refeição!</p>
        </section>
    </main>
    <footer>
        <p>© <?= date("Y") ?> <?= $site_name ?>. Todos os direitos reservados.</p>
    </footer>
</body>
</html>