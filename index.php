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
<section id="Inicio">
    <h2>Bem-vindo ao <?= $site_name ?>!</h2>
    <p>Descubra os melhores restaurantes no seu bairro e explore sabores incríveis.</p>
    <form action="restaurantes.php" method="get">
        <input type="text" name="search" placeholder="Pesquisar restaurantes..." required>
        <button type="submit">Pesquisar</button>
    </form>
    <li><a href="favoritos.php">Favoritos</a></li>
</section>
<?php require_once 'footer.php'; ?>