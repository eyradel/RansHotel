<?php
include('db.php');

echo "<h2>🔐 Password Security Update</h2>";
echo "<hr>";

// Show current passwords before hashing
echo "<h3>Current Passwords (Before Hashing)</h3>";
$current_sql = "SELECT * FROM login";
$current_result = mysqli_query($con, $current_sql);

echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'>";
echo "<th>ID</th><th>Username</th><th>Current Password</th><th>MD5 Hash</th></tr>";

$passwords_to_hash = [];
while ($row = mysqli_fetch_assoc($current_result)) {
    $md5_hash = md5($row['pass']);
    $passwords_to_hash[] = [
        'id' => $row['id'],
        'username' => $row['usname'],
        'current_pass' => $row['pass'],
        'hashed_pass' => $md5_hash
    ];
    
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td><strong>" . $row['usname'] . "</strong></td>";
    echo "<td>'" . htmlspecialchars($row['pass']) . "'</td>";
    echo "<td style='font-family: monospace; font-size: 12px;'>" . $md5_hash . "</td>";
    echo "</tr>";
}
echo "</table>";

echo "<hr>";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hash_passwords'])) {
    echo "<h3>🔄 Updating Passwords to MD5 Hashes</h3>";
    
    $success_count = 0;
    $error_count = 0;
    
    foreach ($passwords_to_hash as $user) {
        $update_sql = "UPDATE login SET pass = '" . $user['hashed_pass'] . "' WHERE id = " . $user['id'];
        $result = mysqli_query($con, $update_sql);
        
        if ($result) {
            echo "<div style='background: #d4edda; color: #155724; padding: 10px; margin: 5px 0; border-radius: 5px;'>";
            echo "✅ <strong>" . $user['username'] . "</strong> - Password hashed successfully";
            echo "</div>";
            $success_count++;
        } else {
            echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 5px 0; border-radius: 5px;'>";
            echo "❌ <strong>" . $user['username'] . "</strong> - Error: " . mysqli_error($con);
            echo "</div>";
            $error_count++;
        }
    }
    
    echo "<hr>";
    echo "<div style='background: #e7f3ff; color: #004085; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>📊 Update Summary</h4>";
    echo "<p><strong>Successfully Updated:</strong> $success_count users</p>";
    echo "<p><strong>Errors:</strong> $error_count users</p>";
    echo "</div>";
    
    // Verify the updates
    echo "<h3>🔍 Verification - Updated Passwords</h3>";
    $verify_sql = "SELECT * FROM login";
    $verify_result = mysqli_query($con, $verify_sql);
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th>ID</th><th>Username</th><th>Hashed Password</th><th>Length</th></tr>";
    
    while ($row = mysqli_fetch_assoc($verify_result)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . $row['usname'] . "</strong></td>";
        echo "<td style='font-family: monospace; font-size: 12px;'>" . $row['pass'] . "</td>";
        echo "<td>" . strlen($row['pass']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>🔐 Security Enhanced!</h4>";
    echo "<p>All passwords are now hashed with MD5. You can now login with:</p>";
    echo "<ul>";
    echo "<li><strong>Admin:</strong> Username: Admin, Password: 1234</li>";
    echo "<li><strong>Prasath:</strong> Username: Prasath, Password: 12345</li>";
    echo "</ul>";
    echo "<p><em>The system will automatically hash your entered password and compare it with the stored hash.</em></p>";
    echo "</div>";
    
} else {
    echo "<h3>⚠️ Ready to Hash Passwords</h3>";
    echo "<div style='background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
    echo "<h4>🔐 Security Notice</h4>";
    echo "<p>This will convert all plain text passwords to MD5 hashes for better security.</p>";
    echo "<p><strong>After hashing:</strong></p>";
    echo "<ul>";
    echo "<li>Passwords will be stored as MD5 hashes</li>";
    echo "<li>Login system will hash entered passwords for comparison</li>";
    echo "<li>Original passwords will still work for login</li>";
    echo "<li>Database will be more secure</li>";
    echo "</ul>";
    echo "</div>";
    
    echo "<form method='post'>";
    echo "<button type='submit' name='hash_passwords' style='background: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px; cursor: pointer;'>";
    echo "🔐 Hash All Passwords";
    echo "</button>";
    echo "</form>";
}

echo "<hr>";
echo "<p><a href='index.php'>← Back to Login</a></p>";
?>


