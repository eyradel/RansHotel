<?php
include('db.php');

echo "=== Database Check ===\n";

// Check roombook table
$sql = "SELECT COUNT(*) as total FROM roombook";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
echo "Total roombook entries: " . $row['total'] . "\n";

// Check entries with email
$sql = "SELECT COUNT(*) as total FROM roombook WHERE Email IS NOT NULL AND Email != ''";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
echo "Entries with email: " . $row['total'] . "\n";

// Check entries with phone
$sql = "SELECT COUNT(*) as total FROM roombook WHERE Phone IS NOT NULL AND Phone != ''";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
echo "Entries with phone: " . $row['total'] . "\n";

// Show sample data
echo "\n=== Sample Data ===\n";
$sql = "SELECT Email, FName, LName, Phone FROM roombook LIMIT 3";
$result = mysqli_query($con, $sql);

while($row = mysqli_fetch_assoc($result)) {
    echo "Email: " . ($row['Email'] ?? 'NULL') . "\n";
    echo "Name: " . ($row['FName'] ?? 'NULL') . " " . ($row['LName'] ?? 'NULL') . "\n";
    echo "Phone: " . ($row['Phone'] ?? 'NULL') . "\n";
    echo "---\n";
}

// Check contact table
echo "\n=== Contact Table ===\n";
$sql = "SELECT COUNT(*) as total FROM contact";
$result = mysqli_query($con, $sql);
$row = mysqli_fetch_assoc($result);
echo "Total contact entries: " . $row['total'] . "\n";

// Show sample contact data
$sql = "SELECT email, fullname FROM contact LIMIT 3";
$result = mysqli_query($con, $sql);

while($row = mysqli_fetch_assoc($result)) {
    echo "Email: " . ($row['email'] ?? 'NULL') . "\n";
    echo "Name: " . ($row['fullname'] ?? 'NULL') . "\n";
    echo "---\n";
}
?>
