<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>MyQueueApp</title>
    <link rel="stylesheet" href="/assets/nav.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-container">
        <a href="/public/index.php?page=profile" class="nav-logo">MyQueueApp</a>
        <div class="nav-links">
            <?php require_once __DIR__ . '/../includes/auth.php'; ?>
            <?php if (isUser()): ?>
                <a href="/public/index.php?page=search">Search</a>
            <?php endif; ?>
            <a href="/pages/logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="navbar-space"></div>