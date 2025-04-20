<?php
    if (getenv('APP_ENV') === 'dev')
    {
        ini_set('display_errors', 1);
        error_reporting(E_ALL);
    }
    else
    {
        ini_set('display_errors', 0);
        error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
        ini_set('log_errors', 1);
        ini_set('error_log', __DIR__ . '/../logs/php-error.log');
    }
    
    set_error_handler(function($severity, $message, $file, $line)
    {
        throw new ErrorException($message, 0, $severity, $file, $line);
    });
    
    set_exception_handler(function(Throwable $e)
    {
        error_log($e);
        if (getenv('APP_ENV') === 'development')
        {
            echo "<h1>Uncaught Exception</h1>";
            echo "<pre>" . htmlspecialchars($e) . "</pre>";
        }
        else
        {
            http_response_code(500);
            header('Location: /../error/generic.php');
        }
    });
    
    require_once __DIR__ . '/../includes/router.php';
?>