<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $descricao = $_POST['descricao'];
    $valor = $_POST['valor'];
    $data = $_POST['data'];
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    if (!empty($descricao) && is_numeric($valor) && !empty($data)) {
        $transactionData = $username . '|' . $descricao . '|' . $valor . '|' . $data . PHP_EOL;
        $transactionFile = fopen('transactions.txt', 'a');
        fwrite($transactionFile, $transactionData);
        fclose($transactionFile);
    }
    header('Location: transactions.php');
}
?>