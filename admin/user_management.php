<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
initAccessControl('user_management');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>User Management - RansHotel</title>
    <meta name="description" content="Manage admin users and staff for RansHotel">
    <meta name="author" content="RansHotel">
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Professional Admin Styles-->
    <link href="assets/css/professional-admin.css" rel="stylesheet" />
    <!-- Responsive Admin Styles-->
    <link href="assets/css/responsive-admin.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <?php
                    $menu = getNavigationMenu();
                    foreach ($menu as $item) {
                        $activeClass = (basename($_SERVER['PHP_SELF']) == basename($item['url'])) ? 'active-menu' : '';
                        echo '<li><a class="' . $activeClass . '" href="' . $item['url'] . '"><i class="fa ' . $item['icon'] . '"></i> ' . $item['text'] . '</a></li>';
                    }
                    ?>
                    <li>
                        <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
       
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            User Management <small>Manage Admin Users and Staff</small>
                        </h1>
                    </div>
                </div>

                <?php
                // Handle form submissions
                if(isset($_POST['add_user'])) {
                    $username = mysqli_real_escape_string($con, $_POST['username']);
                    $password = mysqli_real_escape_string($con, $_POST['password']);
                    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $phone = mysqli_real_escape_string($con, $_POST['phone']);
                    $role = mysqli_real_escape_string($con, $_POST['role']);
                    
                    // Check if username already exists
                    $checkUser = "SELECT id FROM login WHERE usname = ?";
                    $stmt = mysqli_prepare($con, $checkUser);
                    mysqli_stmt_bind_param($stmt, "s", $username);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    
                    if(mysqli_num_rows($result) > 0) {
                        echo "<div class='alert alert-danger'>Username already exists!</div>";
                    } else {
                        $insertUser = "INSERT INTO login (usname, pass, full_name, email, phone, role) VALUES (?, MD5(?), ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($con, $insertUser);
                        mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $full_name, $email, $phone, $role);
                        
                        if(mysqli_stmt_execute($stmt)) {
                            echo "<div class='alert alert-success'>User added successfully!</div>";
                        } else {
                            echo "<div class='alert alert-danger'>Error adding user: " . mysqli_error($con) . "</div>";
                        }
                    }
                }
                
                if(isset($_POST['update_user'])) {
                    $user_id = $_POST['user_id'];
                    $username = mysqli_real_escape_string($con, $_POST['username']);
                    $full_name = mysqli_real_escape_string($con, $_POST['full_name']);
                    $email = mysqli_real_escape_string($con, $_POST['email']);
                    $phone = mysqli_real_escape_string($con, $_POST['phone']);
                    $role = mysqli_real_escape_string($con, $_POST['role']);
                    $is_active = isset($_POST['is_active']) ? 1 : 0;
                    
                    $updateUser = "UPDATE login SET usname = ?, full_name = ?, email = ?, phone = ?, role = ?, is_active = ? WHERE id = ?";
                    $stmt = mysqli_prepare($con, $updateUser);
                    mysqli_stmt_bind_param($stmt, "sssssii", $username, $full_name, $email, $phone, $role, $is_active, $user_id);
                    
                    if(mysqli_stmt_execute($stmt)) {
                        echo "<div class='alert alert-success'>User updated successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error updating user: " . mysqli_error($con) . "</div>";
                    }
                }
                
                if(isset($_POST['reset_password'])) {
                    $user_id = $_POST['user_id'];
                    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
                    
                    $resetPassword = "UPDATE login SET pass = MD5(?) WHERE id = ?";
                    $stmt = mysqli_prepare($con, $resetPassword);
                    mysqli_stmt_bind_param($stmt, "si", $new_password, $user_id);
                    
                    if(mysqli_stmt_execute($stmt)) {
                        echo "<div class='alert alert-success'>Password reset successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error resetting password: " . mysqli_error($con) . "</div>";
                    }
                }
                ?>

                <div class="row">
                    <!-- Add New User Form -->
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Add New User
                            </div>
                            <div class="panel-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input type="text" name="username" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Password</label>
                                        <input type="password" name="password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Full Name</label>
                                        <input type="text" name="full_name" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label>Role</label>
                                        <select name="role" class="form-control" required>
                                            <option value="staff">Staff</option>
                                            <option value="manager">Manager</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <button type="submit" name="add_user" class="btn btn-primary">
                                        <i class="fa fa-plus"></i> Add User
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Information -->
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                System Information
                            </div>
                            <div class="panel-body">
                                <h4>Current User</h4>
                                <p><strong>Username:</strong> <?php echo $_SESSION['user']; ?></p>
                                <p><strong>Full Name:</strong> <?php echo $_SESSION['full_name'] ?? 'N/A'; ?></p>
                                <p><strong>Role:</strong> <?php echo $_SESSION['role'] ?? 'admin'; ?></p>
                                <p><strong>Email:</strong> <?php echo $_SESSION['email'] ?? 'N/A'; ?></p>
                                
                                <hr>
                                
                                <h4>Database Status</h4>
                                <?php
                                $checkNewStructure = "SHOW COLUMNS FROM login LIKE 'role'";
                                $structureResult = mysqli_query($con, $checkNewStructure);
                                
                                if(mysqli_num_rows($structureResult) > 0) {
                                    echo "<p class='text-success'><i class='fa fa-check'></i> Enhanced database structure active</p>";
                                } else {
                                    echo "<p class='text-warning'><i class='fa fa-warning'></i> Using legacy database structure</p>";
                                    echo "<p><small>Run database_updates.sql to enable enhanced features</small></p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users List -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                System Users
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Username</th>
                                                <th>Full Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Role</th>
                                                <th>Status</th>
                                                <th>Last Login</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM login ORDER BY id ASC";
                                            $query = mysqli_query($con, $sql);
                                            while($row = mysqli_fetch_assoc($query)) {
                                                echo "<tr>";
                                                echo "<td>{$row['id']}</td>";
                                                echo "<td>{$row['usname']}</td>";
                                                echo "<td>" . ($row['full_name'] ?? 'N/A') . "</td>";
                                                echo "<td>" . ($row['email'] ?? 'N/A') . "</td>";
                                                echo "<td>" . ($row['phone'] ?? 'N/A') . "</td>";
                                                echo "<td><span class='label label-info'>" . ($row['role'] ?? 'admin') . "</span></td>";
                                                echo "<td>";
                                                if(isset($row['is_active'])) {
                                                    echo $row['is_active'] ? "<span class='label label-success'>Active</span>" : "<span class='label label-danger'>Inactive</span>";
                                                } else {
                                                    echo "<span class='label label-success'>Active</span>";
                                                }
                                                echo "</td>";
                                                echo "<td>" . ($row['last_login'] ?? 'Never') . "</td>";
                                                echo "<td>";
                                                echo "<button class='btn btn-sm btn-primary' onclick='editUser({$row['id']})'><i class='fa fa-edit'></i></button> ";
                                                echo "<button class='btn btn-sm btn-warning' onclick='resetPassword({$row['id']})'><i class='fa fa-key'></i></button>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JS Scripts-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom-scripts.js"></script>
    
    <script>
    function editUser(userId) {
        // Implementation for editing user
        alert('Edit user functionality - User ID: ' + userId);
    }
    
    function resetPassword(userId) {
        var newPassword = prompt('Enter new password for user ID ' + userId + ':');
        if(newPassword && newPassword.length >= 6) {
            // Create form and submit
            var form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = '<input type="hidden" name="user_id" value="' + userId + '">' +
                           '<input type="hidden" name="new_password" value="' + newPassword + '">' +
                           '<input type="hidden" name="reset_password" value="1">';
            document.body.appendChild(form);
            form.submit();
        } else if(newPassword) {
            alert('Password must be at least 6 characters long');
        }
    }
    </script>
</body>
</html>


