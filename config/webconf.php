<?php session_start();

// If logged in
if (isset($_SESSION['user_id'])) {
    if (!isset($_GET['p']) || in_array($_GET['p'], ['login', 'signup'])) {
        $page = 'dashboard';
    } else {
        $page = $_GET['p'];
    }
}
// If not logged in
else {
    if (
        isset($_GET['p']) &&
        in_array($_GET['p'], ['login', 'logout', 'signup'])
    ) {
        $page = $_GET['p'];
    } else {
        $page = 'login';
    }
}

$servertitle = 'ChanChan POS' . ' | ' . ucwords($page);

?>
