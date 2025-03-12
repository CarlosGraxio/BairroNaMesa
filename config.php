<?php
// config.php
$host = "localhost";
$user = "root";
$password = "";
$database = "bairro_mesa";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Definir o nome do site
$site_name = "Bairro Mesa";
?>