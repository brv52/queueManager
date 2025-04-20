<?php 
    session_start();

    $page = $_GET['page'] ?? 'welcome';
    switch ($page) 
    {
        case 'login':
            require __DIR__ . '/../pages/login.php';
            break;
        case 'logout':
            require __DIR__ . '/../pages/logout.php';
            break;
        case 'register':
            require __DIR__ . '/../pages/register.php';
            break;
        case 'profile':
            {
                if ($_SESSION['role'] === 'user')
                    require __DIR__ . '/../pages/user/profile.php';
                else
                    require __DIR__ . '/../pages/company/profile.php';
                break;
            }
        case 'search':
            require __DIR__ . '/../pages/search.php';
            break;
        case 'settings':
            require __DIR__ . '/../pages/settings.php';
            break;
        case 'user/join_queue':
            require __DIR__ . '/../pages/user/join_queue.php';
            break;
        default:
            require __DIR__ . '/../pages/welcome.php';
            break;
    }
?>