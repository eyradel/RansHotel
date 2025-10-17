<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
initAccessControl('pricing');

// Handle form submissions
$message = '';
$message_type = '';

if ($_POST) {
    if (isset($_POST['update_room_price'])) {
        $room_type = $_POST['room_type'];
        $bedding_type = $_POST['bedding_type'];
        $price = $_POST['price'];
        
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
        $meal_plan = $_POST['meal_plan'];
        $price = $_POST['meal_price'];
        
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
?> 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>RANS HOTEL - Pricing Management</title>
	<!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
   <style>
   .pricing-card {
       background: #fff;
       border-radius: 10px;
       box-shadow: 0 2px 10px rgba(0,0,0,0.1);
       margin-bottom: 20px;
       padding: 20px;
   }
   .price-display {
       font-size: 1.5em;
       font-weight: bold;
       color: #1a73e8;
   }
   .form-group label {
       font-weight: 600;
       color: #333;
   }
   .btn-update {
       background: #1a73e8;
       border: none;
       color: white;
       padding: 8px 20px;
       border-radius: 5px;
       cursor: pointer;
   }
   .btn-update:hover {
       background: #1557b0;
   }
   .alert-success {
       background: #d4edda;
       color: #155724;
       border: 1px solid #c3e6cb;
       padding: 10px;
       border-radius: 5px;
       margin-bottom: 20px;
   }
   .alert-error {
       background: #f8d7da;
       color: #721c24;
       border: 1px solid #f5c6cb;
       padding: 10px;
       border-radius: 5px;
       margin-bottom: 20px;
   }
   </style>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="home.php">MAIN MENU </a>
            </div>

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="usersetting.php"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="settings.php"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!--/. NAV TOP  -->
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="home.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="roombook.php"><i class="fa fa-book"></i> Room Booking</a>
                    </li>
                    <li>
                        <a href="reservation.php"><i class="fa fa-calendar"></i> Reservations</a>
                    </li>
                    <li>
                        <a href="settings.php"><i class="fa fa-cog"></i> Room Status</a>
                    </li>
                    <li>
                        <a href="room.php"><i class="fa fa-plus-circle"></i> Add Room</a>
                    </li>
                    <li>
                        <a href="roomdel.php"><i class="fa fa-pencil-square-o"></i> Delete Room</a>
                    </li>
                    <li>
                        <a class="active-menu" href="pricing.php"><i class="fa fa-dollar"></i> Pricing Management</a>
                    </li>
                    <li>
                        <a href="messages.php"><i class="fa fa-envelope"></i> Messages</a>
                    </li>
                    <li>
                        <a href="payment.php"><i class="fa fa-credit-card"></i> Payment</a>
                    </li>
                    <li>
                        <a href="profit.php"><i class="fa fa-bar-chart-o"></i> Profit</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /. NAV SIDE  -->
       
        <div id="page-wrapper" >
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                           <i class="fa fa-dollar"></i> Pricing Management
                        </h1>
                    </div>
                </div> 
                
                <?php if ($message): ?>
                    <div class="alert-<?php echo $message_type; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <!-- Room Pricing Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="pricing-card">
                            <h3><i class="fa fa-bed"></i> Room Pricing Management</h3>
                            <p>Manage room prices for different room types and bedding configurations.</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Update Room Price</h4>
                                    <form method="post">
                                        <div class="form-group">
                                            <label>Room Type</label>
                                            <select name="room_type" class="form-control" required>
                                                <option value="">Select Room Type</option>
                                                <option value="Superior Room">Superior Room</option>
                                                <option value="Deluxe Room">Deluxe Room</option>
                                                <option value="Guest House">Guest House</option>
                                                <option value="Single Room">Single Room</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Bedding Type</label>
                                            <select name="bedding_type" class="form-control" required>
                                                <option value="">Select Bedding Type</option>
                                                <option value="Single">Single</option>
                                                <option value="Double">Double</option>
                                                <option value="Triple">Triple</option>
                                                <option value="Quad">Quad</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Price per Night (₵)</label>
                                            <input type="number" name="price" class="form-control" step="0.01" min="0" required>
                                        </div>
                                        <button type="submit" name="update_room_price" class="btn-update">
                                            <i class="fa fa-save"></i> Update Price
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="col-md-6">
                                    <h4>Current Room Prices</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Room Type</th>
                                                    <th>Bedding</th>
                                                    <th>Price/Night</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($room_pricing)): ?>
                                                    <tr>
                                                        <td colspan="3" class="text-center">No pricing data available. Create pricing tables first.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($room_pricing as $price): ?>
                                                        <tr>
                                                            <td><?php echo $price['room_type']; ?></td>
                                                            <td><?php echo $price['bedding_type']; ?></td>
                                                            <td class="price-display">₵<?php echo number_format($price['price_per_night'], 2); ?></td>
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
                </div>
                
                <!-- Meal Pricing Section -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="pricing-card">
                            <h3><i class="fa fa-cutlery"></i> Meal Plan Pricing Management</h3>
                            <p>Manage meal plan prices per person per day.</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Update Meal Plan Price</h4>
                                    <form method="post">
                                        <div class="form-group">
                                            <label>Meal Plan</label>
                                            <select name="meal_plan" class="form-control" required>
                                                <option value="">Select Meal Plan</option>
                                                <option value="Room only">Room only</option>
                                                <option value="Breakfast">Breakfast</option>
                                                <option value="Half Board">Half Board</option>
                                                <option value="Full Board">Full Board</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Price per Person per Day (₵)</label>
                                            <input type="number" name="meal_price" class="form-control" step="0.01" min="0" required>
                                        </div>
                                        <button type="submit" name="update_meal_price" class="btn-update">
                                            <i class="fa fa-save"></i> Update Price
                                        </button>
                                    </form>
                                </div>
                                
                                <div class="col-md-6">
                                    <h4>Current Meal Plan Prices</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Meal Plan</th>
                                                    <th>Price/Person/Day</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (empty($meal_pricing)): ?>
                                                    <tr>
                                                        <td colspan="2" class="text-center">No meal pricing data available.</td>
                                                    </tr>
                                                <?php else: ?>
                                                    <?php foreach ($meal_pricing as $price): ?>
                                                        <tr>
                                                            <td><?php echo $price['meal_plan']; ?></td>
                                                            <td class="price-display">₵<?php echo number_format($price['price_per_person_per_day'], 2); ?></td>
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
                </div>
                
                <!-- System Information -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="pricing-card">
                            <h3><i class="fa fa-info-circle"></i> System Information</h3>
                            <div class="row">
                                <div class="col-md-4">
                                    <h5>Tax Rate</h5>
                                    <p class="price-display">15%</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Service Charge</h5>
                                    <p class="price-display">10%</p>
                                </div>
                                <div class="col-md-4">
                                    <h5>Currency</h5>
                                    <p class="price-display">Ghanaian Cedis (₵)</p>
                                </div>
                            </div>
                            <p><strong>Note:</strong> Tax and service charges are calculated automatically based on room and meal totals.</p>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>
</body>
</html>
