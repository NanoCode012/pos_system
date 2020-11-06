<?php
// To sync url with page
if ($_GET['p'] != $page) {
    header('Location: index.php?p=' . $page);
} else {
    include 'pages/' . $page . '.php';
}
?>
