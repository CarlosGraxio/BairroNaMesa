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
        require_once 'config.php';
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $senha = trim($_POST['senha']);
            
            if (empty($email) || empty($senha)) {
                echo "<p style='color: red;'>Preencha todos os campos.</p>";
            } else {
                $conn = new mysqli("localhost", "root", "", "Bairro_Mesa");
                
                if ($conn->connect_error) {
                    die("Conexão falhou: " . $conn->connect_error);
                }
                
                $sql = "SELECT * FROM utilizadores WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();
                    if (password_verify($senha, $user['senha'])) {
                        echo "<p style='color: green;'>Login bem sucedido!</p>";
                    } else {
                        echo "<p style='color: red;'>Senha incorreta.</p>";
                    }
                } else {
                    echo "<p style='color: red;'>Email não encontrado.</p>";
                }
                
                $stmt->close();
                $conn->close();
            }
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
        <p><a href="javascript:history.back()">Voltar</a></p>
    </div>
</body>
</html>