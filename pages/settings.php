<?php
require_once __DIR__ . '/../includes/auth.php';
requireLogin();
require_once __DIR__ . '/../includes/functions.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{
    $msg = updateProfileInfo();
    $_SESSION['upd_msg'] = $msg;
    header("Location: /public/index.php?page=profile");
    exit;
}

include __DIR__ . '/../templates/header.php';
?>

<link rel="stylesheet" href="/assets/settings.css">

<div class="container">
    <div class="card">
        <h2>Edit Profile</h2>

        <?php if (isset($_SESSION['upd_msg'])): ?>
            <p class="message <?= str_contains($_SESSION['upd_msg'], 'success') ? 'success' : 'error' ?>">
                <?= htmlspecialchars($_SESSION['upd_msg']) ?>
            </p>
            <?php unset($_SESSION['upd_msg']); ?>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <label>Username:<br>
                <input type="text" name="username" value="<?= htmlspecialchars($_SESSION['username']) ?>" required>
            </label><br><br>

            <label>Avatar:<br>
                <input type="file" name="avatar" accept="image/*">
            </label><br><br>

            <div class="buttons">
                <button type="submit" class="btn save">💾 Save Changes</button>
                <a href="index.php?page=profile" class="btn cancel">✖ Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>