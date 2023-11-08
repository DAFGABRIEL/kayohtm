<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>devweb</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="register">
    <div class="background"></div>
    <form action="login.php" method="post" class="register">
        <h3>LOGIN</h3>
        <label for="username" class="register">usuário:</label>
        <input type="text" name="username" placeholder="Usuário" id="username" class="register">
        <label for="password" class="register">senha:</label>
        <input type="password" name="password" placeholder="Senha" id="password" class="register">
        <button class="register">entrar</button>
        <div class="register">Não é registrado? <a href="register.php">Crie uma conta.</div>
    </form>
    <?php
if (isset($_GET['error']) && $_GET['error'] == 1) {
    echo '<p class="error-message"> Usuário ou senha incorretos. Tente novamente.</p>';
}
    ?>
</body>
</html>