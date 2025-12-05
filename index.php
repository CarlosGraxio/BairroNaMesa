<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $site_name ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
$page_title = "Início";
require_once 'header.php';
?>
<section class="hero">
    <div class="hero-content">
        <h1>Descubra e reserve os melhores<br><span>restaurantes</span></h1>
        <form class="search-form" action="restaurantes.php" method="get">
            <div class="location">
                <input type="text" name="cidade" value="📍Lisboa" readonly>
            </div>
            <input type="text" name="search" placeholder="Cozinha, nome do restaurante…" required>
            <button type="submit">PESQUISAR</button>
        </form>
        <div class="favoritos-link">
            <a href="favoritos.php">❤️Favoritos</a>
        </div>
    </div>
</section>
<?php require_once 'footer.php'; ?>