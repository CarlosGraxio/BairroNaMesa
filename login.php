<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style2.css">
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            // Conexão com o banco de dados
            $conn = new mysqli('localhost', 'root', 'root', 'cinenow');

            // Verificar conexão
            if ($conn->connect_error) {
                die("Conexão falhou: " . $conn->connect_error);
            }

            // Query preparada para evitar SQL Injection
            $sql = "SELECT * FROM utilizadores WHERE email = ? AND senha = ?";
            $stmt = $conn->prepare($sql);
            
            $stmt->bind_param("ss", $email, $senha);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<p style='color: green;'>Login bem sucedido!</p>";
            } else {
                echo "<p style='color: red;'>Email ou senha incorretos.</p>";
            }

            // Fechar conexões
            $stmt->close();
            $conn->close();
        }
        ?>

        <!-- Formulário de Login -->
        <form action="" method="post">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Insira o seu email" required>
            <br><br>
            <label for="senha">Senha</label>
            <input type="password" id="senha" name="senha" placeholder="Insira a sua senha" required>
            <br><br>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="registar.php">Registar</a></p>
    </div>
</body>
</html>