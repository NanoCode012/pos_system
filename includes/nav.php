<?php
if (isset($_SESSION['user_id'])) {
    if (! in_array($page, ['logout', '404'])) {
        //Don't show navbar when logged out
        include 'includes/nav-side.php';
    }
}
?>
