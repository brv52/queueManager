<?php
    session_start();
    $msg = $_SESSION['error_message'] ?? 'An unexpected error occurred.';
    unset($_SESSION['error_message']);
?>
<!DOCTYPE html>
<html>
    <head><title>Error</title></head>
    <body>
        <h1>Oops!</h1>
        <p><?= htmlspecialchars($msg) ?></p>
        <p><a href="/../public/index.php?page=welcome">Go back home</a></p>
    </body>
</html>