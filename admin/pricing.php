<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('pricing');

// Handle form submissions
$message = '';
$message_type = '';

if ($_POST) {
    if (isset($_POST['update_room_price'])) {
        $room_type = mysqli_real_escape_string($con, $_POST['room_type']);
        $bedding_type = mysqli_real_escape_string($con, $_POST['bedding_type']);
        $price = mysqli_real_escape_string($con, $_POST['price']);
        
        // Check if pricing table exists, if not create it
        $check_table = "SHOW TABLES LIKE 'room_pricing'";
        $result = mysqli_query($con, $check_table);
        
        if (mysqli_num_rows($result) == 0) {
            // Create room_pricing table
            $create_table = "CREATE TABLE `room_pricing` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `room_type` varchar(50) NOT NULL,
                `bedding_type` varchar(20) NOT NULL,
                `price_per_night` decimal(10,2) NOT NULL,
                `currency` varchar(3) DEFAULT 'GHS',
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_room_bedding` (`room_type`, `bedding_type`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            if (mysqli_query($con, $create_table)) {
                // Insert default pricing data
                $insert_defaults = "INSERT INTO `room_pricing` (`room_type`, `bedding_type`, `price_per_night`, `currency`) VALUES
                    ('Superior Room', 'Single', 3840.00, 'GHS'),
                    ('Superior Room', 'Double', 4500.00, 'GHS'),
                    ('Superior Room', 'Triple', 5500.00, 'GHS'),
                    ('Superior Room', 'Quad', 6500.00, 'GHS'),
                    ('Deluxe Room', 'Single', 2640.00, 'GHS'),
                    ('Deluxe Room', 'Double', 3200.00, 'GHS'),
                    ('Deluxe Room', 'Triple', 4000.00, 'GHS'),
                    ('Deluxe Room', 'Quad', 4800.00, 'GHS'),
                    ('Guest House', 'Single', 2160.00, 'GHS'),
                    ('Guest House', 'Double', 2600.00, 'GHS'),
                    ('Guest House', 'Triple', 3200.00, 'GHS'),
                    ('Guest House', 'Quad', 3800.00, 'GHS'),
                    ('Single Room', 'Single', 1800.00, 'GHS'),
                    ('Single Room', 'Double', 2200.00, 'GHS'),
                    ('Single Room', 'Triple', 2800.00, 'GHS')";
                
                mysqli_query($con, $insert_defaults);
            }
        }
        
        // Update or insert pricing
        $sql = "INSERT INTO `room_pricing` (`room_type`, `bedding_type`, `price_per_night`, `currency`) 
                VALUES ('$room_type', '$bedding_type', '$price', 'GHS')
                ON DUPLICATE KEY UPDATE 
                `price_per_night` = '$price', 
                `updated_at` = CURRENT_TIMESTAMP";
        
        if (mysqli_query($con, $sql)) {
            $message = "Room price updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating room price: " . mysqli_error($con);
            $message_type = "error";
        }
    }
    
    if (isset($_POST['update_meal_price'])) {
        $meal_plan = mysqli_real_escape_string($con, $_POST['meal_plan']);
        $price = mysqli_real_escape_string($con, $_POST['meal_price']);
        
        // Check if meal_pricing table exists, if not create it
        $check_table = "SHOW TABLES LIKE 'meal_pricing'";
        $result = mysqli_query($con, $check_table);
        
        if (mysqli_num_rows($result) == 0) {
            // Create meal_pricing table
            $create_table = "CREATE TABLE `meal_pricing` (
                `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `meal_plan` varchar(50) NOT NULL,
                `price_per_person_per_day` decimal(10,2) NOT NULL,
                `currency` varchar(3) DEFAULT 'GHS',
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_meal_plan` (`meal_plan`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            
            if (mysqli_query($con, $create_table)) {
                // Insert default meal pricing
                $insert_defaults = "INSERT INTO `meal_pricing` (`meal_plan`, `price_per_person_per_day`, `currency`) VALUES
                    ('Room only', 0.00, 'GHS'),
                    ('Breakfast', 25.00, 'GHS'),
                    ('Half Board', 45.00, 'GHS'),
                    ('Full Board', 65.00, 'GHS')";
                
                mysqli_query($con, $insert_defaults);
            }
        }
        
        // Update or insert meal pricing
        $sql = "INSERT INTO `meal_pricing` (`meal_plan`, `price_per_person_per_day`, `currency`) 
                VALUES ('$meal_plan', '$price', 'GHS')
                ON DUPLICATE KEY UPDATE 
                `price_per_person_per_day` = '$price', 
                `updated_at` = CURRENT_TIMESTAMP";
        
        if (mysqli_query($con, $sql)) {
            $message = "Meal plan price updated successfully!";
            $message_type = "success";
        } else {
            $message = "Error updating meal plan price: " . mysqli_error($con);
            $message_type = "error";
        }
    }
    
    if (isset($_POST['bulk_update_prices'])) {
        $bulk_room_type = mysqli_real_escape_string($con, $_POST['bulk_room_type']);
        $bulk_percentage = floatval($_POST['bulk_percentage']);
        $bulk_reason = mysqli_real_escape_string($con, $_POST['bulk_reason']);
        
        // Build the WHERE clause
        $where_clause = "is_active = 1";
        if (!empty($bulk_room_type)) {
            $where_clause .= " AND room_type = '$bulk_room_type'";
        }
        
        // Calculate new prices
        $multiplier = 1 + ($bulk_percentage / 100);
        $sql = "UPDATE room_pricing 
                SET price_per_night = ROUND(price_per_night * $multiplier, 2),
                    updated_at = CURRENT_TIMESTAMP
                WHERE $where_clause";
        
        if (mysqli_query($con, $sql)) {
            $affected_rows = mysqli_affected_rows($con);
            $message = "Successfully updated $affected_rows room prices by $bulk_percentage%";
            $message_type = "success";
        } else {
            $message = "Error updating prices: " . mysqli_error($con);
            $message_type = "error";
        }
    }
}

// Get current pricing data
$room_pricing = [];
$meal_pricing = [];

// Get room pricing
$room_sql = "SELECT * FROM room_pricing WHERE is_active = 1 ORDER BY room_type, bedding_type";
$room_result = mysqli_query($con, $room_sql);
if ($room_result) {
    while ($row = mysqli_fetch_assoc($room_result)) {
        $room_pricing[] = $row;
    }
}

// Get meal pricing
$meal_sql = "SELECT * FROM meal_pricing WHERE is_active = 1 ORDER BY meal_plan";
$meal_result = mysqli_query($con, $meal_sql);
if ($meal_result) {
    while ($row = mysqli_fetch_assoc($meal_result)) {
        $meal_pricing[] = $row;
    }
}

// Get pricing statistics
$room_types_query = "SELECT COUNT(DISTINCT room_type) as total_types FROM room_pricing";
$room_types_result = mysqli_query($con, $room_types_query);
$room_types_data = mysqli_fetch_assoc($room_types_result);
$total_room_types = $room_types_data['total_types'] ?? 0;

$avg_price_query = "SELECT AVG(price_per_night) as avg_price FROM room_pricing WHERE is_active = 1";
$avg_price_result = mysqli_query($con, $avg_price_query);
$avg_price_data = mysqli_fetch_assoc($avg_price_result);
$avg_price = $avg_price_data['avg_price'] ?? 0;

$max_price_query = "SELECT MAX(price_per_night) as max_price FROM room_pricing WHERE is_active = 1";
$max_price_result = mysqli_query($con, $max_price_query);
$max_price_data = mysqli_fetch_assoc($max_price_result);
$max_price = $max_price_data['max_price'] ?? 0;

$min_price_query = "SELECT MIN(price_per_night) as min_price FROM room_pricing WHERE is_active = 1";
$min_price_result = mysqli_query($con, $min_price_query);
$min_price_data = mysqli_fetch_assoc($min_price_result);
$min_price = $min_price_data['min_price'] ?? 0;

// Start admin page with unified layout
startUnifiedAdminPage('Pricing Management', 'Manage room and meal pricing for RansHotel');
?>

<!-- Pricing Management Content with Tailwind CSS -->
<div class="w-full px-4 sm:px-6 lg:px-8 py-6">
    
    <!-- Alert Messages -->
    <?php if ($message): ?>
        <div class="mb-6 p-4 rounded-lg border <?php echo $message_type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'; ?> flex items-center justify-between">
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
        
        <!-- Total Room Types Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-blue-600 uppercase tracking-wide mb-1">Total Room Types</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800"><?php echo number_format($total_room_types); ?></p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-bed text-blue-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-blue-50 px-5 sm:px-6 py-2 sm:py-3">
                <p class="text-xs sm:text-sm text-blue-700">Active pricing configurations</p>
            </div>
        </div>

        <!-- Average Room Price Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-green-600 uppercase tracking-wide mb-1">Average Price</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800">₵<?php echo number_format($avg_price, 2); ?></p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-green-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-green-50 px-5 sm:px-6 py-2 sm:py-3">
                <p class="text-xs sm:text-sm text-green-700">Per night average rate</p>
            </div>
        </div>

        <!-- Highest Price Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-purple-600 uppercase tracking-wide mb-1">Highest Price</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800">₵<?php echo number_format($max_price, 2); ?></p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-up text-purple-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-purple-50 px-5 sm:px-6 py-2 sm:py-3">
                <p class="text-xs sm:text-sm text-purple-700">Premium room rate</p>
            </div>
        </div>

        <!-- Lowest Price Card -->
        <div class="bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-5 sm:p-6 flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs sm:text-sm font-semibold text-amber-600 uppercase tracking-wide mb-1">Lowest Price</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800">₵<?php echo number_format($min_price, 2); ?></p>
                </div>
                <div class="flex-shrink-0 ml-4">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-arrow-down text-amber-600 text-xl sm:text-2xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-amber-50 px-5 sm:px-6 py-2 sm:py-3">
                <p class="text-xs sm:text-sm text-amber-700">Budget room rate</p>
            </div>
        </div>

    </div>

    <!-- Main Pricing Management Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200">
            <div class="flex flex-wrap -mb-px overflow-x-auto">
                <button onclick="switchTab('room-pricing')" id="tab-room-pricing" class="pricing-tab flex-shrink-0 px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-blue-500 text-blue-600 font-semibold text-sm sm:text-base whitespace-nowrap flex items-center">
                    <i class="fas fa-bed mr-2"></i>
                    <span class="hidden sm:inline">Room </span>Pricing
                </button>
                <button onclick="switchTab('meal-pricing')" id="tab-meal-pricing" class="pricing-tab flex-shrink-0 px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 font-medium text-sm sm:text-base whitespace-nowrap flex items-center">
                    <i class="fas fa-utensils mr-2"></i>
                    <span class="hidden sm:inline">Meal </span>Pricing
                </button>
                <button onclick="switchTab('bulk-pricing')" id="tab-bulk-pricing" class="pricing-tab flex-shrink-0 px-4 sm:px-6 py-3 sm:py-4 border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 font-medium text-sm sm:text-base whitespace-nowrap flex items-center">
                    <i class="fas fa-edit mr-2"></i>
                    <span class="hidden sm:inline">Bulk </span>Update
                </button>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="p-4 sm:p-6 lg:p-8">
            
            <!-- Room Pricing Tab -->
            <div id="content-room-pricing" class="tab-content">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    
                    <!-- Update Room Price Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-lg p-5 sm:p-6 border border-blue-100">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-edit text-blue-600 mr-3"></i>
                                Update Room Price
                            </h3>
                            
                            <form method="post" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Room Type *</label>
                                    <select name="room_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">Select Room Type</option>
                                        <option value="Standard">Standard Room</option>
                                        <option value="Mini Executive">Mini Executive Room</option>
                                        <option value="Executive">Executive Room</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bedding Type *</label>
                                    <select name="bedding_type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <option value="">Select Bedding Type</option>
                                        <option value="Single">Single Bed</option>
                                        <option value="Double">Double Bed</option>
                                        <option value="Triple">Triple Bed</option>
                                        <option value="Quad">Quad Bed</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price per Night (₵) *</label>
                                    <input type="number" name="price" step="0.01" min="0" required placeholder="0.00" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                                
                                <button type="submit" name="update_room_price" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Price
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Current Room Prices Table -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg border border-gray-200">
                            <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-list text-blue-600 mr-3"></i>
                                    Current Room Prices
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Room Type</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Bedding</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price/Night</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php if (empty($room_pricing)): ?>
                                            <tr>
                                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                                    <p>No pricing data available. Add your first room price above.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($room_pricing as $price): ?>
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-800"><?php echo htmlspecialchars($price['room_type']); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-600"><?php echo htmlspecialchars($price['bedding_type']); ?></td>
                                                    <td class="px-4 py-3 text-sm font-bold text-green-600">₵<?php echo number_format($price['price_per_night'], 2); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-500 hidden sm:table-cell"><?php echo date('M j, Y', strtotime($price['updated_at'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Meal Pricing Tab -->
            <div id="content-meal-pricing" class="tab-content hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                    
                    <!-- Update Meal Price Form -->
                    <div class="lg:col-span-1">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-lg p-5 sm:p-6 border border-green-100">
                            <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-4 flex items-center">
                                <i class="fas fa-edit text-green-600 mr-3"></i>
                                Update Meal Price
                            </h3>
                            
                            <form method="post" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Meal Plan *</label>
                                    <select name="meal_plan" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                        <option value="">Select Meal Plan</option>
                                        <option value="Room only">Room only</option>
                                        <option value="Breakfast">Breakfast</option>
                                        <option value="Half Board">Half Board</option>
                                        <option value="Full Board">Full Board</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Price per Person per Day (₵) *</label>
                                    <input type="number" name="meal_price" step="0.01" min="0" required placeholder="0.00" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                                </div>
                                
                                <button type="submit" name="update_meal_price" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-save mr-2"></i>
                                    Update Price
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Current Meal Prices Table -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg border border-gray-200">
                            <div class="px-5 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-list text-green-600 mr-3"></i>
                                    Current Meal Plan Prices
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-50 border-b border-gray-200">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Meal Plan</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price/Person/Day</th>
                                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider hidden sm:table-cell">Updated</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <?php if (empty($meal_pricing)): ?>
                                            <tr>
                                                <td colspan="3" class="px-4 py-8 text-center text-gray-500">
                                                    <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
                                                    <p>No meal pricing data available. Add your first meal plan above.</p>
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($meal_pricing as $price): ?>
                                                <tr class="hover:bg-gray-50 transition-colors">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-800"><?php echo htmlspecialchars($price['meal_plan']); ?></td>
                                                    <td class="px-4 py-3 text-sm font-bold text-green-600">₵<?php echo number_format($price['price_per_person_per_day'], 2); ?></td>
                                                    <td class="px-4 py-3 text-sm text-gray-500 hidden sm:table-cell"><?php echo date('M j, Y', strtotime($price['updated_at'])); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Bulk Update Tab -->
            <div id="content-bulk-pricing" class="tab-content hidden">
                <div class="max-w-4xl mx-auto">
                    <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-lg p-6 sm:p-8 border border-amber-200">
                        <div class="flex items-start mb-6">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-amber-700 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">Bulk Price Update</h3>
                                <p class="text-sm text-gray-600">Apply percentage-based price adjustments to multiple room types at once. Use with caution!</p>
                            </div>
                        </div>
                        
                        <form method="post" onsubmit="return confirm('Are you sure you want to update prices for the selected room types?');" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Room Type Filter</label>
                                    <select name="bulk_room_type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                        <option value="">All Room Types</option>
                                        <option value="Standard">Standard Room</option>
                                        <option value="Mini Executive">Mini Executive Room</option>
                                        <option value="Executive">Executive Room</option>
                                        <option value="Superior Room">Superior Room</option>
                                        <option value="Deluxe Room">Deluxe Room</option>
                                        <option value="Guest House">Guest House</option>
                                        <option value="Single Room">Single Room</option>
                                    </select>
                                    <p class="mt-2 text-xs text-gray-500">Leave empty to update all room types</p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Percentage Change *</label>
                                    <div class="relative">
                                        <input type="number" name="bulk_percentage" step="0.01" required placeholder="e.g., 10 or -5" class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                                        <span class="absolute right-3 top-3 text-gray-500 font-semibold">%</span>
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Positive for increase, negative for decrease</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Change</label>
                                <textarea name="bulk_reason" rows="3" placeholder="e.g., Seasonal adjustment, Market competition, Cost increase..." class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all"></textarea>
                            </div>
                            
                            <div class="flex flex-col sm:flex-row gap-4">
                                <button type="submit" name="bulk_update_prices" class="flex-1 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-edit mr-2"></i>
                                    Apply Bulk Update
                                </button>
                                <button type="button" onclick="document.querySelector('form').reset();" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-all duration-300 flex items-center justify-center">
                                    <i class="fas fa-undo mr-2"></i>
                                    Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Information Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                        <div class="bg-white rounded-lg p-4 shadow-md border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-700">Tax Rate</h4>
                                <i class="fas fa-percent text-blue-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-blue-600">15%</p>
                            <p class="text-xs text-gray-500 mt-1">Applied automatically</p>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 shadow-md border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-700">Service Charge</h4>
                                <i class="fas fa-hand-holding-usd text-green-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-green-600">10%</p>
                            <p class="text-xs text-gray-500 mt-1">On total bookings</p>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 shadow-md border border-gray-200">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-semibold text-gray-700">Currency</h4>
                                <i class="fas fa-money-bill-wave text-purple-500"></i>
                            </div>
                            <p class="text-2xl font-bold text-purple-600">GHS (₵)</p>
                            <p class="text-xs text-gray-500 mt-1">Ghanaian Cedis</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
// Tab switching functionality
function switchTab(tabName) {
    // Hide all tab contents
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(content => content.classList.add('hidden'));
    
    // Remove active state from all tabs
    const tabs = document.querySelectorAll('.pricing-tab');
    tabs.forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-600');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active state to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-600');
    activeTab.classList.add('border-blue-500', 'text-blue-600');
}

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
