<?php  
 // Enable error reporting for debugging
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 
 // Simple test to see if PHP is working
 echo "<!-- PHP is working -->";
 
 session_start();  
 if(isset($_SESSION["user"]))  
 {  
      header("location:dashboard_classic.php");  
      exit();
 }  

 // Process login form
 include('db.php');
 
 $error_message = '';
 $success_message = '';
 
 if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Debug: Show what was posted
    echo "<div style='background: yellow; padding: 10px; margin: 10px; border: 2px solid red;'>";
    echo "DEBUG: Form submitted! Username: " . (isset($_POST['user']) ? $_POST['user'] : 'NOT SET') . " | Password: " . (isset($_POST['pass']) ? 'SET' : 'NOT SET');
    echo "</div>";
    
    // Validate input
    if(empty($_POST['user']) || empty($_POST['pass'])) {
       $error_message = "Please enter both username and password.";
    } else {
       // Sanitize input
       $myusername = mysqli_real_escape_string($con, trim($_POST['user']));
       $mypassword = mysqli_real_escape_string($con, trim($_POST['pass'])); 
       
       // Hash the password with MD5 for security
       $hashed_password = md5($mypassword);
       
       // Check if using new database structure with roles
       $checkNewStructure = "SHOW COLUMNS FROM login LIKE 'role'";
       $structureResult = mysqli_query($con, $checkNewStructure);
       
       if(mysqli_num_rows($structureResult) > 0) {
           // New database structure with roles
           $sql = "SELECT id, usname, full_name, role, email, phone, is_active FROM login 
                   WHERE usname = '$myusername' AND (pass = '$mypassword' OR pass = '$hashed_password') AND is_active = 1";
           $result = mysqli_query($con, $sql);
           
           if($result && mysqli_num_rows($result) == 1) {
               $row = mysqli_fetch_assoc($result);
               
               // Set session variables
               $_SESSION['user'] = $row['usname'];
               $_SESSION['user_id'] = $row['id'];
               $_SESSION['full_name'] = $row['full_name'];
               $_SESSION['role'] = $row['role'];
               $_SESSION['email'] = $row['email'];
               $_SESSION['login_time'] = time();
           } else {
               $error_message = "Invalid username or password. Please try again.";
           }
       } else {
           // Old database structure (fallback)
           $sql = "SELECT id, usname FROM login WHERE usname = '$myusername' AND (pass = '$mypassword' OR pass = '$hashed_password')";
           $result = mysqli_query($con, $sql);
           
           if($result && mysqli_num_rows($result) == 1) {
               $row = mysqli_fetch_assoc($result);
               
               // Set session variables
               $_SESSION['user'] = $row['usname'];
               $_SESSION['user_id'] = $row['id'];
               $_SESSION['role'] = 'admin'; // Default role for old system
               $_SESSION['login_time'] = time();
           } else {
               $error_message = "Invalid username or password. Please try again.";
           }
       }
       
       // Debug information
       $debug_info = "Username: '$myusername', Original Password: '$mypassword', MD5 Hash: '$hashed_password'";
       error_log("Login Debug: " . $debug_info);
       
       if(isset($_SESSION['user'])) {
          
          // Log successful login
          $log_message = "Admin login successful: " . $myusername . " at " . date('Y-m-d H:i:s');
          error_log($log_message);
          
          // Redirect to admin dashboard
          header("location: dashboard_classic.php");
          exit();
       } else {
          $error_message = "Invalid username or password. Please try again.";
          
          // Log failed login attempt
          $log_message = "Failed login attempt: " . $myusername . " at " . date('Y-m-d H:i:s');
          error_log($log_message);
       }
    }
 }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>RANS HOTEL Admin Login</title>
  <meta name="description" content="RANS HOTEL Admin Panel - Located in Tsito, Ghana. Manage your hotel operations efficiently">
  <meta name="author" content="RANS HOTEL">
  
  <link rel="stylesheet" href="css/style.css">
  <style>
    .login-container {
      max-width: 400px;
      margin: 0 auto;
      padding: 20px;
      width: 100%;
      box-sizing: border-box;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
      .login-container {
        max-width: 100%;
        padding: 15px;
        margin: 10px;
      }
      
      .hotel-logo {
        font-size: 2em !important;
      }
      
      .hotel-name {
        font-size: 1.5em !important;
      }
      
      .form-group input {
        font-size: 16px !important; /* Prevents zoom on iOS */
      }
      
      .login-btn {
        font-size: 16px !important;
        padding: 15px !important;
      }
    }
    
    @media (max-width: 480px) {
      .login-container {
        padding: 10px;
        margin: 5px;
      }
      
      .hotel-logo {
        font-size: 1.8em !important;
      }
      
      .hotel-name {
        font-size: 1.3em !important;
      }
      
      .form-group input {
        padding: 15px 12px !important;
      }
    }
    .hotel-branding {
      text-align: center;
      margin-bottom: 30px;
    }
    .hotel-logo {
      font-size: 2.5em;
      color: #1a73e8;
      margin-bottom: 10px;
    }
    .hotel-name {
      font-size: 1.8em;
      color: #fff;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .hotel-location {
      color: #ccc;
      font-size: 0.9em;
    }
    .login-form {
      background: rgba(255, 255, 255, 0.1);
      padding: 30px;
      border-radius: 10px;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }
    .form-group {
      margin-bottom: 20px;
    }
    .form-group label {
      display: block;
      color: #fff;
      margin-bottom: 8px;
      font-weight: 500;
    }
    .form-group input {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid rgba(255, 255, 255, 0.3);
      border-radius: 5px;
      background: rgba(255, 255, 255, 0.9);
      color: #333;
      font-size: 16px;
    }
    .form-group input:focus {
      outline: none;
      border-color: #1a73e8;
      background: rgba(255, 255, 255, 0.95);
      color: #333;
      box-shadow: 0 0 5px rgba(26, 115, 232, 0.3);
    }
    .form-group input::placeholder {
      color: rgba(0, 0, 0, 0.5);
    }
    .login-btn {
      width: 100%;
      padding: 12px;
      background: #1a73e8;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
    }
    .login-btn:hover {
      background: #1557b0;
    }
    .homepage-link {
      text-align: center;
      margin-top: 30px;
    }
    .homepage-link a {
      color: #1a73e8;
      text-decoration: none;
      font-weight: 500;
    }
    .homepage-link a:hover {
      color: #4285f4;
      text-decoration: underline;
    }
    .admin-info {
      background: rgba(255, 255, 255, 0.05);
      padding: 15px;
      border-radius: 5px;
      margin-top: 20px;
      text-align: center;
    }
    .admin-info h4 {
      color: #fff;
      margin-bottom: 10px;
    }
    .admin-info p {
      color: #ccc;
      font-size: 0.9em;
      margin: 5px 0;
    }
    
    /* Ensure full responsiveness */
    body {
      margin: 0;
      padding: 0;
      width: 100%;
      overflow-x: hidden;
    }
    
    .container {
      width: 100%;
      max-width: 100%;
      margin: 0 auto;
      padding: 0 15px;
      box-sizing: border-box;
    }
    
    @media (max-width: 768px) {
      .container {
        padding: 0 10px;
      }
    }
  </style>
</head>

<body>
  <div id="clouds">
	<div class="cloud x1"></div>
	<!-- Time for multiple clouds to dance around -->
	<div class="cloud x2"></div>
	<div class="cloud x3"></div>

</div>

 <div class="container">
    <div class="login-container">
      <!-- Hotel Branding -->
      <div class="hotel-branding">
        <div class="hotel-logo">RANS HOTEL</div>
        <div class="hotel-name">Admin Panel</div>
        <div class="hotel-location">Tsito, Ghana</div>
      </div>

      <!-- Login Form -->
      <div class="login-form">
        <?php if(!empty($error_message)): ?>
          <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">
            <?php echo htmlspecialchars($error_message); ?>
          </div>
        <?php endif; ?>
        
        <?php if(!empty($success_message)): ?>
          <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
            <?php echo htmlspecialchars($success_message); ?>
          </div>
        <?php endif; ?>
        
        <form method="post" id="loginForm" action="">
          <div class="form-group">
            <label for="user">Username</label>
            <input type="text" id="user" name="user" placeholder="Enter your username" 
                   value="<?php echo isset($_POST['user']) ? htmlspecialchars($_POST['user']) : ''; ?>" required>
          </div>

          <div class="form-group">
            <label for="pass">Password</label>
            <input type="password" id="pass" name="pass" placeholder="Enter your password" required>
          </div>

          <button type="submit" name="sub" class="login-btn">
            Login to Admin Panel
          </button>
        </form>

        <!-- Admin Information -->
        <div class="admin-info">
          <h4>Secure Access</h4>
          <p>Authorized personnel only</p>
          <p><em>Contact system administrator for access</em></p>
        </div>
      </div>

      <!-- Homepage Link -->
      <div class="homepage-link">
        <a href="../index.php">
          Back to RansHotel Homepage
        </a>
      </div>
    </div>
  </div>
  
  <!-- PHP handles all form validation and processing -->
  
</body>
</html>
