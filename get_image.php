<?php
require_once 'config.php';

$restaurante_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$restaurante_id) {
    header('HTTP/1.1 404 Not Found');
    exit();
}

try {
    $sql = "SELECT imagem_path, imagem_tipo FROM restaurantes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$restaurante_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && $row['imagem_path'] && file_exists($row['imagem_path'])) {
        header('Content-Type: ' . $row['imagem_tipo']);
        readfile($row['imagem_path']);
    } else {
        header('Content-Type: image/jpeg');
        readfile('images/default.jpg');
    }
} catch (PDOException $e) {
    header('Content-Type: image/jpeg');
    readfile('image/default.jpg');
}
exit();
?>