<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title>Registro</title>
    <link rel="stylesheet" href="css/style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="register">
    <div class="background">
    </div>
    <form action="register.php" method="post" class="register">
        <h3>REGISTER</h3>

        <label for="username" class="register">usuário</label>
        <input type="text" name="username" placeholder="Usuário" id="username" class="register"/>

        <label for="password" class="register">senha</label>
        <input type="password" name="password" placeholder="Senha" id="password" class="register"/>

        <button class="register">registrar</button>
          <div class="register">Já é registrado? <a href="index.php">Voltar</div>
    </form>
</body>
</html>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $users = file('users.txt', FILE_IGNORE_NEW_LINES);
    
    foreach ($users as $user) {
        list($storedUsername, $storedPassword) = explode(':', $user);
        if ($username === $storedUsername) {
            header('Location: register.php?error=1');
            exit;
        }
    }
    
    $newUser = $username . ':' . $password . PHP_EOL;
    
    $userFile = fopen('users.txt', 'a');
    
    fwrite($userFile, $newUser);
    
    fclose($userFile);
    
    header('Location: index.php');
}
?>