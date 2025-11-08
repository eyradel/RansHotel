<?php
/**
 * Database Sync Verification Script
 * Verifies that all queries match the actual database structure
 */

session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
    exit();
}

include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');

startUnifiedAdminPage('Database Sync Verification', 'Verify database structure compatibility');

$issues = [];
$warnings = [];
$success = [];

// Check roombook table structure
$roombook_columns = [];
$roombook_check = mysqli_query($con, "SHOW COLUMNS FROM roombook");
while($col = mysqli_fetch_assoc($roombook_check)) {
    $roombook_columns[] = $col['Field'];
}

// Check room table structure
$room_columns = [];
$room_check = mysqli_query($con, "SHOW COLUMNS FROM room");
while($col = mysqli_fetch_assoc($room_check)) {
    $room_columns[] = $col['Field'];
}

// Check payment table structure
$payment_columns = [];
$payment_check = mysqli_query($con, "SHOW COLUMNS FROM payment");
while($col = mysqli_fetch_assoc($payment_check)) {
    $payment_columns[] = $col['Field'];
}

// Verify roombook columns used in queries
$required_roombook = ['id', 'Title', 'FName', 'LName', 'Email', 'Phone', 'TRoom', 'Bed', 'NRoom', 'cin', 'cout', 'stat', 'final_amount', 'payment_status'];
foreach($required_roombook as $col) {
    if(in_array($col, $roombook_columns)) {
        $success[] = "roombook.$col exists";
    } else {
        $issues[] = "roombook.$col MISSING - queries will fail!";
    }
}

// Verify room columns used in queries
$required_room = ['id', 'room_number', 'type', 'bedding', 'place', 'cusid', 'status'];
foreach($required_room as $col) {
    if(in_array($col, $room_columns)) {
        $success[] = "room.$col exists";
    } else {
        $issues[] = "room.$col MISSING - queries will fail!";
    }
}

// Verify payment columns used in queries
$required_payment = ['id', 'fintot'];
foreach($required_payment as $col) {
    if(in_array($col, $payment_columns)) {
        $success[] = "payment.$col exists";
    } else {
        $warnings[] = "payment.$col missing (may not be critical)";
    }
}

// Test calendar query
try {
    $test_query = "SELECT rb.id, rb.Title, rb.FName, rb.LName, rb.Email, rb.Phone, rb.TRoom, rb.cin, rb.cout, rb.stat, rb.final_amount, r.room_number, r.status as room_status 
                   FROM roombook rb 
                   LEFT JOIN room r ON r.cusid = rb.id 
                   WHERE rb.cin IS NOT NULL 
                   LIMIT 1";
    $test_result = mysqli_query($con, $test_query);
    if($test_result) {
        $success[] = "Calendar query syntax is valid";
    } else {
        $issues[] = "Calendar query failed: " . mysqli_error($con);
    }
} catch(Exception $e) {
    $issues[] = "Calendar query error: " . $e->getMessage();
}

// Test room cleanup query
try {
    $test_query = "UPDATE room 
                   SET place = 'Free', status = 'Available', cusid = NULL 
                   WHERE status = 'Occupied' AND cusid IS NULL 
                   LIMIT 0";
    $test_result = mysqli_query($con, $test_query);
    if($test_result) {
        $success[] = "Room cleanup query syntax is valid";
    } else {
        $issues[] = "Room cleanup query failed: " . mysqli_error($con);
    }
} catch(Exception $e) {
    $issues[] = "Room cleanup query error: " . $e->getMessage();
}

// Test revenue query
try {
    $test_query = "SELECT SUM(rb.final_amount) as total_revenue
                   FROM roombook rb
                   WHERE rb.payment_status = 'paid'
                   AND rb.final_amount IS NOT NULL
                   LIMIT 1";
    $test_result = mysqli_query($con, $test_query);
    if($test_result) {
        $success[] = "Revenue query syntax is valid";
    } else {
        $issues[] = "Revenue query failed: " . mysqli_error($con);
    }
} catch(Exception $e) {
    $issues[] = "Revenue query error: " . $e->getMessage();
}

?>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Database Sync Verification</h2>
        
        <?php if(empty($issues)): ?>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">All Systems Synced!</h3>
                        <p class="text-green-700">All database queries are compatible with your database structure.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                    <div>
                        <h3 class="text-lg font-semibold text-red-800">Issues Found</h3>
                        <p class="text-red-700">Some database queries may fail. Please review the issues below.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($issues)): ?>
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-red-800 mb-3">Critical Issues</h3>
            <ul class="space-y-2">
                <?php foreach($issues as $issue): ?>
                <li class="flex items-start p-3 bg-red-50 border border-red-200 rounded-lg">
                    <i class="fas fa-times-circle text-red-600 mr-2 mt-1"></i>
                    <span class="text-red-800"><?php echo htmlspecialchars($issue); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($warnings)): ?>
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-yellow-800 mb-3">Warnings</h3>
            <ul class="space-y-2">
                <?php foreach($warnings as $warning): ?>
                <li class="flex items-start p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <i class="fas fa-exclamation-circle text-yellow-600 mr-2 mt-1"></i>
                    <span class="text-yellow-800"><?php echo htmlspecialchars($warning); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if(!empty($success)): ?>
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-green-800 mb-3">Verified Components</h3>
            <ul class="space-y-2">
                <?php foreach($success as $item): ?>
                <li class="flex items-start p-3 bg-green-50 border border-green-200 rounded-lg">
                    <i class="fas fa-check text-green-600 mr-2 mt-1"></i>
                    <span class="text-green-800"><?php echo htmlspecialchars($item); ?></span>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Database Structure Summary</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">roombook Table</h4>
                    <p class="text-sm text-gray-600"><?php echo count($roombook_columns); ?> columns</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">room Table</h4>
                    <p class="text-sm text-gray-600"><?php echo count($room_columns); ?> columns</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-700 mb-2">payment Table</h4>
                    <p class="text-sm text-gray-600"><?php echo count($payment_columns); ?> columns</p>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <a href="dashboard_classic.php" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

<?php
endUnifiedAdminPage();
?>

