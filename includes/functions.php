<?php
    function db()
    {
        static $pdo;
        if ($pdo == null)
            require __DIR__ . '/../config/database.php';
        return ($pdo);
    }

    function getAllUsers()
    {
        $pdo = db();
        $stmt = $pdo->query("SELECT * FROM users");
        return ($stmt->fetchAll());
    }

    function getUserById($id)
    {
        $pdo = db();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return ($stmt->fetch());
    }

    function registerNew($username, $email, $password, $role)
    {
        $pdo = db();
        try 
        {
            $stmt = $pdo->prepare("INSERT INTO " . 
                                        ($role === 'company' ? "companies" : "users") . 
                                        " (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);
            
            return (true);
        } 
        catch (PDOException $e)
        {
            if ($e->getCode() === '23000')
                return "Username or email is already taken.";
            error_log($e);
            return "Unexpected error. Please try again later.";
        }
    }

    function LogIn($email, $password, $role)
    {
        $pdo = db();
        try
        {
            $table = ($role === 'company' ? "companies" : "users");
            $stmt = $pdo->prepare("SELECT * FROM $table WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password']))
            {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $role;
                $_SESSION['avatar'] = $user['avatar'];
                return (true);
            }
            return (false);
        }
        catch (Throwable $e)
        {
            error_log($e);
            $_SESSION['error_message'] = 'Could not login.';
            header('Location: /../error/generic.php');
            exit;
        }
    }

    function updateProfileInfo()
    {
        try
        {
            $pdo = db();

            $new_username = trim($_POST['username']);
            $avatar_name = $_SESSION['avatar'] ?? null;
            $user_id = $_SESSION['user_id'];
            $role = $_SESSION['role'];

            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK)
            {
                $tmp = $_FILES['avatar']['tmp_name'];
                $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
                $filename = uniqid('avatar_') . '.' . $ext;
                move_uploaded_file($tmp, __DIR__ . '/../uploads/avatars/' . $filename);
                $avatar_name = $filename;
            }

            $table = ($role === 'company') ? "companies" : "users";
            $stmt = $pdo->prepare("UPDATE $table SET username = ?, avatar = ? WHERE id = ?");
            $stmt->execute([$new_username, $avatar_name, $user_id]);

            $_SESSION['username'] = $new_username;
            $_SESSION['avatar'] = $avatar_name;

            $_SESSION['upd_msg'] = ("Profile updated successfully.");
        }
        catch (Throwable $e)
        {
            if ($e->getCode() === '23000')
            {
                $_SESSION['upd_msg'] = "Username is already taken.";
                return;
            }
            error_log($e);
            $_SESSION['error_message'] = 'Could not update info.';
            header('Location: /../error/generic.php');
            exit;
        }
    }
?>