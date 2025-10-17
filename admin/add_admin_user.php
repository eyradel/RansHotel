<?php
/**
 * Add New Admin User Script
 * Use this to create additional admin accounts
 */

session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
    exit();
}

include('db.php');

$message = '';
$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    
    // Validation
    if(empty($new_username) || empty($new_password)) {
        $error = "Username and password are required.";
    } elseif($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif(strlen($new_password) < 4) {
        $error = "Password must be at least 4 characters long.";
    } else {
        // Check if username already exists
        $check_sql = "SELECT id FROM login WHERE usname = ?";
        $check_stmt = mysqli_prepare($con, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "s", $new_username);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);
        
        if(mysqli_num_rows($result) > 0) {
            $error = "Username already exists. Please choose a different username.";
        } else {
            // Insert new user
            $insert_sql = "INSERT INTO login (usname, pass) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($con, $insert_sql);
            mysqli_stmt_bind_param($insert_stmt, "ss", $new_username, $new_password);
            
            if(mysqli_stmt_execute($insert_stmt)) {
                $message = "New admin user '$new_username' created successfully!";
            } else {
                $error = "Error creating user: " . mysqli_error($con);
            }
        }
    }
}

// Get existing users
$users_sql = "SELECT id, usname FROM login ORDER BY id";
$users_result = mysqli_query($con, $users_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin User - RansHotel</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <style>
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .user-list {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .alert {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Add New Admin User</h2>
            
            <?php if($message): ?>
                <div class="alert alert-success">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <?php if($error): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="post" class="form-horizontal">
                <div class="form-group">
                    <label class="control-label col-sm-3">Username:</label>
                    <div class="col-sm-9">
                        <input type="text" name="username" class="form-control" required 
                               placeholder="Enter new username" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Password:</label>
                    <div class="col-sm-9">
                        <input type="password" name="password" class="form-control" required 
                               placeholder="Enter new password">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="control-label col-sm-3">Confirm Password:</label>
                    <div class="col-sm-9">
                        <input type="password" name="confirm_password" class="form-control" required 
                               placeholder="Confirm new password">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-primary">Create Admin User</button>
                        <a href="home.php" class="btn btn-default">Back to Dashboard</a>
                    </div>
                </div>
            </form>
            
            <div class="user-list">
                <h4>Existing Admin Users:</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($user = mysqli_fetch_assoc($users_result)): ?>
                        <tr>
                            <td><?php echo $user['id']; ?></td>
                            <td><?php echo htmlspecialchars($user['usname']); ?></td>
                            <td>
                                <?php if($user['usname'] == $_SESSION['user']): ?>
                                    <span class="label label-success">Current User</span>
                                <?php else: ?>
                                    <span class="label label-info">Active</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>


