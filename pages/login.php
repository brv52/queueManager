<?php
    require_once __DIR__ . '/../includes/functions.php';
    require_once __DIR__ . '/../includes/auth.php';

    $error_message = '';

    if (isLoggedIn()) 
    {
        header('Location: /public/index.php?page=profile');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if (logIn($_POST['email'], $_POST['password'], $_POST['role']))
        {
            header('Location: /public/index.php?page=profile');
            exit;
        }
        else
            $error_message = "Login failed. Please check your credentials.";
    }
?>

<link rel="stylesheet" href="/assets/login.css">

<div class="container">
    <div class="card">
        <h2>Login</h2>

        <?php if (!empty($error_message)): ?>
            <p class="error"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form method="post" class="form">
            <label>Email:
                <input type="email" name="email" required placeholder="you@example.com">
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
                <button type="submit" class="btn primary">Login</button>
                <a href="/public/index.php" class="btn secondary">Home</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>