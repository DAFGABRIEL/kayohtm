<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $users = file('users.txt', FILE_IGNORE_NEW_LINES);
    foreach ($users as $user) {
        list($storedUsername, $storedPassword) = explode(':', $user);
        if ($username === $storedUsername && password_verify($password, $storedPassword)) {
            session_start();
            $_SESSION['username'] = $username; // Store the username in a session
            header('Location: transactions.php');
            exit;
        }
    }
    header('Location: index.php?error=1');
}
?>