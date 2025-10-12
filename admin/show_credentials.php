<?php
// Show all available login credentials
include('db.php');

echo "<h2>RansHotel Admin Login Credentials</h2>";

$sql = "SELECT id, usname, pass FROM login";
$result = mysqli_query($con, $sql);

if($result && mysqli_num_rows($result) > 0) {
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 20px 0;'>";
    echo "<tr style='background: #f0f0f0;'><th>ID</th><th>Username</th><th>Password</th><th>Status</th></tr>";
    
    while($row = mysqli_fetch_assoc($result)) {
        $username = $row['usname'];
        $password_hash = $row['pass'];
        
        // Try to determine the original password
        $possible_passwords = ['1234', '12345', 'admin', 'password', 'manager'];
        $found_password = 'Unknown';
        
        foreach($possible_passwords as $test_pass) {
            if(md5($test_pass) === $password_hash) {
                $found_password = $test_pass;
                break;
            }
        }
        
        $status = ($found_password !== 'Unknown') ? '✅ Working' : '❌ Unknown';
        
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . $username . "</strong></td>";
        echo "<td><strong>" . $found_password . "</strong></td>";
        echo "<td>" . $status . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<h3>🔑 Try These Credentials:</h3>";
    echo "<div style='background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
    echo "<p><strong>Option 1:</strong> Username: <code>admin</code> | Password: <code>1234</code></p>";
    echo "<p><strong>Option 2:</strong> Username: <code>Prasath</code> | Password: <code>12345</code></p>";
    echo "<p><strong>Option 3:</strong> Username: <code>manager</code> | Password: <code>1234</code></p>";
    echo "</div>";
    
} else {
    echo "<p style='color: red;'>❌ No users found in database</p>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Back to Login</a></p>";
echo "<p><a href='fix_admin_password.php'>Fix Admin Password</a></p>";
echo "<p><a href='test_password.php'>Test Password Hashing</a></p>";
?>
