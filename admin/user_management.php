<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('user_management');
// Start admin page with components
startUnifiedAdminPage('User Management', 'Manage admin users and staff for RansHotel');
?>
    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fa fa-users text-white text-lg"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">User Management</h1>
            </div>
            <p class="text-gray-600">Manage admin users and staff for RansHotel</p>
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
                        echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4'>Username already exists!</div>";
                    } else {
                        $insertUser = "INSERT INTO login (usname, pass, full_name, email, phone, role) VALUES (?, MD5(?), ?, ?, ?, ?)";
                        $stmt = mysqli_prepare($con, $insertUser);
                        mysqli_stmt_bind_param($stmt, "ssssss", $username, $password, $full_name, $email, $phone, $role);
                        
                        if(mysqli_stmt_execute($stmt)) {
                            echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4'>User added successfully!</div>";
                        } else {
                            echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4'>Error adding user: " . mysqli_error($con) . "</div>";
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
                        echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4'>User updated successfully!</div>";
                    } else {
                        echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4'>Error updating user: " . mysqli_error($con) . "</div>";
                    }
                }
                
                if(isset($_POST['reset_password'])) {
                    $user_id = $_POST['user_id'];
                    $new_password = mysqli_real_escape_string($con, $_POST['new_password']);
                    
                    $resetPassword = "UPDATE login SET pass = MD5(?) WHERE id = ?";
                    $stmt = mysqli_prepare($con, $resetPassword);
                    mysqli_stmt_bind_param($stmt, "si", $new_password, $user_id);
                    
                    if(mysqli_stmt_execute($stmt)) {
                        echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4'>Password reset successfully!</div>";
                    } else {
                        echo "<div class='bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4'>Error resetting password: " . mysqli_error($con) . "</div>";
                    }
                }
                ?>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Add New User Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Add New User</h3>
                        </div>
                        <div class="p-6">
                            <form method="post" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                                    <input type="text" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                    <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                    <input type="text" name="full_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                    <input type="text" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="staff">Staff</option>
                                        <option value="manager">Manager</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <button type="submit" name="add_user" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium flex items-center justify-center space-x-2">
                                    <i class="fa fa-plus"></i>
                                    <span>Add User</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- System Information -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">System Information</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Current User</h4>
                                <div class="space-y-1 text-sm">
                                    <p><span class="font-medium text-gray-600">Username:</span> <?php echo $_SESSION['user']; ?></p>
                                    <p><span class="font-medium text-gray-600">Full Name:</span> <?php echo $_SESSION['full_name'] ?? 'N/A'; ?></p>
                                    <p><span class="font-medium text-gray-600">Role:</span> <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded-full"><?php echo $_SESSION['role'] ?? 'admin'; ?></span></p>
                                    <p><span class="font-medium text-gray-600">Email:</span> <?php echo $_SESSION['email'] ?? 'N/A'; ?></p>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-200 pt-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Database Status</h4>
                                <?php
                                $checkNewStructure = "SHOW COLUMNS FROM login LIKE 'role'";
                                $structureResult = mysqli_query($con, $checkNewStructure);
                                
                                if(mysqli_num_rows($structureResult) > 0) {
                                    echo "<p class='text-green-700 text-sm flex items-center'><i class='fa fa-check mr-2'></i> Enhanced database structure active</p>";
                                } else {
                                    echo "<p class='text-yellow-700 text-sm flex items-center'><i class='fa fa-warning mr-2'></i> Using legacy database structure</p>";
                                    echo "<p class='text-xs text-gray-500 mt-1'>Run database_updates.sql to enable enhanced features</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users List -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">System Users</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                $sql = "SELECT * FROM login ORDER BY id ASC";
                                $query = mysqli_query($con, $sql);
                                while($row = mysqli_fetch_assoc($query)) {
                                    echo "<tr class='hover:bg-gray-50'>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$row['id']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['usname']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . ($row['full_name'] ?? 'N/A') . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . ($row['email'] ?? 'N/A') . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . ($row['phone'] ?? 'N/A') . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>";
                                    $role = $row['role'] ?? 'admin';
                                    $roleColors = ['admin' => 'bg-red-100 text-red-800', 'manager' => 'bg-yellow-100 text-yellow-800', 'staff' => 'bg-blue-100 text-blue-800'];
                                    $roleColor = $roleColors[$role] ?? 'bg-gray-100 text-gray-800';
                                    echo "<span class='px-2 py-1 text-xs font-medium rounded-full {$roleColor}'>" . ucfirst($role) . "</span>";
                                    echo "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>";
                                    if(isset($row['is_active'])) {
                                        if($row['is_active']) {
                                            echo "<span class='px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800'>Active</span>";
                                        } else {
                                            echo "<span class='px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800'>Inactive</span>";
                                        }
                                    } else {
                                        echo "<span class='px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800'>Active</span>";
                                    }
                                    echo "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . ($row['last_login'] ?? 'Never') . "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>";
                                    echo "<div class='flex space-x-2'>";
                                    echo "<button class='text-blue-600 hover:text-blue-900' onclick='editUser({$row['id']})' title='Edit'><i class='fa fa-edit'></i></button>";
                                    echo "<button class='text-yellow-600 hover:text-yellow-900' onclick='resetPassword({$row['id']})' title='Reset Password'><i class='fa fa-key'></i></button>";
                                    echo "</div>";
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
<?php
// End admin page with components
endUnifiedAdminPage();
?>


