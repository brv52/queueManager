<?php 
    function isLoggedIn()
    {
        return (isset($_SESSION['user_id']));
    }
    
    function isCompany()
    {
        return ($_SESSION['role'] === 'company');
    }

    function isUser()
    {
        return ($_SESSION['role'] === 'user');
    }

    function requireLogin() 
    {
        if (!isLoggedIn())
        {
            header('Location: /public/index.php?page=login');
            exit;
        }    
    }
    
    function requireCompany()
    {
        requireLogin();
        if (!isCompany())
        {
            header("Location: /public/index.php?page=login");
            exit;
        }
    }
    
    function requireUser()
    {
        requireLogin();
        if (!isUser())
        {
            header("Location: /public/index.php?page=login");
            exit;
        }
    }
?>