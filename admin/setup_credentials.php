<?php
/**
 * Setup proper credentials and roles for RansHotel Admin
 */
include('db.php');

echo "<h2>RansHotel Credentials Setup</h2>";

// Check if role column exists
$checkRole = "SHOW COLUMNS FROM login LIKE 'role'";
$roleResult = mysqli_query($con, $checkRole);

if(mysqli_num_rows($roleResult) == 0) {
    echo "<p>Adding role column to login table...</p>";
    $addRole = "ALTER TABLE `login` ADD COLUMN `role` ENUM('admin', 'manager', 'staff') DEFAULT 'staff'";
    if(mysqli_query($con, $addRole)) {
        echo "<p>✅ Role column added successfully</p>";
    } else {
        echo "<p>❌ Error adding role column: " . mysqli_error($con) . "</p>";
    }
} else {
    echo "<p>✅ Role column already exists</p>";
}

// Check if other columns exist and add them if needed
$columns = [
    'email' => "VARCHAR(100) DEFAULT NULL",
    'full_name' => "VARCHAR(100) DEFAULT NULL", 
    'phone' => "VARCHAR(20) DEFAULT NULL",
    'created_at' => "TIMESTAMP DEFAULT CURRENT_TIMESTAMP",
    'last_login' => "TIMESTAMP NULL DEFAULT NULL",
    'is_active' => "TINYINT(1) DEFAULT 1"
];

foreach($columns as $column => $definition) {
    $checkColumn = "SHOW COLUMNS FROM login LIKE '$column'";
    $columnResult = mysqli_query($con, $checkColumn);
    
    if(mysqli_num_rows($columnResult) == 0) {
        echo "<p>Adding $column column...</p>";
        $addColumn = "ALTER TABLE `login` ADD COLUMN `$column` $definition";
        if(mysqli_query($con, $addColumn)) {
            echo "<p>✅ $column column added successfully</p>";
        } else {
            echo "<p>❌ Error adding $column column: " . mysqli_error($con) . "</p>";
        }
    } else {
        echo "<p>✅ $column column already exists</p>";
    }
}

// Update existing admin user
echo "<p>Updating admin user...</p>";
$updateAdmin = "UPDATE `login` SET 
    `usname` = 'admin',
    `pass` = MD5('1234'),
    `email` = 'admin@ranshotel.com',
    `full_name` = 'RansHotel Administrator',
    `role` = 'admin',
    `phone` = '0540202096',
    `is_active` = 1
WHERE `usname` = 'admin'";

if(mysqli_query($con, $updateAdmin)) {
    echo "<p>✅ Admin user updated successfully</p>";
} else {
    echo "<p>❌ Error updating admin user: " . mysqli_error($con) . "</p>";
}

// Add manager user
echo "<p>Adding manager user...</p>";
$checkManager = "SELECT id FROM login WHERE usname = 'manager'";
$managerResult = mysqli_query($con, $checkManager);

if(mysqli_num_rows($managerResult) == 0) {
    $addManager = "INSERT INTO `login` (`usname`, `pass`, `email`, `full_name`, `role`, `phone`, `is_active`) VALUES
     ('manager', MD5('1234'), 'manager@ranshotel.com', 'RansHotel Manager', 'manager', '0540202096', 1)";
    
    if(mysqli_query($con, $addManager)) {
        echo "<p>✅ Manager user added successfully</p>";
    } else {
        echo "<p>❌ Error adding manager user: " . mysqli_error($con) . "</p>";
    }
} else {
    // Update existing manager
    $updateManager = "UPDATE `login` SET 
        `pass` = MD5('1234'),
        `email` = 'manager@ranshotel.com',
        `full_name` = 'RansHotel Manager',
        `role` = 'manager',
        `phone` = '0540202096',
        `is_active` = 1
    WHERE `usname` = 'manager'";
    
    if(mysqli_query($con, $updateManager)) {
        echo "<p>✅ Manager user updated successfully</p>";
    } else {
        echo "<p>❌ Error updating manager user: " . mysqli_error($con) . "</p>";
    }
}

// Add staff user
echo "<p>Adding staff user...</p>";
$checkStaff = "SELECT id FROM login WHERE usname = 'staff'";
$staffResult = mysqli_query($con, $checkStaff);

if(mysqli_num_rows($staffResult) == 0) {
    $addStaff = "INSERT INTO `login` (`usname`, `pass`, `email`, `full_name`, `role`, `phone`, `is_active`) VALUES
     ('staff', MD5('1234'), 'staff@ranshotel.com', 'RansHotel Staff', 'staff', '0540202096', 1)";
    
    if(mysqli_query($con, $addStaff)) {
        echo "<p>✅ Staff user added successfully</p>";
    } else {
        echo "<p>❌ Error adding staff user: " . mysqli_error($con) . "</p>";
    }
} else {
    // Update existing staff
    $updateStaff = "UPDATE `login` SET 
        `pass` = MD5('1234'),
        `email` = 'staff@ranshotel.com',
        `full_name` = 'RansHotel Staff',
        `role` = 'staff',
        `phone` = '0540202096',
        `is_active` = 1
    WHERE `usname` = 'staff'";
    
    if(mysqli_query($con, $updateStaff)) {
        echo "<p>✅ Staff user updated successfully</p>";
    } else {
        echo "<p>❌ Error updating staff user: " . mysqli_error($con) . "</p>";
    }
}

echo "<hr>";
echo "<h3>🔑 Updated Login Credentials:</h3>";
echo "<div style='background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
echo "<p><strong>Admin:</strong> Username: <code>admin</code> | Password: <code>1234</code></p>";
echo "<p><strong>Manager:</strong> Username: <code>manager</code> | Password: <code>1234</code></p>";
echo "<p><strong>Staff:</strong> Username: <code>staff</code> | Password: <code>1234</code></p>";
echo "</div>";

echo "<p><a href='index.php'>← Back to Login</a></p>";
echo "<p><a href='test_access_control.php'>Test Access Control</a></p>";
?>
