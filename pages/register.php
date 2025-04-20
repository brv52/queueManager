<?php
    require_once __DIR__ . '/../includes/functions.php';

    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $result = registerNew($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role']);

        if ($result === true)
        {
            header('Location: index.php?page=login');
            exit;
        }
        else
            $error_message = $result;
    }
?>

<link rel="stylesheet" href="/assets/register.css">

<div class="container">
    <div class="card">
        <h2>Create an Account</h2>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form method="post" class="form">
            <label>Username:
                <input name="username" required placeholder="Your name">
            </label><br>

            <label>Email:
                <input type="email" name="email" required placeholder="example@mail.com">
            </label><br>

            <label>Password:
                <input type="password" name="password" required placeholder="••••••••">
            </label><br>

            <label>Role:
                <select name="role">
                    <option value="user">User</option>
                    <option value="company">Company</option>
                </select>
            </label><br>

            <div class="buttons">
                <button type="submit" class="btn primary">Register</button>
                <a href="/public/index.php" class="btn secondary">Home</a>
            </div>
        </form>
    </div>
</div>
