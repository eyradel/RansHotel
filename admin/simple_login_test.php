<?php
// Simple admin login test - minimal version
session_start();

$error_message = '';
$success_message = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');
    
    $username = trim($_POST['user'] ?? '');
    $password = trim($_POST['pass'] ?? '');
    
    if(empty($username) || empty($password)) {
        $error_message = "Please enter both username and password.";
    } else {
        // Simple query
        $sql = "SELECT id, usname FROM login WHERE usname = '$username' AND pass = '$password'";
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $row['usname'];
            $_SESSION['user_id'] = $row['id'];
            header("location: home.php");
            exit();
        } else {
            $error_message = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>RansHotel Admin - Simple Test</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f0f0f0; 
            margin: 0; 
            padding: 20px; 
        }
        .container { 
            max-width: 400px; 
            margin: 50px auto; 
            background: white; 
            padding: 30px; 
            border-radius: 10px; 
            box-shadow: 0 2px 10px rgba(0,0,0,0.1); 
        }
        h1 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 30px; 
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            color: #555; 
        }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 12px; 
            border: 1px solid #ddd; 
            border-radius: 5px; 
            font-size: 16px; 
            box-sizing: border-box; 
        }
        button { 
            width: 100%; 
            padding: 12px; 
            background: #007cba; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer; 
        }
        button:hover { 
            background: #005a87; 
        }
        .error { 
            color: red; 
            margin-top: 10px; 
            text-align: center; 
        }
        .success { 
            color: green; 
            margin-top: 10px; 
            text-align: center; 
        }
        .test-links {
            margin-top: 20px;
            text-align: center;
        }
        .test-links a {
            color: #007cba;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>RansHotel Admin</h1>
        <h2 style="text-align: center; color: #666; font-size: 16px;">Simple Login Test</h2>
        
        <?php if($error_message): ?>
            <div class="error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <?php if($success_message): ?>
            <div class="success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="user">Username:</label>
                <input type="text" id="user" name="user" required>
            </div>
            
            <div class="form-group">
                <label for="pass">Password:</label>
                <input type="password" id="pass" name="pass" required>
            </div>
            
            <button type="submit">Login</button>
        </form>
        
        <div class="test-links">
            <a href="test_connection.php">Test Connection</a>
            <a href="index.php">Full Login</a>
            <a href="../index.php">Main Site</a>
        </div>
    </div>
</body>
</html>
