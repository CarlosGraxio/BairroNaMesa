<?php
require_once 'config.php';
session_start();

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Método inválido.");
}

// Verificar token CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("Token CSRF inválido.");
}

// Limpar e validar os campos
$nome = trim($_POST['nome']);
$email = trim($_POST['email']);
$mensagem = trim($_POST['mensagem']);

if (empty($nome) || empty($email) || empty($mensagem)) {
    die("Todos os campos são obrigatórios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Email inválido.");
}

// Evitar injeção de cabeçalhos
$nome = str_replace(["\r", "\n"], '', $nome);
$email = str_replace(["\r", "\n"], '', $email);

// Definir email de destino (Mailinator para testes)
$to = "adm@mailinator.com"; // <-- ALTERA AQUI

$subject = "Nova mensagem de contacto - $site_name";

$body  = "Recebeu uma nova mensagem do formulário de contacto:\n\n";
$body .= "Nome: $nome\n";
$body .= "Email: $email\n";
$body .= "Mensagem:\n$mensagem\n\n";
$body .= "Enviado em: " . date("d-m-Y H:i");

// Cabeçalhos
$headers = "From: no-reply@$site_domain\r\n";
$headers .= "Reply-To: $email\r\n";

// Enviar email
if (mail($to, $subject, $body, $headers)) {
    echo "<h2>Mensagem enviada com sucesso!</h2>";
    echo "<p>Iremos responder em breve.</p>";
} else {
    echo "<h2>Erro ao enviar a mensagem.</h2>";
    echo "<p>Tente novamente mais tarde.</p>";
}
?>
