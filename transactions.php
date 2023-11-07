<!DOCTYPE html>
<html>
<head>
    <title>Gerenciamento Financeiro</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Gerenciamento Financeiro</h1>

    <form action="process.php" method="post">
        <label for="descricao">Descrição:</label>
        <input type="text" name="descricao" required>
        
        <label for="valor">Valor:</label>
        <input type="number" name="valor" step="0.01" required>
        
        <label for="data">Data: </label>
        <input type="date" name="data" value="<?php echo date('Y-m-d'); ?>" required>

        <input type="submit" value="Adicionar Transação">
    </form>

    <?php
    session_start();
    if (isset($_SESSION['username'])) {
        $username = $_SESSION['username'];
        echo "Bem-vindo, $username!";
        echo '<form action="logout.php" method="post">
        <input type="submit" value="Desconectar">
        </form>';
    } else {
        echo "Você não está logado.";
        $username = '';
    }

    $transactions = file('transactions.txt', FILE_IGNORE_NEW_LINES);

    $currentMonth = date('Y-m');

    if (!empty($transactions)) {
        if (isset($_POST['selectedMonth'])) {
            $currentMonth = $_POST['selectedMonth'];
        }

        $prevMonth = date('Y-m', strtotime($currentMonth . ' -1 month'));
        $nextMonth = date('Y-m', strtotime($currentMonth . ' +1 month'));

        echo '<form method="post">
                <label for="selectedMonth">Selecione o mês:</label>
                <input type="month" name="selectedMonth" value="' . $currentMonth . '" required>
                <input type="submit" value="Filtrar">
              </form>';

        echo '<table>
                <tr>
                    <th>Descrição</th>
                    <th>Valor</th>
                    <th>Data</th>
                </tr>';
        
        foreach ($transactions as $transaction) {
            $transactionData = explode('|', $transaction);
            
            if (count($transactionData) === 4) {
                list($transUsername, $descricao, $valor, $data) = $transactionData;

                if ($transUsername === $username && strpos($data, $currentMonth) === 0) {
                    echo '<tr>
                            <td>' . $descricao . '</td>
                            <td>' . $valor . '</td>
                            <td>' . $data . '</td>
                          </tr>';
                }
            } else {
                $errorMsg = "Error: Dados formatados incorretamente no arquivo de transações: $transaction";
                error_log($errorMsg, 3, 'log.txt');
            }
        }
        
        echo '</table>';
        
        echo '<form method="post">
                <input type="hidden" name="selectedMonth" value="' . $prevMonth . '">
                <input type="submit" value="Mês Anterior">
              </form>';

        echo '<form method="post">
                <input type="hidden" name="selectedMonth" value="' . $nextMonth . '">
                <input type="submit" value="Próximo Mês">
              </form>';
    } else {
        echo "Nenhuma transação encontrada.";
    }
    ?>
</body>
</html>
