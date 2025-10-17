<?php
session_start();

// Process login
if($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');
    
    $username = trim($_POST['user'] ?? '');
    $password = trim($_POST['pass'] ?? '');
    
    if(!empty($username) && !empty($password)) {
        // Try both hashed and plain text passwords
        $hashed_password = md5($password);
        
        // First try with hashed password
        $sql = "SELECT id, usname FROM login WHERE usname = '$username' AND pass = '$hashed_password'";
        $result = mysqli_query($con, $sql);
        
        // If no result, try with plain text password
        if(!$result || mysqli_num_rows($result) == 0) {
            $sql = "SELECT id, usname FROM login WHERE usname = '$username' AND pass = '$password'";
            $result = mysqli_query($con, $sql);
        }
        
        if($result && mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $row['usname'];
            $_SESSION['user_id'] = $row['id'];
            header("location: dashboard_simple.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    } else {
        $error = "Please enter both username and password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>RansHotel Admin - Simple Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0; 
            padding: 20px; 
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container { 
            max-width: 400px; 
            width: 100%;
            background: white; 
            padding: 40px; 
            border-radius: 10px; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 { 
            text-align: center; 
            color: #333; 
            margin-bottom: 30px;
            font-size: 2em;
        }
        .form-group { 
            margin-bottom: 20px; 
        }
        label { 
            display: block; 
            margin-bottom: 5px; 
            color: #555; 
            font-weight: bold;
        }
        input[type="text"], input[type="password"] { 
            width: 100%; 
            padding: 15px; 
            border: 2px solid #ddd; 
            border-radius: 5px; 
            font-size: 16px; 
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
        }
        button { 
            width: 100%; 
            padding: 15px; 
            background: #667eea; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            font-size: 16px; 
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover { 
            background: #5a6fd8; 
        }
        .error { 
            color: #e74c3c; 
            margin-top: 15px; 
            text-align: center;
            padding: 10px;
            background: #fdf2f2;
            border-radius: 5px;
            border: 1px solid #fecaca;
        }
        .credentials {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            border-left: 4px solid #667eea;
        }
        .credentials h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .credentials p {
            margin: 5px 0;
            color: #666;
        }
        .links {
            text-align: center;
            margin-top: 20px;
        }
        .links a {
            color: #667eea;
            text-decoration: none;
            margin: 0 10px;
        }
        .links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏨 RansHotel Admin</h1>
        
        <?php if(isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
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
        
        <div class="credentials">
            <h4>🔑 Try These Credentials:</h4>
            <p><strong>Username:</strong> admin | <strong>Password:</strong> 1234</p>
            <p><strong>Username:</strong> Prasath | <strong>Password:</strong> 12345</p>
            <p><strong>Username:</strong> manager | <strong>Password:</strong> 1234</p>
        </div>
        
        <div class="links">
            <a href="debug_login.php">Debug Login</a>
            <a href="index.php">Original Login</a>
            <a href="../index.php">Main Site</a>
        </div>
    </div>
</body>
</html>


