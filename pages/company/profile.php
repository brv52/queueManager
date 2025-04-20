<?php 
    require_once __DIR__ . '/../../includes/auth.php';
    requireCompany();
    require_once __DIR__ . '/../../includes/queues_ops.php';

    $company = $_SESSION;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        if (isset($_POST['queue_id'], $_POST['action'])) 
        {
            setupQueue($company['user_id'], $_POST['queue_id']);
            header("Location: index.php?page=profile");
            exit;
        }
        else
        {
            addQueue($_POST['queue_name'], $_POST['queue_places'], $company['user_id']);
            header("Location: index.php?page=profile");
            exit;
        }
    }

    include __DIR__ . '/../../templates/header.php';
?>

<link rel="stylesheet" href="/assets/company_profile.css">
<link rel="stylesheet" href="/assets/queue_add.css">
<link rel="stylesheet" href="/assets/queue_interface.css">

<div class="container">
    <div class="profile-card">
        <div class="profile-header">
            <?php
                $avatar = !empty($company['avatar']) ? '/uploads/avatars/' . htmlspecialchars($company['avatar']) : '/assets/company_default.png';
            ?>
            <img src="<?= $avatar ?>" alt="Avatar" class="profile-avatar">
            <div class="profile-info">
                <h2><?= htmlspecialchars($company['username']) ?></h2>
                <a href="index.php?page=settings" class="settings-button">⚙️ Edit Profile</a>
            </div>
        </div>

        <p class="description">Here you can manage your queues.</p>

        <a href="#openModal" class="button add-button">Add Queue</a>

        <div id="openModal" class="modal">
            <div class="modal-content">
                <a href="#" class="close">&times;</a>
                <h2>Add New Queue</h2>
                <form method="post" action="index.php?page=profile">
                    <input type="text" name="queue_name" placeholder="Queue Name" required>
                    <input type="number" name="queue_places" placeholder="Places" required>
                    <button type="submit" class="btn primary">Create</button>
                </form>
            </div>
        </div>

        <div class="queue-section">
            <h3>Your Queues:</h3>
            <?php showCompanyQueues($company['user_id']); ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../templates/footer.php'; ?>