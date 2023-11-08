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
    echo '<table>
    <tr>
        <th>Descrição</th>
        <th>Valor</th>
        <th>Data</th>
        <th>Ações</th>
    </tr>';

foreach ($transactions as $key => $transaction) {
$transactionData = explode('|', $transaction);

if (count($transactionData) === 4) {
    list($transUsername, $descricao, $valor, $data) = $transactionData;

    if ($transUsername === $username && strpos($data, $currentMonth) === 0) {
        echo '<tr>
                <td>' . $descricao . '</td>
                <td>' . $valor . '</td>
                <td>' . $data . '</td>
                <td>
                    <form method="post">
                        <input type="hidden" name="editKey" value="' . $key . '">
                        <input type="submit" value="Editar">
                    </form>
                    <form method="post">
                        <input type="hidden" name="deleteKey" value="' . $key . '">
                        <input type="submit" value="Excluir">
                    </form>
                </td>
              </tr>';
    }
} else {
    $errorMsg = "Error: Dados formatados incorretamente no arquivo de transações: $transaction";
    error_log($errorMsg, 3, 'log.txt');
}
}

echo '</table>';

// ...

if (isset($_POST['editKey'])) {
$editKey = $_POST['editKey'];
// Exibir o formulário de edição para a transação com índice $editKey
// O formulário deve ter campos para editar a descrição, valor e data da transação.
// Use o valor de $editKey para obter os dados da transação a ser editada.
// Após a edição, atualize os dados no arquivo transactions.txt.
}

if (isset($_POST['deleteKey'])) {
$deleteKey = $_POST['deleteKey'];
// Remover a transação com índice $deleteKey do arquivo transactions.txt.
// Certifique-se de atualizar o array $transactions também.
}
if (isset($_POST['editKey'])) {
    $editKey = $_POST['editKey'];
    // Exibir o formulário de edição para a transação com índice $editKey
    // O formulário deve ter campos para editar a descrição, valor e data da transação.
    // Use o valor de $editKey para obter os dados da transação a ser editada.
    
    // Obtenha os detalhes da transação selecionada
    $editTransactionData = explode('|', $transactions[$editKey]);
    
    if (count($editTransactionData) === 4) {
        list($transUsername, $descricao, $valor, $data) = $editTransactionData;

        if ($transUsername === $username) {
            echo '<form method="post">
                    <input type="hidden" name="updateKey" value="' . $editKey . '">
                    <label for="editDescricao">Descrição:</label>
                    <input type="text" name="editDescricao" value="' . $descricao . '" required>
                    
                    <label for="editValor">Valor:</label>
                    <input type="number" name="editValor" step="0.01" value="' . $valor . '" required>
                    
                    <label for="editData">Data: </label>
                    <input type="date" name="editData" value="' . $data . '" required>
                    
                    <input type="submit" value="Salvar Edições">
                </form>';
        } else {
            echo "Você não tem permissão para editar esta transação.";
        }
    } else {
        $errorMsg = "Erro: Dados formatados incorretamente na transação selecionada.";
        error_log($errorMsg, 3, 'log.txt');
    }
}
if (isset($_POST['updateKey'])) {
    $updateKey = $_POST['updateKey'];
    $editDescricao = $_POST['editDescricao'];
    $editValor = $_POST['editValor'];
    $editData = $_POST['editData'];

    // Verifique se os campos estão preenchidos corretamente
    if (!empty($editDescricao) && is_numeric($editValor) && !empty($editData)) {
        // Atualize a transação no arquivo
        $transactionData = $username . '|' . $editDescricao . '|' . $editValor . '|' . $editData . PHP_EOL;
        $transactions[$updateKey] = $transactionData;

        // Escreva todas as transações de volta para o arquivo
        file_put_contents('transactions.txt', implode("\n", $transactions));

        // Redirecione de volta para a página de transações
        header('Location: transactions.php');
    } else {
        echo "Por favor, preencha todos os campos corretamente.";
    }
}
    ?>
</body>
</html>
