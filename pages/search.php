<link rel="stylesheet" href="/assets/search.css">
<?php
    require_once __DIR__ . '/../includes/auth.php';
    requireUser();
    require_once __DIR__ . '/../includes/queues_ops.php';

    $matches = [];
    if (isset($_GET['q']) && trim($_GET['q']) !== '')
        $matches = searchQueues(trim($_GET['q']));

    include __DIR__ . '/../templates/header.php';
?>

<div class="container">
    <div class="card">
        <h2>🔍 Search for a Queue</h2>
        <form method="get" action="index.php" autocomplete="off" class="search-form">
            <input type="hidden" name="page" value="search">
            <input type="text" name="q" placeholder="Start typing queue name..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" class="search-input">
            <button type="submit" class="btn">Search</button>
        </form>

        <?php if (!empty($matches)): ?>
            <ul class="search-results">
                <?php foreach ($matches as $queue): ?>
                    <li>
                        <a href="index.php?page=user/join_queue&id=<?= $queue['id'] ?>">
                            <strong><?= htmlspecialchars($queue['name']) ?></strong>
                            <small>(<?= htmlspecialchars($queue['company']) ?>)</small>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php elseif (isset($_GET['q'])): ?>
            <p class="no-results">No matches found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../templates/footer.php'; ?>