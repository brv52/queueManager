<?php
    require_once __DIR__ . "/functions.php";

    function addQueue($name, $places, $company_id)
    {
        $pdo = db();
        try
        {
            $add_quque_stmt = $pdo->prepare("INSERT INTO queues (name, company_id) VALUES (?, ?)");
            $add_quque_stmt->execute([$name, $company_id]);
            $queue_id = $pdo->lastInsertId();
            $fill_places_stmt = $pdo->prepare("INSERT INTO queue_places (queue_id, position) VALUES (?, ?)");
            for ($i = 1; $i <= $places; $i++)
                $fill_places_stmt->execute([$queue_id, $i]);
        }
        catch (Throwable $e)
        {
            error_log($e);
            $_SESSION['error_message'] = 'Could not create queue.';
            header('Location: /../error/generic.php');
            exit;
        }
    }

    function showCompanyQueues($company_id)
    {
        $pdo = db();
        try 
        {
            $stmt = $pdo->prepare("SELECT * FROM queues WHERE company_id = ?");
            $stmt->execute([$company_id]);
            $queues = $stmt->fetchAll();

            if (empty($queues)) 
            {
                echo "<p>No queues created yet.</p>";
                return;
            }

            echo '<div class="queues-list">';
            foreach ($queues as $queue) 
            {
                $queue_id = $queue['id'];
                $queue_name = htmlspecialchars($queue['name']);

                $stats_stmt = $pdo->prepare("SELECT COUNT(*) as total,
                                                SUM(CASE WHEN occupied = 1 THEN 1 ELSE 0 END) as occupied
                                            FROM queue_places
                                            WHERE queue_id = ?");
                $stats_stmt->execute([$queue_id]);
                $data = $stats_stmt->fetch();
                $total = (int)$data['total'];
                $occupied = (int)$data['occupied'];

                echo "<a href='#queue-$queue_id' class='queue-box-link'>
                        <div class='queue-box'>
                            <h3>$queue_name</h3>
                            <div class='queue-bar'>";
                for ($i = 1; $i <= $total; $i++) 
                {
                    $type = $i <= $occupied ? 'filled' : 'free';
                    echo "<span class='place $type'></span>";
                }
                echo "</div>
                    <p>$occupied / $total places occupied</p>
                    </div>
                    </a>";

                echo "<div id='queue-$queue_id' class='modal'>
                        <div class='modal-content'>
                            <a href='#' class='close'>&times;</a>
                            <h3>Manage Queue: $queue_name</h3>
                            <p>Total: $total<br>Occupied: $occupied</p>
                            <form method='post' action='index.php?page=profile'>
                                <input type='hidden' name='action' value='update'>
                                <input type='hidden' name='queue_id' value='$queue_id'>
                                <label>New Total:
                                    <input type='number' name='new_places' min='$occupied' value='$total' required>
                                </label>
                                <button type='submit' class='btn primary'>Update</button>
                            </form>
                            <form method='post' action='index.php?page=profile' style='margin-top: 20px;'>
                                <input type='hidden' name='action' value='delete'>
                                <input type='hidden' name='queue_id' value='$queue_id'>
                                <button type='submit' class='btn danger'>Delete</button>
                            </form>
                        </div>
                    </div>";
            }
            echo '</div>';
        } 
        catch (Throwable $e)
        {
            error_log($e);
            $_SESSION['error_message'] = 'Could not show queues.';
            header('Location: /../error/generic.php');
            exit;
        }
    }

    function showUserQueues($user_id)
    {
        $pdo = db();
        try 
        {
            $stmt = $pdo->prepare("SELECT q.*
                                    FROM queues AS q
                                    JOIN user_queue AS uq ON q.id = uq.queue_id
                                    WHERE uq.user_id = ?");
            $stmt->execute([$user_id]);
            $queues = $stmt->fetchAll();

            if (empty($queues))
            {
                echo "<p>No queues joined yet.</p>";
                echo "<a href='index.php?page=search' class='btn secondary'>Search for Queues</a>";
                return;
            }

            echo '<div class="queues-list">';
            foreach ($queues as $queue) 
            {
                $queue_id = $queue['id'];
                $queue_name = htmlspecialchars($queue['name']);

                $stats_stmt = $pdo->prepare("SELECT COUNT(*) as total,
                                                SUM(CASE WHEN occupied = 1 THEN 1 ELSE 0 END) as occupied
                                            FROM queue_places
                                            WHERE queue_id = ?");
                $stats_stmt->execute([$queue_id]);
                $data = $stats_stmt->fetch();
                $total = (int)$data['total'];
                $occupied = (int)$data['occupied'];

                $position_stmt = $pdo->prepare("SELECT position
                                                FROM queue_places
                                                WHERE queue_id = ? AND user_id = ?");
                $position_stmt->execute([$queue_id, $user_id]);
                $user_position = $position_stmt->fetchColumn();

                echo "<a href='#queue-$queue_id' class='queue-box-link'>
                        <div class='queue-box'>
                            <h3>$queue_name</h3>
                            <div class='queue-bar'>";
                for ($i = 1; $i <= $total; $i++) 
                {
                    if ($i == $user_position) $type = "user";
                    else if ($i <= $occupied) $type = "filled";
                    else $type = "free";
                    echo "<span class='place $type'></span>";
                }
                echo "</div>
                    <p>$occupied / $total places occupied</p>";
                if ($user_position !== false)
                    echo "<p>Your position: $user_position</p>";
                else
                    echo "<p>You are not in this queue</p>";
                echo "</div>
                    </a>";

                echo "<div id='queue-$queue_id' class='modal'>
                        <div class='modal-content'>
                            <a href='#' class='close'>&times;</a>
                            <h3>$queue_name</h3>
                            <p>Your position: $user_position</p>
                            <form method='post' action='index.php?page=profile'>
                                <input type='hidden' name='queue_id' value='$queue_id'>
                                <button type='submit' class='btn danger'>Leave Queue</button>
                            </form>
                        </div>
                    </div>";
            }
            echo '</div>';
        } 
        catch (Throwable $e)
        {
            error_log($e);
            $_SESSION['error_message'] = 'Could not show queues.';
            header('Location: /../error/generic.php');
            exit;
        }
    }


    function searchQueues(string $query)
    {
        $pdo = db();
        $stmt = $pdo->prepare("SELECT q.id, q.name, c.username AS company
                                    FROM queues q
                                    JOIN companies c ON q.company_id = c.id
                                    WHERE q.name LIKE ?
                                    ORDER BY q.name ASC
                                    LIMIT 10");
        $stmt->execute([$query . '%']);
        return ($stmt->fetchAll());
    }

    function joinQueue($user_id, $queue_id)
    {
        $pdo = db();
        try
        {
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM queue_places WHERE queue_id = ? AND user_id = ?");
            $check_stmt->execute([$queue_id, $user_id]);
            if ($check_stmt->fetchColumn() > 0)
            {
                $_SESSION['info_message'] = "You are already in this queue.";
                return;
            }

            $spot_stmt = $pdo->prepare("SELECT id, position FROM queue_places 
                                            WHERE queue_id = ? AND occupied = 0 
                                            ORDER BY position ASC 
                                            LIMIT 1");
            $spot_stmt->execute([$queue_id]);
            $spot = $spot_stmt->fetch();
            if (!$spot) 
            {
                $_SESSION['error_message'] = "No available spots in this queue.";
                return;
            }

            $update_stmt = $pdo->prepare("UPDATE queue_places 
                                            SET occupied = 1, user_id = ?
                                            WHERE id = ?");
            $update_stmt->execute([$user_id, $spot['id']]);
            
            $log_stmt = $pdo->prepare("INSERT IGNORE INTO user_queue (user_id, queue_id) VALUES (?, ?)");
            $log_stmt->execute([$user_id, $queue_id]);
            $_SESSION['success_message'] = "You joined the queue successfully!";
        }
        catch (Throwable $e)
        {
            error_log($e);
            $_SESSION['error_message'] = 'Could not join queue.';
            header('Location: /../error/generic.php');
            exit;
        }
    }

    function leaveQueue($user_id, $queue_id)
    {
        try
        {
            $pdo = db();
            
            $stmt = $pdo->prepare("UPDATE queue_places SET occupied = 0, user_id = NULL WHERE queue_id = ? AND user_id = ?");
            $stmt->execute([$queue_id, $user_id]);
            $pdo->prepare("DELETE FROM user_queue WHERE user_id = ? AND queue_id = ?")->execute([$user_id, $queue_id]);
        }
        catch (Throwable $e)
        {
            error_log($e);
            $_SESSION['error_message'] = 'Could not join queue.';
            header('Location: /../error/generic.php');
            exit;
        }
    }

    function setupQueue($company_id, $queue_id)
    {
        $pdo = db();

        $check = $pdo->prepare("SELECT * FROM queues WHERE id = ? AND company_id = ?");
        $check->execute([$queue_id, $company_id]);
        $queue = $check->fetch();

        if (!$queue)
        {
            $_SESSION['error_message'] = "Queue not found.";
            header("Location: index.php?page=profile");
            exit;
        }

        $places_stmt = $pdo->prepare("SELECT COUNT(*) FROM queue_places WHERE queue_id = ?");
        $places_stmt->execute([$queue_id]);
        $current_places = (int)$places_stmt->fetchColumn();

        if ($_POST['action'] === 'update' && isset($_POST['new_places']))
        {
            $new_places = (int)$_POST['new_places'];

            $occupied_stmt = $pdo->prepare("SELECT COUNT(*) FROM queue_places WHERE queue_id = ? AND occupied = 1");
            $occupied_stmt->execute([$queue_id]);
            $occupied = (int)$occupied_stmt->fetchColumn();
            if ($new_places < $occupied)
                $_SESSION['error_message'] = "Cannot reduce below number of occupied places ($occupied).";
            else
            {
                if ($new_places < $current_places) 
                    $pdo->prepare("
                        DELETE FROM queue_places
                        WHERE queue_id = ? AND occupied = 0 AND position > ?
                    ")->execute([$queue_id, $new_places]);
        
                if ($new_places > $current_places) 
                {
                    $insert_stmt = $pdo->prepare("INSERT INTO queue_places (queue_id, position, occupied) VALUES (?, ?, 0)");
                    for ($i = $current_places + 1; $i <= $new_places; $i++)
                        $insert_stmt->execute([$queue_id, $i]);
                }

                $_SESSION['success_message'] = "Queue updated successfully.";
            }
        }
        else if ($_POST['action'] === 'delete')
        {
            $pdo->prepare("DELETE FROM queue_places WHERE queue_id = ?")->execute([$queue_id]);
            $pdo->prepare("DELETE FROM user_queue WHERE queue_id = ?")->execute([$queue_id]);
            $pdo->prepare("DELETE FROM queues WHERE id = ?")->execute([$queue_id]);
    
            $_SESSION['success_message'] = "Queue deleted.";
        }
    }
?>