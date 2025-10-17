<?php
session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
initAccessControl('notifications');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Debug Notifications - RansHotel</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
</head>
<body>
    <div class="container" style="margin-top: 20px;">
        <h2>Debug Notifications System</h2>
        
        <?php
        // Check roombook table structure
        echo "<h3>1. Roombook Table Structure</h3>";
        $structure = "DESCRIBE roombook";
        $result = mysqli_query($con, $structure);
        
        if($result) {
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "<td>" . $row['Extra'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Check contact table structure
        echo "<h3>2. Contact Table Structure</h3>";
        $structure = "DESCRIBE contact";
        $result = mysqli_query($con, $structure);
        
        if($result) {
            echo "<table class='table table-bordered'>";
            echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row['Field'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Null'] . "</td>";
                echo "<td>" . $row['Key'] . "</td>";
                echo "<td>" . $row['Default'] . "</td>";
                echo "<td>" . $row['Extra'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Check roombook data
        echo "<h3>3. Roombook Data Sample</h3>";
        $sql = "SELECT * FROM roombook LIMIT 5";
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            echo "<table class='table table-bordered'>";
            echo "<tr>";
            $first_row = mysqli_fetch_assoc($result);
            foreach(array_keys($first_row) as $column) {
                echo "<th>" . $column . "</th>";
            }
            echo "</tr>";
            
            // Reset result pointer
            mysqli_data_seek($result, 0);
            
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                foreach($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='alert alert-warning'>No data found in roombook table</p>";
        }
        
        // Check contact data
        echo "<h3>4. Contact Data Sample</h3>";
        $sql = "SELECT * FROM contact LIMIT 5";
        $result = mysqli_query($con, $sql);
        
        if($result && mysqli_num_rows($result) > 0) {
            echo "<table class='table table-bordered'>";
            echo "<tr>";
            $first_row = mysqli_fetch_assoc($result);
            foreach(array_keys($first_row) as $column) {
                echo "<th>" . $column . "</th>";
            }
            echo "</tr>";
            
            // Reset result pointer
            mysqli_data_seek($result, 0);
            
            while($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                foreach($row as $value) {
                    echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='alert alert-warning'>No data found in contact table</p>";
        }
        
        // Test the current query
        echo "<h3>5. Current Notification Query Results</h3>";
        $sql = "SELECT DISTINCT Email as email, CONCAT(FName, ' ', LName) as name, Phone as phone FROM roombook WHERE Email IS NOT NULL AND Email != ''";
        $result = mysqli_query($con, $sql);
        
        if($result) {
            $customers = [];
            while($row = mysqli_fetch_assoc($result)) {
                $customers[] = $row;
            }
            
            echo "<p><strong>Query:</strong> <code>" . htmlspecialchars($sql) . "</code></p>";
            echo "<p><strong>Found " . count($customers) . " customers</strong></p>";
            
            if(!empty($customers)) {
                echo "<table class='table table-bordered'>";
                echo "<tr><th>Email</th><th>Name</th><th>Phone</th><th>Valid Email</th><th>Valid Phone</th></tr>";
                foreach($customers as $customer) {
                    $validEmail = filter_var($customer['email'], FILTER_VALIDATE_EMAIL) ? '✅' : '❌';
                    $validPhone = !empty($customer['phone']) ? '✅' : '❌';
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($customer['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer['name']) . "</td>";
                    echo "<td>" . htmlspecialchars($customer['phone'] ?? 'NULL') . "</td>";
                    echo "<td>" . $validEmail . "</td>";
                    echo "<td>" . $validPhone . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p class='alert alert-warning'>No customers found with the current query</p>";
            }
        } else {
            echo "<p class='alert alert-danger'>Query failed: " . mysqli_error($con) . "</p>";
        }
        
        // Test alternative queries
        echo "<h3>6. Alternative Queries</h3>";
        
        // Query 1: All roombook entries
        echo "<h4>All Roombook Entries</h4>";
        $sql = "SELECT COUNT(*) as total FROM roombook";
        $result = mysqli_query($con, $sql);
        $total = mysqli_fetch_assoc($result)['total'];
        echo "<p>Total roombook entries: <strong>$total</strong></p>";
        
        // Query 2: Entries with email
        echo "<h4>Entries with Email</h4>";
        $sql = "SELECT COUNT(*) as total FROM roombook WHERE Email IS NOT NULL AND Email != ''";
        $result = mysqli_query($con, $sql);
        $total = mysqli_fetch_assoc($result)['total'];
        echo "<p>Entries with email: <strong>$total</strong></p>";
        
        // Query 3: Entries with phone
        echo "<h4>Entries with Phone</h4>";
        $sql = "SELECT COUNT(*) as total FROM roombook WHERE Phone IS NOT NULL AND Phone != ''";
        $result = mysqli_query($con, $sql);
        $total = mysqli_fetch_assoc($result)['total'];
        echo "<p>Entries with phone: <strong>$total</strong></p>";
        
        // Query 4: Contact table entries
        echo "<h4>Contact Table Entries</h4>";
        $sql = "SELECT COUNT(*) as total FROM contact";
        $result = mysqli_query($con, $sql);
        $total = mysqli_fetch_assoc($result)['total'];
        echo "<p>Total contact entries: <strong>$total</strong></p>";
        
        // Query 5: Contact entries with email
        echo "<h4>Contact Entries with Email</h4>";
        $sql = "SELECT COUNT(*) as total FROM contact WHERE email IS NOT NULL AND email != ''";
        $result = mysqli_query($con, $sql);
        $total = mysqli_fetch_assoc($result)['total'];
        echo "<p>Contact entries with email: <strong>$total</strong></p>";
        ?>
        
        <hr>
        <p><a href="notifications.php" class="btn btn-primary">← Back to Notifications</a></p>
    </div>
</body>
</html>
