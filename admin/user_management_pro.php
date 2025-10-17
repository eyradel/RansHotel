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
    <title>User Management - RansHotel Admin</title>
    <meta name="description" content="Manage admin users and staff for RansHotel">
    <meta name="author" content="RansHotel">
    
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet' />
    
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            flex: 1;
            min-width: 200px;
            margin: 10px;
        }
        
        .stats-card h3 {
            font-size: 36px;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .stats-card p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .content-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        
        .card-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        
        .card-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 18px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-danger { background-color: #dc3545; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-info { background-color: #17a2b8; color: white; }
        .badge-success { background-color: #28a745; color: white; }
        .badge-secondary { background-color: #6c757d; color: white; }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 12px;
            margin: 2px;
        }
        
        .btn-sm { padding: 4px 8px; font-size: 11px; }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-outline-primary { background-color: transparent; color: #007bff; border: 1px solid #007bff; }
        .btn-outline-warning { background-color: transparent; color: #ffc107; border: 1px solid #ffc107; }
        .btn-secondary { background-color: #6c757d; color: white; }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin: 10px 0;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #dee2e6;
            text-align: right;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .close {
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }
        
        .close:hover {
            color: #000;
        }
        
        .actions-container {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .action-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }
        
        .action-btn:hover {
            background-color: #0056b3;
            color: white;
            text-decoration: none;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .stats-container {
                flex-direction: column;
            }
            
            .stats-card {
                margin: 5px 0;
            }
            
            .actions-container {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
                margin: 5px 0;
            }
            
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                min-width: 600px;
            }
            
            .table th,
            .table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 8px;
            }
        }
        
        @media (max-width: 480px) {
            .stats-card h3 {
                font-size: 24px;
            }
            
            .table th,
            .table td {
                font-size: 11px;
                padding: 6px;
            }
            
            .table {
                min-width: 500px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0; font-size: 24px;">RansHotel Admin</h1>
                <p style="margin: 5px 0 0 0; opacity: 0.8;">User Management System</p>
            </div>
            <div>
                <span style="margin-right: 20px;">Welcome, <?php echo $_SESSION['user']; ?></span>
                <a href="dashboard_simple.php" style="color: white; text-decoration: none; margin-right: 15px;">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
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
                echo '<div class="alert alert-danger">Username already exists!</div>';
            } else {
                $insertUser = "INSERT INTO login (usname, pass, full_name, email, phone, role) VALUES (?, MD5(?), ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $insertUser);
                mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $full_name, $email, $phone, $role);
                
                if(mysqli_stmt_execute($stmt)) {
                    echo '<div class="alert alert-success">User added successfully!</div>';
                } else {
                    echo '<div class="alert alert-danger">Error adding user: ' . mysqli_error($con) . '</div>';
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
                echo '<div class="alert alert-success">User updated successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Error updating user: ' . mysqli_error($con) . '</div>';
            }
        }
        
        if(isset($_POST['reset_password'])) {
            $user_id = $_POST['user_id'];
            $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
            
            $resetPassword = "UPDATE login SET pass = MD5(?) WHERE id = ?";
            $stmt = mysqli_prepare($con, $resetPassword);
            mysqli_stmt_bind_param($stmt, "si", $new_password, $user_id);
            
            if(mysqli_stmt_execute($stmt)) {
                echo '<div class="alert alert-success">Password reset successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Error resetting password: ' . mysqli_error($con) . '</div>';
            }
        }
        ?>

        <!-- User Statistics -->
        <div class="stats-container">
            <?php
            // Get user statistics
            $adminCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM login WHERE role = 'admin'"))['count'];
            $managerCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM login WHERE role = 'manager'"))['count'];
            $staffCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM login WHERE role = 'staff'"))['count'];
            $totalCount = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM login"))['count'];
            ?>
            
            <div class="stats-card">
                <h3 style="color: #007bff;"><?php echo $totalCount; ?></h3>
                <p>Total Users</p>
            </div>
            
            <div class="stats-card">
                <h3 style="color: #28a745;"><?php echo $adminCount; ?></h3>
                <p>Administrators</p>
            </div>
            
            <div class="stats-card">
                <h3 style="color: #ffc107;"><?php echo $managerCount; ?></h3>
                <p>Managers</p>
            </div>
            
            <div class="stats-card">
                <h3 style="color: #17a2b8;"><?php echo $staffCount; ?></h3>
                <p>Staff Members</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-container">
            <button class="action-btn" onclick="openAddUserModal()">
                <i class="fa fa-plus"></i> Add New User
            </button>
            <a href="dashboard_simple.php" class="action-btn">
                <i class="fa fa-dashboard"></i> Back to Dashboard
            </a>
        </div>

        <!-- Users Table -->
        <div class="content-card">
            <div class="card-header">
                <h3>System Users</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
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
                                echo "<td>#" . $row['id'] . "</td>";
                                echo "<td><strong>" . $row['usname'] . "</strong></td>";
                                echo "<td>" . ($row['full_name'] ?? 'N/A') . "</td>";
                                echo "<td>" . ($row['email'] ?? 'N/A') . "</td>";
                                echo "<td>" . ($row['phone'] ?? 'N/A') . "</td>";
                                
                                // Role badge
                                $roleClass = [
                                    'admin' => 'badge-danger',
                                    'manager' => 'badge-warning',
                                    'staff' => 'badge-info'
                                ][$row['role'] ?? 'admin'] ?? 'badge-secondary';
                                echo "<td><span class='badge " . $roleClass . "'>" . ucfirst($row['role'] ?? 'admin') . "</span></td>";
                                
                                // Status badge
                                if(isset($row['is_active'])) {
                                    $statusClass = $row['is_active'] ? 'badge-success' : 'badge-danger';
                                    $statusText = $row['is_active'] ? 'Active' : 'Inactive';
                                } else {
                                    $statusClass = 'badge-success';
                                    $statusText = 'Active';
                                }
                                echo "<td><span class='badge " . $statusClass . "'>" . $statusText . "</span></td>";
                                
                                echo "<td>" . ($row['last_login'] ?? 'Never') . "</td>";
                                
                                // Actions
                                echo "<td>";
                                echo "<button class='btn btn-sm btn-outline-primary' onclick='editUser(" . $row['id'] . ")' title='Edit User'>";
                                echo "<i class='fa fa-edit'></i>";
                                echo "</button>";
                                echo "<button class='btn btn-sm btn-outline-warning' onclick='resetPassword(" . $row['id'] . ")' title='Reset Password'>";
                                echo "<i class='fa fa-key'></i>";
                                echo "</button>";
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

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New User</h3>
                <span class="close" onclick="closeAddUserModal()">&times;</span>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username *</label>
                        <input type="text" class="form-control" name="username" id="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                    </div>
                    <div class="form-group">
                        <label for="full_name">Full Name *</label>
                        <input type="text" class="form-control" name="full_name" id="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="text" class="form-control" name="phone" id="phone">
                    </div>
                    <div class="form-group">
                        <label for="role">Role *</label>
                        <select class="form-control" name="role" id="role" required>
                            <option value="staff">Staff</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeAddUserModal()">Cancel</button>
                    <button type="submit" name="add_user" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
    <script>
    function openAddUserModal() {
        document.getElementById('addUserModal').style.display = 'block';
    }
    
    function closeAddUserModal() {
        document.getElementById('addUserModal').style.display = 'none';
    }
    
    function editUser(userId) {
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
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById('addUserModal');
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
    </script>
</body>
</html>