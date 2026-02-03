<?php
session_start();
// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit;
}

include '../dbms/dbms_config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $sql = "SELECT id, username, password FROM admin_users WHERE username = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $username);
            
            if ($stmt->execute()) {
                $stmt->store_result();
                
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $db_username, $db_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $db_password)) {
                            // Password is correct, start a new session
                            $_SESSION['admin_logged_in'] = true;
                            $_SESSION['admin_id'] = $id;
                            $_SESSION['admin_username'] = $db_username;
                            
                            header("Location: index.php");
                            exit;
                        } else {
                            $error = "Invalid password.";
                        }
                    }
                } else {
                    $error = "No account found with that username.";
                }
            } else {
                $error = "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In &lsaquo; RA Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f1f1f1;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            color: #444;
        }
        .login-container {
            width: 320px;
            padding: 20px;
            background: #fff;
            border: 1px solid #ccd0d4;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .login-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .login-header h1 {
            color: #555;
            font-weight: 300;
            font-size: 24px;
            margin: 0;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 14px;
        }
        .form-group input {
            width: 100%;
            padding: 10px; /* Comfortable padding */
            border: 1px solid #ddd;
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.07);
            background-color: #fff;
            color: #32373c;
            outline: none;
            box-sizing: border-box; /* Important for padding */
            transition: 0.05s border-color ease-in-out;
        }
        .form-group input:focus {
            border-color: #007cba;
            box-shadow: 0 0 0 1px #007cba;
        }
        .btn-submit {
            background: #0073aa;
            border-color: #0073aa;
            color: #fff;
            text-decoration: none;
            text-shadow: none;
            display: inline-block;
            font-size: 13px;
            line-height: 26px;
            height: 30px;
            margin: 0;
            padding: 0 10px 1px;
            cursor: pointer;
            border-width: 1px;
            border-style: solid;
            -webkit-appearance: none;
            border-radius: 3px;
            white-space: nowrap;
            box-sizing: border-box;
            float: right;
            font-weight: 600;
        }
        .btn-submit:hover {
            background: #006799;
            border-color: #006799;
        }
        .error-message {
            background: #fff;
            border-left: 4px solid #dc3232;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 12px;
            font-size: 13px;
        }
        .back-link {
            text-align: center;
            margin-top: 20px;
        }
        .back-link a {
            color: #555;
            text-decoration: none;
            font-size: 13px;
        }
        .back-link a:hover {
            color: #00a0d2;
        }
    </style>
</head>
<body>

<div style="width: 320px;">
    <div class="login-header">
        <h1>RA Admin</h1>
    </div>

    <?php if(!empty($error)): ?>
        <div class="error-message">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="login-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div style="overflow: hidden;">
                <button type="submit" class="btn-submit">Log In</button>
            </div>
        </form>
    </div>

    <div class="back-link">
        <a href="../index.php">&larr; Go to RA ENTERPRISES</a>
    </div>
</div>

</body>
</html>
