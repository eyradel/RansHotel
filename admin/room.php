<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

// Include database connection
include('db.php');
// Include access control system
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('add_room');

// Ensure room table has all required columns
$columns_check = mysqli_query($con, "SHOW COLUMNS FROM room");
$existing_columns = [];
while($col = mysqli_fetch_assoc($columns_check)) {
    $existing_columns[] = $col['Field'];
}

// Add missing columns if they don't exist
if(!in_array('room_number', $existing_columns)) {
    mysqli_query($con, "ALTER TABLE room ADD COLUMN room_number VARCHAR(10) AFTER id");
}
if(!in_array('floor', $existing_columns)) {
    mysqli_query($con, "ALTER TABLE room ADD COLUMN floor INT DEFAULT 1");
}
if(!in_array('max_occupancy', $existing_columns)) {
    mysqli_query($con, "ALTER TABLE room ADD COLUMN max_occupancy INT DEFAULT 2");
}
if(!in_array('status', $existing_columns)) {
    mysqli_query($con, "ALTER TABLE room ADD COLUMN status VARCHAR(20) DEFAULT 'Available'");
    // Update existing rows to have a status based on their 'place' value
    mysqli_query($con, "UPDATE room SET status = CASE WHEN place = 'Free' THEN 'Available' ELSE 'Occupied' END WHERE status IS NULL");
}
if(!in_array('amenities', $existing_columns)) {
    mysqli_query($con, "ALTER TABLE room ADD COLUMN amenities TEXT");
}

// Populate room_number for existing records that don't have it
mysqli_query($con, "UPDATE room SET room_number = CONCAT('R', LPAD(id, 3, '0')) WHERE room_number IS NULL OR room_number = ''");

// Automatically clean up orphaned occupied rooms and update statuses
// This runs every time the page loads
try {
    $today = date('Y-m-d');
    $cleaned_count = 0;
    
    // 1. Free rooms that are marked as occupied but have no valid booking (orphaned rooms)
    // This handles rooms with NULL cusid
    $cleanup_orphaned1 = "UPDATE room 
                         SET place = 'Free',
                             status = 'Available',
                             cusid = NULL
                         WHERE (status = 'Occupied' OR place = 'NotFree')
                         AND cusid IS NULL";
    mysqli_query($con, $cleanup_orphaned1);
    $cleaned_count += mysqli_affected_rows($con);
    
    // Free rooms with invalid cusid (cusid doesn't exist in roombook)
    $cleanup_orphaned2 = "UPDATE room r
                         SET r.place = 'Free',
                             r.status = 'Available',
                             r.cusid = NULL
                         WHERE (r.status = 'Occupied' OR r.place = 'NotFree')
                         AND r.cusid IS NOT NULL
                         AND r.cusid NOT IN (SELECT id FROM roombook)";
    mysqli_query($con, $cleanup_orphaned2);
    $cleaned_count += mysqli_affected_rows($con);
    
    // Free rooms linked to cancelled or checked out bookings
    $cleanup_orphaned3 = "UPDATE room r
                         INNER JOIN roombook rb ON rb.id = r.cusid
                         SET r.place = 'Free',
                             r.status = 'Available',
                             r.cusid = NULL
                         WHERE (r.status = 'Occupied' OR r.place = 'NotFree')
                         AND rb.stat IN ('Cancelled', 'Checked Out')";
    mysqli_query($con, $cleanup_orphaned3);
    $cleaned_count += mysqli_affected_rows($con);
    
    // 2. Free rooms where checkout date has passed
    $cleanup_expired = "UPDATE room r
                       INNER JOIN roombook rb ON rb.id = r.cusid
                       SET r.place = 'Free',
                           r.status = 'Available',
                           r.cusid = NULL
                       WHERE r.status = 'Occupied'
                       AND r.place = 'NotFree'
                       AND rb.cout < '$today'
                       AND rb.stat NOT IN ('Checked Out', 'Cancelled')";
    mysqli_query($con, $cleanup_expired);
    $cleaned_count += mysqli_affected_rows($con);
    
    // 3. Update booking status to Checked Out for expired bookings
    $update_expired_bookings = "UPDATE roombook 
                               SET stat = 'Checked Out' 
                               WHERE stat IN ('Confirmed', 'Checked In', 'Pending')
                               AND cout < '$today'
                               AND stat NOT IN ('Checked Out', 'Cancelled')";
    mysqli_query($con, $update_expired_bookings);
    
    // 4. Auto-check-in bookings where check-in date is today or past
    $auto_checkin = "UPDATE roombook 
                    SET stat = 'Checked In' 
                    WHERE stat = 'Confirmed' 
                    AND cin <= '$today' 
                    AND cout >= '$today'";
    mysqli_query($con, $auto_checkin);
    
    // Track if rooms were cleaned up
    $rooms_cleaned = $cleaned_count > 0;
    
} catch (Exception $e) {
    // Log error but don't interrupt page display
    error_log('Room cleanup error: ' . $e->getMessage());
    $rooms_cleaned = false;
}

// Handle form submissions
$message = '';
$message_type = '';

// Show notification if rooms were automatically cleaned up
if (isset($rooms_cleaned) && $rooms_cleaned && !isset($_GET['cleaned']) && empty($message)) {
    $message = "Room statuses automatically updated (orphaned rooms freed, expired checkouts processed)";
    $message_type = "success";
}

// Add Room
if(isset($_POST['add'])) {
    $room_number = mysqli_real_escape_string($con, $_POST['room_number']);
    $room = mysqli_real_escape_string($con, $_POST['troom']);
    $bed = mysqli_real_escape_string($con, $_POST['bed']);
    $floor = mysqli_real_escape_string($con, $_POST['floor']);
    $max_occupancy = mysqli_real_escape_string($con, $_POST['max_occupancy']);
    $place = 'Free';
    
    // Check if room number already exists
    $check = "SELECT * FROM room WHERE room_number = '$room_number'";
    $rs = mysqli_query($con, $check);
    
    if(mysqli_num_rows($rs) > 0) {
        $message = "Room number already exists!";
        $message_type = "error";
    } else {
        $sql = "INSERT INTO `room`(`room_number`, `type`, `bedding`, `floor`, `max_occupancy`, `place`, `status`) 
                VALUES ('$room_number','$room','$bed','$floor','$max_occupancy','$place', 'Available')";
        
        if(mysqli_query($con, $sql)) {
            $message = "Room added successfully!";
            $message_type = "success";
        } else {
            $message = "Error adding room. Please try again.";
            $message_type = "error";
        }
    }
}

// Update Room Status
if(isset($_POST['update_status'])) {
    $room_id = mysqli_real_escape_string($con, $_POST['room_id']);
    $new_status = mysqli_real_escape_string($con, $_POST['new_status']);
    
    $sql = "UPDATE room SET status = '$new_status' WHERE id = '$room_id'";
    if(mysqli_query($con, $sql)) {
        $message = "Room status updated successfully!";
        $message_type = "success";
    } else {
        $message = "Error updating status.";
        $message_type = "error";
    }
}

// Delete Room
if(isset($_GET['delete'])) {
    $room_id = mysqli_real_escape_string($con, $_GET['delete']);
    
    // Check if room is currently occupied
    $check = "SELECT * FROM room WHERE id = '$room_id' AND place = 'Free'";
    $rs = mysqli_query($con, $check);
    
    if(mysqli_num_rows($rs) > 0) {
        $sql = "DELETE FROM room WHERE id = '$room_id'";
        if(mysqli_query($con, $sql)) {
            $message = "Room deleted successfully!";
            $message_type = "success";
        }
    } else {
        $message = "Cannot delete occupied room!";
        $message_type = "error";
    }
}

// Get room statistics
$total_rooms_query = "SELECT COUNT(*) as total FROM room";
$total_rooms_result = mysqli_query($con, $total_rooms_query);
$total_rooms = mysqli_fetch_assoc($total_rooms_result)['total'];

$available_query = "SELECT COUNT(*) as available FROM room WHERE place = 'Free' AND status = 'Available'";
$available_result = mysqli_query($con, $available_query);
$available_rooms = mysqli_fetch_assoc($available_result)['available'];

// Count only rooms that are actually occupied with active bookings
$occupied_query = "SELECT COUNT(*) as occupied 
                   FROM room r
                   INNER JOIN roombook rb ON rb.id = r.cusid
                   WHERE r.status = 'Occupied'
                   AND r.place = 'NotFree'
                   AND rb.stat NOT IN ('Cancelled', 'Checked Out')
                   AND rb.cout >= CURDATE()";
$occupied_result = mysqli_query($con, $occupied_query);
$occupied_rooms = mysqli_fetch_assoc($occupied_result)['occupied'];

$maintenance_query = "SELECT COUNT(*) as maintenance FROM room WHERE status = 'Maintenance'";
$maintenance_result = mysqli_query($con, $maintenance_query);
$maintenance_rooms = mysqli_fetch_assoc($maintenance_result)['maintenance'];

$occupancy_rate = $total_rooms > 0 ? round(($occupied_rooms / $total_rooms) * 100, 1) : 0;

// Get all rooms with pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$filter_status = isset($_GET['status_filter']) ? mysqli_real_escape_string($con, $_GET['status_filter']) : '';

$where = "WHERE 1=1";
if($search) {
    $where .= " AND (room_number LIKE '%$search%' OR type LIKE '%$search%' OR bedding LIKE '%$search%')";
}
if($filter_status) {
    $where .= " AND status = '$filter_status'";
}

$sql = "SELECT * FROM room $where ORDER BY room_number ASC LIMIT $limit OFFSET $offset";
$rooms_result = mysqli_query($con, $sql);

$count_sql = "SELECT COUNT(*) as total FROM room $where";
$count_result = mysqli_query($con, $count_sql);
$total_filtered = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_filtered / $limit);

// Start admin page with unified layout
startUnifiedAdminPage('Room Management', 'Manage hotel rooms and inventory for RansHotel');
?>

<!-- Room Management Content with Tailwind CSS -->
<div class="w-full px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Alert Messages -->
    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg border <?php echo $message_type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'; ?> flex items-center justify-between animate-fade-in">
            <div class="flex items-center">
                <i class="fas <?php echo $message_type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?> mr-3 text-xl"></i>
                <span class="font-medium"><?php echo htmlspecialchars($message); ?></span>
            </div>
            <button onclick="this.parentElement.style.display='none'" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    <?php endif; ?>
    
    <!-- Statistics Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-8">
        
        <!-- Total Rooms Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Rooms</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800"><?php echo number_format($total_rooms); ?></p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-building text-blue-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-5 sm:px-6 py-2 sm:py-3">
                <p class="text-xs sm:text-sm text-white font-medium">Total inventory</p>
            </div>
        </div>

        <!-- Available Rooms Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">Available</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800"><?php echo number_format($available_rooms); ?></p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-green-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-5 sm:px-6 py-2 sm:py-3">
                <p class="text-xs sm:text-sm text-white font-medium">Ready for booking</p>
            </div>
        </div>

        <!-- Occupied Rooms Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-purple-600 uppercase tracking-wide mb-1">Occupied</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800"><?php echo number_format($occupied_rooms); ?></p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-purple-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-5 sm:px-6 py-2 sm:py-3">
                <p class="text-xs sm:text-sm text-white font-medium">Currently in use</p>
            </div>
        </div>

        <!-- Occupancy Rate Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden group">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-amber-600 uppercase tracking-wide mb-1">Occupancy Rate</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800"><?php echo $occupancy_rate; ?>%</p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-100 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-pie text-amber-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-5 sm:px-6 py-2 sm:py-3">
                <div class="flex items-center justify-between">
                    <p class="text-xs sm:text-sm text-white font-medium">Performance metric</p>
                    <div class="flex-shrink-0 w-16 bg-white bg-opacity-30 rounded-full h-2 overflow-hidden">
                        <div class="bg-white h-full rounded-full" style="width: <?php echo $occupancy_rate; ?>%"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">
        
        <!-- Add Room Form - Left Column -->
        <div class="xl:col-span-1">
            <div class="bg-white rounded-xl shadow-md overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 border-b border-blue-700">
                    <h3 class="text-lg sm:text-xl font-bold text-white flex items-center">
                        <i class="fas fa-plus-circle mr-3"></i>
                        Add New Room
                    </h3>
                </div>
                
                <form method="post" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Room Number *
                            <span class="text-xs font-normal text-gray-500 ml-2">(e.g., 101, 205)</span>
                        </label>
                        <input type="text" name="room_number" required placeholder="101" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Room Type *</label>
                        <select name="troom" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">Select Room Type</option>
                                        <option value="Standard">Standard Room</option>
                                        <option value="Mini Executive">Mini Executive Room</option>
                                        <option value="Executive">Executive Room</option>
                                            </select>
                              </div>
							  
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Bedding Type *</label>
                        <select name="bed" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">Select Bedding Type</option>
                                        <option value="Single">Single Bed</option>
                            <option value="Double">Double Bed</option>
                            <option value="Twin">Twin Beds</option>
                            <option value="Queen">Queen Bed</option>
                            <option value="King">King Bed</option>
                                            </select>
                                </div>
                                            
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Floor *</label>
                            <input type="number" name="floor" min="0" max="50" value="1" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Max Guests *</label>
                            <input type="number" name="max_occupancy" min="1" max="10" value="2" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                    
                    <button type="submit" name="add" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Add Room
                                </button>
							</form>
                
                <!-- Quick Stats -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <p class="text-xs text-gray-600 mb-2 font-semibold uppercase">Quick Stats</p>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Under Maintenance:</span>
                            <span class="font-bold text-gray-800"><?php echo $maintenance_rooms; ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Total Capacity:</span>
                            <span class="font-bold text-gray-800">
							<?php
                                $capacity_query = "SELECT SUM(max_occupancy) as total FROM room";
                                $capacity_result = mysqli_query($con, $capacity_query);
                                $total_capacity = mysqli_fetch_assoc($capacity_result)['total'] ?? 0;
                                echo number_format($total_capacity);
                                ?> guests
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Room List - Right Column -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-list-ul mr-3 text-blue-600"></i>
                            Room Inventory
                        </h3>
                        
                        <!-- Search and Filter -->
                        <div class="flex flex-col sm:flex-row gap-2">
                            <form method="get" class="flex gap-2">
                                <input type="text" name="search" placeholder="Search rooms..." value="<?php echo htmlspecialchars($search); ?>" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                
                                <select name="status_filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    <option value="">All Status</option>
                                    <option value="Available" <?php echo $filter_status === 'Available' ? 'selected' : ''; ?>>Available</option>
                                    <option value="Occupied" <?php echo $filter_status === 'Occupied' ? 'selected' : ''; ?>>Occupied</option>
                                    <option value="Maintenance" <?php echo $filter_status === 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                    <option value="Cleaning" <?php echo $filter_status === 'Cleaning' ? 'selected' : ''; ?>>Cleaning</option>
                                </select>
                                
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-search"></i>
                                </button>
                                
                                <?php if($search || $filter_status): ?>
                                <a href="room.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                                    <i class="fas fa-times"></i>
                                </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Rooms Table -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Room</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden md:table-cell">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Bedding</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden lg:table-cell">Floor</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if(mysqli_num_rows($rooms_result) == 0): ?>
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                        <i class="fas fa-bed text-6xl mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium">No rooms found</p>
                                        <p class="text-sm">Add your first room using the form on the left</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php while($row = mysqli_fetch_array($rooms_result)): ?>
                                        <?php
                                    $status = $row['status'] ?? ($row['place'] === 'Free' ? 'Available' : 'Occupied');
                                    $status_colors = [
                                        'Available' => 'bg-green-100 text-green-800 border-green-200',
                                        'Occupied' => 'bg-purple-100 text-purple-800 border-purple-200',
                                        'Maintenance' => 'bg-red-100 text-red-800 border-red-200',
                                        'Cleaning' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'Reserved' => 'bg-amber-100 text-amber-800 border-amber-200'
                                    ];
                                    $status_class = $status_colors[$status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4">
                                            <div class="flex items-center">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                                    <i class="fas fa-bed text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-800">#<?php echo htmlspecialchars($row['room_number'] ?? $row['id']); ?></p>
                                                    <p class="text-xs text-gray-500 md:hidden"><?php echo htmlspecialchars($row['type']); ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-800 hidden md:table-cell">
                                            <?php echo htmlspecialchars($row['type']); ?>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600 hidden sm:table-cell">
                                            <?php echo htmlspecialchars($row['bedding']); ?>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600 hidden lg:table-cell">
                                            <span class="inline-flex items-center">
                                                <i class="fas fa-layer-group text-gray-400 mr-2"></i>
                                                Floor <?php echo htmlspecialchars($row['floor'] ?? '1'); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border <?php echo $status_class; ?>">
                                                <?php echo htmlspecialchars($status); ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center justify-center gap-2">
                                                <!-- Status Update Dropdown -->
                                                <div class="relative group">
                                                    <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Change Status">
                                                        <i class="fas fa-exchange-alt"></i>
                                                    </button>
                                                    <div class="hidden group-hover:block absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                                        <form method="post" class="p-2">
                                                            <input type="hidden" name="room_id" value="<?php echo $row['id']; ?>">
                                                            <select name="new_status" onchange="this.form.submit()" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                                                                <option value="">Change Status...</option>
                                                                <option value="Available">Available</option>
                                                                <option value="Occupied">Occupied</option>
                                                                <option value="Maintenance">Maintenance</option>
                                                                <option value="Cleaning">Cleaning</option>
                                                            </select>
                                                            <input type="hidden" name="update_status" value="1">
                                                        </form>
                                                    </div>
                                                </div>
                                                
                                                <!-- Delete Button -->
                                                <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this room?');" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Room">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                
                <!-- Pagination -->
                <?php if($total_pages > 1): ?>
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-gray-600">
                            Showing <?php echo $offset + 1; ?> to <?php echo min($offset + $limit, $total_filtered); ?> of <?php echo $total_filtered; ?> rooms
                        </p>
                        
                        <div class="flex gap-2">
                            <?php if($page > 1): ?>
                                <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $filter_status ? '&status_filter=' . urlencode($filter_status) : ''; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                    <i class="fas fa-chevron-left"></i> Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php for($i = max(1, $page - 2); $i <= min($total_pages, $page + 2); $i++): ?>
                                <a href="?page=<?php echo $i; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $filter_status ? '&status_filter=' . urlencode($filter_status) : ''; ?>" class="px-4 py-2 <?php echo $i === $page ? 'bg-blue-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50'; ?> rounded-lg transition-colors text-sm font-medium">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if($page < $total_pages): ?>
                                <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?><?php echo $filter_status ? '&status_filter=' . urlencode($filter_status) : ''; ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                    Next <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                       </div>
        </div>
        
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

<script>
// Auto-hide alert after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('[class*="bg-green-50"], [class*="bg-red-50"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>

<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>
