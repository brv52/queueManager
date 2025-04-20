<?php
    require_once __DIR__ . '/../../includes/auth.php';
    requireUser();

    $user = $_SESSION;

    require_once __DIR__ . '/../../includes/queues_ops.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['queue_id'])) 
    {
        $queue_id = (int) $_POST['queue_id'];
        $user_id = $user['user_id'];

        leaveQueue($user_id, $queue_id);
        $_SESSION['success_message'] = "You left the queue.";
        header("Location: index.php?page=profile");
        exit;
    }

    include __DIR__ . '/../../templates/header.php';
?>

<link rel="stylesheet" href="/assets/user_profile.css">
<link rel="stylesheet" href="/assets/queue_interface.css">

<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <?php
            $avatar = !empty($user['avatar']) ? '/uploads/avatars/' . htmlspecialchars($user['avatar']) : '/assets/user_default.png';
            ?>
            <img src="<?= $avatar ?>" alt="Avatar" class="profile-avatar">
            <div class="profile-info">
                <h2><?= htmlspecialchars($user['username']) ?></h2>
                <a href="index.php?page=settings" class="settings-button">⚙️ Edit Profile</a>
            </div>
        </div>

        <?php if (!empty($_SESSION['success_message'])): ?>
            <p class="message success"><?= htmlspecialchars($_SESSION['success_message']) ?></p>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <div class="queue-section">
            <h3>Your Queues:</h3>
            <?php showUserQueues($user['user_id']); ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../templates/footer.php'; ?>