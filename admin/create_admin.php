<?php
// Create a new admin user with simple credentials
include('db.php');

echo "<h2>Creating New Admin User</h2>";

// Create new admin user
$username = "admin";
$password = "admin123";
$hashed_password = md5($password);

// First, delete any existing admin user
$delete_sql = "DELETE FROM login WHERE usname = 'admin'";
mysqli_query($con, $delete_sql);

// Insert new admin user
$sql = "INSERT INTO login (usname, pass) VALUES ('$username', '$hashed_password')";
$result = mysqli_query($con, $sql);

if($result) {
    echo "<div style='background: #d4edda; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3 style='color: #155724;'>✅ New Admin User Created Successfully!</h3>";
    echo "<p><strong>Username:</strong> <code style='background: #f8f9fa; padding: 5px; border-radius: 3px;'>admin</code></p>";
    echo "<p><strong>Password:</strong> <code style='background: #f8f9fa; padding: 5px; border-radius: 3px;'>admin123</code></p>";
    echo "<p><strong>Password Hash:</strong> <code style='background: #f8f9fa; padding: 5px; border-radius: 3px;'>$hashed_password</code></p>";
    echo "</div>";
    
    echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>🔑 Your New Login Credentials:</h4>";
    echo "<p><strong>Username:</strong> admin</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    echo "</div>";
    
} else {
    echo "<div style='background: #f8d7da; padding: 20px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3 style='color: #721c24;'>❌ Error creating admin user</h3>";
    echo "<p>Error: " . mysqli_error($con) . "</p>";
    echo "</div>";
}

// Show all current users
echo "<hr><h3>All Users in Database:</h3>";
$sql2 = "SELECT id, usname, pass FROM login ORDER BY id";
$result2 = mysqli_query($con, $sql2);

if($result2 && mysqli_num_rows($result2) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
    echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Username</th><th>Password Hash</th></tr>";
    
    while($row = mysqli_fetch_assoc($result2)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . $row['usname'] . "</strong></td>";
        echo "<td>" . $row['pass'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>No users found in database.</p>";
}

echo "<hr>";
echo "<div style='text-align: center; margin: 30px 0;'>";
echo "<a href='index.php' style='background: #007cba; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 0 10px;'>🔐 Login Now</a>";
echo "<a href='test_password.php' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin: 0 10px;'>🧪 Test Password</a>";
echo "</div>";
?>
