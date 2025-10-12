<?php
session_start();
if(isset($_SESSION["user"])) {
    header("location:home.php");
}

include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>RansHotel Admin - Login</title>
    <meta name="description" content="RansHotel Admin Panel - Located in Tsito, Ghana. Manage your hotel operations efficiently">
    <meta name="author" content="RansHotel">
    
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #f5c6cb;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            border: 1px solid #c3e6cb;
        }
        .login-info {
            background-color: #e7f3ff;
            color: #004085;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #b8daff;
        }
    </style>
</head>

<body>
    <div id="clouds">
        <div class="cloud x1"></div>
        <div class="cloud x2"></div>
        <div class="cloud x3"></div>
        <div class="cloud x4"></div>
        <div class="cloud x5"></div>
    </div>

    <div class="container">
        <div id="login">
            <form method="post">
                <fieldset class="clearfix">
                    <p><span class="fontawesome-user"></span>
                        <input type="text" name="user" placeholder="Username" required>
                    </p>
                    <p><span class="fontawesome-lock"></span>
                        <input type="password" name="pass" placeholder="Password" required>
                    </p>
                    <p><input type="submit" name="sub" value="Login"></p>
                </fieldset>
            </form>
        </div>
    </div>
    
    <div class="bottom">
        <h3><a href="../index.php">RansHotel HOMEPAGE</a></h3>
    </div>

    <?php
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $myusername = mysqli_real_escape_string($con, $_POST['user']);
        $mypassword = mysqli_real_escape_string($con, $_POST['pass']);
        
        // Check if using new database structure
        $checkNewStructure = "SHOW COLUMNS FROM login LIKE 'role'";
        $structureResult = mysqli_query($con, $checkNewStructure);
        
        if(mysqli_num_rows($structureResult) > 0) {
            // New database structure with enhanced security
            $sql = "SELECT id, usname, full_name, role, email, phone, is_active FROM login 
                    WHERE usname = ? AND pass = MD5(?) AND is_active = 1";
            $stmt = mysqli_prepare($con, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $myusername, $mypassword);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            
            if($row) {
                // Update last login time
                $updateLogin = "UPDATE login SET last_login = NOW() WHERE id = ?";
                $stmt2 = mysqli_prepare($con, $updateLogin);
                mysqli_stmt_bind_param($stmt2, "i", $row['id']);
                mysqli_stmt_execute($stmt2);
                
                // Log the login activity
                $logActivity = "INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) 
                               VALUES (?, 'login', 'User logged in successfully', ?, ?)";
                $stmt3 = mysqli_prepare($con, $logActivity);
                $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
                mysqli_stmt_bind_param($stmt3, "iss", $row['id'], $ip, $userAgent);
                mysqli_stmt_execute($stmt3);
                
                // Set session variables
                $_SESSION['user'] = $row['usname'];
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['full_name'] = $row['full_name'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['email'] = $row['email'];
                
                header("location: home.php");
                exit();
            } else {
                echo '<div class="error-message">Invalid username or password. Please try again.</div>';
            }
        } else {
            // Old database structure (fallback)
            $sql = "SELECT id FROM login WHERE usname = '$myusername' and pass = '$mypassword'";
            $result = mysqli_query($con, $sql);
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $count = mysqli_num_rows($result);
            
            if($count == 1) {
                $_SESSION['user'] = $myusername;
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['role'] = 'admin'; // Default role for old system
                header("location: home.php");
                exit();
            } else {
                echo '<div class="error-message">Invalid username or password. Please try again.</div>';
            }
        }
    }
    ?>

    <!-- Display login information for development/testing -->
    <?php if(isset($_GET['show_credentials'])): ?>
    <div class="login-info">
        <h4>Default Login Credentials:</h4>
        <p><strong>Admin:</strong> admin / RansHotel2024!</p>
        <p><strong>Manager:</strong> manager / Manager2024!</p>
        <p><strong>Legacy Admin:</strong> Admin / 1234</p>
        <p><em>Note: Run database_updates.sql to enable new credentials</em></p>
    </div>
    <?php endif; ?>

</body>
</html>
