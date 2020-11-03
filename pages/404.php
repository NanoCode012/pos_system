<?php

$redirect = 'index.php';
if (isset($_SERVER['HTTP_REFERER'])) {
    $redirect = $_SERVER['HTTP_REFERER'];
}
echo '<META HTTP-EQUIV="Refresh" Content="0; URL="' . $redirect . '">';

?>
