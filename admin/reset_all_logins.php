<?php
// Reset all logins and create new ones
include('db.php');

echo "<h2>🔄 Resetting All Admin Logins</h2>";

// Delete all existing logins
echo "<h3>1. Deleting All Existing Logins:</h3>";
$delete_sql = "DELETE FROM login";
$delete_result = mysqli_query($con, $delete_sql);

if($delete_result) {
    $deleted_count = mysqli_affected_rows($con);
    echo "✅ <strong>Deleted $deleted_count existing login(s)</strong><br>";
} else {
    echo "❌ Error deleting logins: " . mysqli_error($con) . "<br>";
}

// Create new admin users
echo "<h3>2. Creating New Admin Users:</h3>";

$new_users = [
    [
        'username' => 'admin',
        'password' => 'admin123',
        'description' => 'Main Administrator'
    ],
    [
        'username' => 'manager',
        'password' => 'manager123',
        'description' => 'Hotel Manager'
    ],
    [
        'username' => 'staff',
        'password' => 'staff123',
        'description' => 'Staff Member'
    ]
];

$created_count = 0;
foreach($new_users as $user) {
    $username = $user['username'];
    $password = $user['password'];
    $hashed_password = md5($password);
    
    $sql = "INSERT INTO login (usname, pass) VALUES ('$username', '$hashed_password')";
    $result = mysqli_query($con, $sql);
    
    if($result) {
        echo "✅ Created user: <strong>$username</strong> | Password: <strong>$password</strong> | Role: $user[description]<br>";
        $created_count++;
    } else {
        echo "❌ Error creating user $username: " . mysqli_error($con) . "<br>";
    }
}

// Show final results
echo "<h3>3. Final Results:</h3>";
echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
echo "<h4 style='color: #155724; margin-top: 0;'>🎉 Login Reset Complete!</h4>";
echo "<p><strong>Created $created_count new admin users</strong></p>";
echo "</div>";

// Display all current users
echo "<h3>4. All Current Users in Database:</h3>";
$sql = "SELECT id, usname, pass FROM login ORDER BY id";
$result = mysqli_query($con, $sql);

if($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
    echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Username</th><th>Password</th><th>Status</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . $row['usname'] . "</strong></td>";
        echo "<td><strong>" . $row['pass'] . "</strong></td>";
        echo "<td>✅ Active</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No users found in database.</p>";
}

// Show login credentials
echo "<h3>5. 🔑 Your New Login Credentials:</h3>";
echo "<div style='background: #fff3cd; padding: 20px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #ffc107;'>";
echo "<h4 style='margin-top: 0; color: #856404;'>Choose any of these to login:</h4>";

foreach($new_users as $user) {
    echo "<div style='background: white; padding: 10px; margin: 10px 0; border-radius: 3px; border: 1px solid #e0e0e0;'>";
    echo "<p><strong>Username:</strong> <code style='background: #f8f9fa; padding: 3px 6px; border-radius: 3px;'>" . $user['username'] . "</code></p>";
    echo "<p><strong>Password:</strong> <code style='background: #f8f9fa; padding: 3px 6px; border-radius: 3px;'>" . $user['password'] . "</code></p>";
    echo "<p><strong>Role:</strong> " . $user['description'] . "</p>";
    echo "</div>";
}
echo "</div>";

echo "<hr>";
echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='index.php' style='background: #007cba; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 0 10px; font-weight: bold;'>🔐 Login Now</a>";
echo "<a href='simple_login.php' style='background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin: 0 10px; font-weight: bold;'>🚀 Simple Login</a>";
echo "</div>";

echo "<div style='background: #e7f3ff; padding: 15px; border-radius: 5px; margin: 20px 0; border-left: 4px solid #007cba;'>";
echo "<h4 style='margin-top: 0; color: #004085;'>💡 Quick Login Test:</h4>";
echo "<p>Try logging in with: <strong>admin</strong> / <strong>admin123</strong></p>";
echo "</div>";
?>
