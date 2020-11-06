<?php

$error = false;

try {
    $db->insert('transactions', [
        'stock_id' => '1',
        'quantity' => '2',
    ]);
} catch (PDOException $exception) {
    $error = $exception->getMessage();
    $error = ($pos = strpos($error, '1644 '))
        ? substr($error, $pos + 5)
        : $error;
}
?>

<?php if ($error) {
    echo '<div class="main-content" id="panel">
        <div class="alert alert-danger" role="alert">
            ' .
        $error .
        '
        </div>
    </div>';
}
