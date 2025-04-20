<?php
    require_once __DIR__ . '/../../includes/auth.php';
    requireUser();
    require_once __DIR__ . '/../../includes/queues_ops.php';

    $queue_id = (int)($_GET['id'] ?? ($_POST['queue_id'] ?? 0));
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $queue_id > 0) {
        joinQueue($_SESSION['user_id'], $queue_id);
        header('Location: index.php?page=profile');
        exit;
    }
    if ($queue_id <= 0) {
        $_SESSION['error_message'] = "Invalid queue ID.";
        header('Location: index.php?page=search');
        exit;
    }

include __DIR__ . '/../../templates/header.php';
?>

<link rel="stylesheet" href="/assets/queue_join.css">

<div class="container">
    <div class="card">
        <h2>Join Queue</h2>
        <p>Are you sure you want to join this queue?</p>

        <form method="post" action="index.php?page=user/join_queue" class="form-buttons">
            <input type="hidden" name="queue_id" value="<?= $queue_id ?>">
            <button type="submit" class="btn primary">Yes, Join</button>
            <a href="index.php?page=search" class="btn secondary">Cancel</a>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../templates/footer.php'; ?>