<?php
// Temporarily surface errors to prevent blank 500s during booking
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('db.php');
require_once 'includes/notification_manager.php';
require_once 'includes/pricing_helper.php';
require_once 'includes/phpmailer_email_system.php';
include('includes/unified_layout.php');

// Feature Toggle: Set to true to enable meal plan selection
$ENABLE_MEAL_PLAN = false;

// Initialize pricing tables if they don't exist
try {
	PricingHelper::initializePricingTables($con);
} catch (Throwable $e) {
	// Do not fail the page; show friendly error and continue
	$error_message = 'Pricing initialization error: ' . $e->getMessage();
}

// Handle form submission
$success_message = '';
$error_message = '';

if ($_POST) {
    $title = trim($_POST['title']);
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $national = trim($_POST['national']);
    $country = trim($_POST['country']);
    $phone = trim($_POST['phone']);
    $troom = trim($_POST['troom']);
    $bed = trim($_POST['bed']);
    $nroom = (int)$_POST['nroom'];
    $meal = isset($_POST['meal']) ? trim($_POST['meal']) : 'Room only'; // Default to 'Room only' if meal plan is disabled
    $cin = trim($_POST['cin']);
    $cout = trim($_POST['cout']);
    
    // Calculate days
    $checkin = new DateTime($cin);
    $checkout = new DateTime($cout);
    $days = $checkin->diff($checkout)->days;
    
    // Get pricing
    $roomPrice = PricingHelper::getRoomPrice($troom, $bed, $con);
    $mealPrice = PricingHelper::getMealPrice($meal, $con);
    
    // Calculate per-room totals (for single room)
    $roomTotalPerRoom = $roomPrice * $days;
    $mealTotalPerRoom = $mealPrice * $days;
    $subtotalPerRoom = $roomTotalPerRoom + $mealTotalPerRoom;
    $taxPerRoom = $subtotalPerRoom * 0.15; // 15% tax
    $serviceChargePerRoom = $subtotalPerRoom * 0.10; // 10% service charge
    $totalAmountPerRoom = $subtotalPerRoom + $taxPerRoom + $serviceChargePerRoom;
    
    // Calculate total for all rooms
    $roomTotal = $roomTotalPerRoom * $nroom;
    $mealTotal = $mealTotalPerRoom * $nroom;
    $subtotal = $subtotalPerRoom * $nroom;
    $tax = $taxPerRoom * $nroom;
    $serviceCharge = $serviceChargePerRoom * $nroom;
    $totalAmount = $totalAmountPerRoom * $nroom;
    
    // Ensure parent_booking_id column exists for linking multiple room bookings
    $columns_check = mysqli_query($con, "SHOW COLUMNS FROM roombook LIKE 'parent_booking_id'");
    if(mysqli_num_rows($columns_check) == 0) {
        mysqli_query($con, "ALTER TABLE roombook ADD COLUMN parent_booking_id INT NULL AFTER id");
    }
    
    // Sanitize values for SQL
    $titleDb = mysqli_real_escape_string($con, $title);
    $fnameDb = mysqli_real_escape_string($con, $fname);
    $lnameDb = mysqli_real_escape_string($con, $lname);
    $emailDb = mysqli_real_escape_string($con, $email);
    $nationalDb = mysqli_real_escape_string($con, $national);
    $countryDb = mysqli_real_escape_string($con, $country);
    $phoneDb = mysqli_real_escape_string($con, $phone);
    $troomDb = mysqli_real_escape_string($con, $troom);
    $bedDb = mysqli_real_escape_string($con, $bed);
    $mealDb = mysqli_real_escape_string($con, $meal);
    $cinDb = mysqli_real_escape_string($con, $cin);
    $coutDb = mysqli_real_escape_string($con, $cout);

    // Create separate booking records for each room
    $bookingIds = [];
    $parentBookingId = null;
    $allSuccess = true;
    
    mysqli_begin_transaction($con);
    
    try {
        for($i = 1; $i <= $nroom; $i++) {
            // For first room, parent_booking_id is NULL (it's the parent)
            // For subsequent rooms, parent_booking_id is the first booking ID
            $parentId = ($i == 1) ? 'NULL' : $parentBookingId;
            
            $sql = "INSERT INTO `roombook` (`parent_booking_id`, `Title`, `FName`, `LName`, `Email`, `National`, `Country`, `Phone`, `TRoom`, `Bed`, `NRoom`, `Meal`, `cin`, `cout`, `stat`, `nodays`, `room_price`, `meal_price`, `total_amount`, `tax_amount`, `service_charge`, `final_amount`, `currency`, `payment_status`) VALUES ($parentId, '$titleDb', '$fnameDb', '$lnameDb', '$emailDb', '$nationalDb', '$countryDb', '$phoneDb', '$troomDb', '$bedDb', '1', '$mealDb', '$cinDb', '$coutDb', 'Pending', '$days', '$roomPrice', '$mealPrice', '$subtotalPerRoom', '$taxPerRoom', '$serviceChargePerRoom', '$totalAmountPerRoom', 'GHS', 'pending')";
            
            if(mysqli_query($con, $sql)) {
                $bookingId = mysqli_insert_id($con);
                $bookingIds[] = $bookingId;
                
                // Store first booking ID as parent for subsequent rooms
                if($i == 1) {
                    $parentBookingId = $bookingId;
                    // Update first booking to reference itself (for consistency)
                    mysqli_query($con, "UPDATE roombook SET parent_booking_id = $parentBookingId WHERE id = $parentBookingId");
                }
            } else {
                throw new Exception("Failed to create booking for room $i: " . mysqli_error($con));
            }
        }
        
        mysqli_commit($con);
        
        // Send reservation notifications (for Pending status) - only once for the entire booking
        $notificationResults = null;
        try {
            $notificationManager = new NotificationManager();
            $notificationResults = $notificationManager->sendReservationNotifications([
                'booking_id' => $parentBookingId,
                'customerName' => "$title $fname $lname",
                'email' => $email,
                'phone' => $phone,
                'roomType' => $troom,
                'checkIn' => $cin,
                'checkOut' => $cout,
                'bookingId' => $parentBookingId,
                'mealPlan' => $meal,
                'nationality' => $national,
                'country' => $country,
                'totalAmount' => $totalAmount,
                'numberOfRooms' => $nroom
            ]);
        } catch (Throwable $e) {
            // Notifications failing should not break booking
            $notificationResults = ['details' => [], 'error' => $e->getMessage()];
        }
        
        // Get notification status
        $notificationStatus = isset($notificationManager) && $notificationResults
            ? $notificationManager->getNotificationStatus($notificationResults)
            : ['total_sent' => 0, 'total_failed' => 1, 'details' => ['notifications' => ['success' => false, 'error' => $notificationResults['error'] ?? 'Notification error']]];
        
        if($nroom == 1) {
            $success_message = "Reservation submitted successfully! Booking ID: $parentBookingId";
        } else {
            $success_message = "Reservation submitted successfully! Created $nroom booking" . ($nroom > 1 ? 's' : '') . ":\n";
            $success_message .= "Main Booking ID: $parentBookingId\n";
            $success_message .= "All Booking IDs: " . implode(', ', $bookingIds);
        }
        
        // Add detailed notification status to success message
        if ($notificationStatus['total_sent'] > 0) {
            $success_message .= "\n\n✅ Notifications sent: " . $notificationStatus['total_sent'];
        }
        if ($notificationStatus['total_failed'] > 0) {
            $success_message .= "\n❌ Notifications failed: " . $notificationStatus['total_failed'];
        }
        
        // Add detailed notification results for debugging
        $notificationDetails = [];
        foreach ($notificationStatus['details'] as $type => $result) {
            if ($result['success']) {
                $notificationDetails[] = "✅ " . ucfirst(str_replace('_', ' ', $type)) . ": Sent successfully";
            } else {
                $notificationDetails[] = "❌ " . ucfirst(str_replace('_', ' ', $type)) . ": " . ($result['error'] ?? 'Failed');
            }
        }
        
        if (!empty($notificationDetails)) {
            $success_message .= "\n\n" . implode("\n", $notificationDetails);
        }
        
    } catch (Exception $e) {
        mysqli_rollback($con);
        $error_message = "Error creating bookings: " . $e->getMessage();
    }
}
// Start page (always through unified layout; it now supports embed mode and hides chrome when embed=1)
startUnifiedAdminPage('Make a Reservation', 'Book your stay at RansHotel - Located in Tsito, Ghana');
?>
    
    <style>
        /* Page-specific styles */
        /* Form-only centering */
        .reservation-form { max-width: 820px; margin-left: auto; margin-right: auto; }
        @media (min-width: 1200px) { .reservation-form { max-width: 860px; } }
        /* Ensure the form container centers the card */
        #main-content { display: flex; justify-content: center; width: 100%; }
        #main-content > .container {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            width: 100%;
        }
        #main-content > .container .reservation-form { width: 100%; }
        
        .hero-section {
            background: linear-gradient(135deg, var(--classic-navy) 0%, var(--classic-navy-light) 100%);
            color: var(--classic-white);
            padding: var(--space-20) 0;
            text-align: center;
        }
        
        .hero-title {
            font-size: var(--font-size-4xl);
            margin-bottom: var(--space-4);
            color: var(--classic-gold);
        }
        
        .hero-subtitle {
            font-size: var(--font-size-lg);
            color: var(--classic-white);
            opacity: 0.95;
            max-width: 600px;
            margin: 0 auto;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .reservation-form {
            background: var(--classic-white);
            border-radius: var(--radius-2xl);
            box-shadow: var(--shadow-xl);
            margin: calc(var(--space-20) * -1) auto 0;
            position: relative;
            z-index: 10;
            max-width: 860px;
            width: 100%;
            overflow: hidden;
        }
        
        /* Onboarding Wizard Styles */
        .wizard-container {
            position: relative;
            max-width: 860px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .progress-bar {
            background: var(--classic-gray-light);
            height: 4px;
            position: relative;
            margin-bottom: var(--space-8);
        }
        
        .progress-fill {
            background: var(--classic-gold);
            height: 100%;
            width: 33.33%;
            transition: width var(--transition-normal);
        }
        
        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: var(--space-8);
            flex-wrap: wrap;
            margin-bottom: var(--space-6);
            padding: 0 var(--space-3);
        }
        
        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }
        
        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--classic-gray-light);
            color: var(--classic-gray);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-bottom: var(--space-2);
            transition: all var(--transition-normal);
        }
        
        .step.active .step-number {
            background: var(--classic-gold);
            color: var(--classic-white);
        }
        
        .step.completed .step-number {
            background: var(--classic-navy);
            color: var(--classic-white);
        }
        
        .step-label {
            font-size: var(--font-size-sm);
            color: var(--classic-gray);
            text-align: center;
            font-weight: 500;
        }
        
        .step.active .step-label {
            color: var(--classic-navy);
            font-weight: 600;
        }
        
        .step.completed .step-label {
            color: var(--classic-navy);
        }
        
        .wizard-step {
            display: none;
            animation: fadeIn 0.3s ease-in-out;
        }
        
        .wizard-step.active {
            display: block;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .step-title {
            font-family: 'Playfair Display', serif;
            font-size: var(--font-size-xl);
            color: var(--classic-navy);
            margin-bottom: var(--space-2);
            text-align: center;
        }
        
        .step-subtitle {
            color: var(--classic-gray);
            text-align: center;
            margin-bottom: var(--space-6);
            font-size: var(--font-size-sm);
        }
        
        .form-section {
            padding: var(--space-6) var(--space-8);
        }
        
        .wizard-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-4) var(--space-6);
            background: var(--classic-gray-light);
            border-top: 1px solid var(--classic-gray-light);
        }
        
        .btn-nav {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-3) var(--space-6);
            background: var(--classic-white);
            border: 2px solid var(--classic-navy);
            color: var(--classic-navy);
            border-radius: var(--radius-md);
            font-weight: 600;
            transition: all var(--transition-fast);
        }
        
        .btn-nav:hover {
            background: var(--classic-navy);
            color: var(--classic-white);
        }
        
        .btn-nav:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        
        .btn-nav:disabled:hover {
            background: var(--classic-white);
            color: var(--classic-navy);
        }
        
        .step-counter {
            font-weight: 600;
            color: var(--classic-navy);
            font-size: var(--font-size-sm);
        }
        
        .booking-summary {
            background: var(--classic-gray-light);
            border-radius: var(--radius-lg);
            padding: var(--space-6);
            margin-bottom: var(--space-6);
        }
        
        .summary-section {
            margin-bottom: var(--space-4);
        }
        
        .summary-section:last-child {
            margin-bottom: 0;
        }
        
        .summary-section h4 {
            color: var(--classic-navy);
            font-size: var(--font-size-lg);
            margin-bottom: var(--space-3);
            border-bottom: 2px solid var(--classic-gold);
            padding-bottom: var(--space-2);
        }
        
        .summary-section p {
            margin-bottom: var(--space-2);
            color: var(--classic-gray-dark);
        }
        
        .summary-section p:last-child {
            margin-bottom: 0;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: var(--space-6);
            margin-bottom: var(--space-4);
            width: 100%;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .form-row .form-group {
            width: 100%;
        }
        
        .form-row.full {
            grid-template-columns: 1fr;
        }
        
        .form-row.single {
            grid-template-columns: 1fr;
            max-width: 400px;
        }
        
        .form-row.single .form-group {
            width: 100%;
        }
        
        .form-group {
            margin-bottom: 0;
        }
        
        .form-control {
            width: 100%;
            padding: var(--space-3) var(--space-4);
            border: 2px solid var(--classic-gray-light);
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            transition: all var(--transition-fast);
            background: var(--classic-white);
            color: var(--classic-navy);
            box-sizing: border-box;
            margin: 0;
            min-height: 48px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--classic-gold);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
        
        .form-label {
            display: block;
            margin-bottom: var(--space-2);
            font-weight: 600;
            color: var(--classic-navy);
            font-size: var(--font-size-sm);
        }
        
        .pricing-card {
            background: var(--classic-navy);
            color: var(--classic-white);
            border-radius: var(--radius-xl);
            padding: var(--space-8);
            margin-top: var(--space-8);
        }

        /* Room allocation info card */
        .allocation-card {
            background: #ffffff;
            border: 1px solid var(--classic-gray-light);
            border-radius: var(--radius-lg);
            padding: var(--space-6);
            margin-top: var(--space-6);
        }
        .allocation-card h4 { margin-bottom: var(--space-3); color: var(--classic-navy); }
        .allocation-grid { display: grid; grid-template-columns: 1fr; gap: var(--space-4); }
        @media (min-width: 768px) { .allocation-grid { grid-template-columns: 1fr 1fr; } }
        .allocation-section { background: #fafafa; border-radius: var(--radius-md); padding: var(--space-4); border: 1px dashed var(--classic-gray-light); }
        .allocation-section h5 { margin: 0 0 var(--space-2) 0; color: var(--classic-gold); font-weight: 700; }
        .allocation-list { margin: 0; padding-left: var(--space-5); }
        
        .pricing-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--space-3) 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .pricing-item:last-child {
            border-bottom: none;
        }
        
        .pricing-total {
            font-size: var(--font-size-xl);
            font-weight: 700;
            color: var(--classic-gold);
            border-top: 2px solid var(--classic-gold);
            padding-top: var(--space-4);
            margin-top: var(--space-4);
        }
        
        .submit-section {
            text-align: center;
            margin-top: var(--space-12);
            padding-top: var(--space-8);
            border-top: 1px solid var(--classic-gray-light);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            color: var(--classic-gold);
            text-decoration: none;
            font-weight: 600;
            margin-bottom: var(--space-8);
            transition: all var(--transition-fast);
        }
        
        .back-link:hover {
            color: var(--classic-gold-dark);
            transform: translateX(-4px);
        }
        
        .back-link i {
            margin-right: var(--space-2);
        }
        
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: var(--space-6);
            right: var(--space-6);
            z-index: 9999;
            pointer-events: none;
        }
        
        .toast {
            background: var(--classic-white);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            margin-bottom: var(--space-4);
            min-width: 320px;
            opacity: 0;
            transform: translateX(100%);
            transition: all var(--transition-normal);
            border-left: 4px solid var(--classic-gold);
            pointer-events: auto;
        }
        
        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }
        
        .toast.success {
            border-left-color: #10b981;
        }
        
        .toast.error {
            border-left-color: #ef4444;
        }
        
        .toast-header {
            padding: var(--space-4) var(--space-5);
            border-bottom: 1px solid var(--classic-gray-light);
            display: flex;
            align-items: center;
            font-weight: 600;
        }
        
        .toast-body {
            padding: var(--space-4) var(--space-5);
        }
        
        .toast-icon {
            margin-right: var(--space-3);
            font-size: var(--font-size-lg);
        }
        
        .toast-icon.success {
            color: #10b981;
        }
        
        .toast-icon.error {
            color: #ef4444;
        }
        
        .toast-close {
            background: none;
            border: none;
            font-size: 18px;
            font-weight: bold;
            color: var(--classic-gray);
            cursor: pointer;
            padding: 0;
            margin-left: auto;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all var(--transition-fast);
        }
        
        .toast-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: var(--classic-navy);
        }
        
        /* Desktop Optimization */
        @media (min-width: 769px) {
            .form-row {
                gap: var(--space-8);
            }
            
            .form-control {
                font-size: var(--font-size-base);
                padding: var(--space-4) var(--space-5);
            }
            
            .form-section {
                padding: var(--space-8) var(--space-10);
            }
        }
        
        /* Tablet & Mobile Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: var(--space-12) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-2xl);
            }
            
            .hero-subtitle {
                font-size: var(--font-size-sm);
                padding: 0 var(--space-4);
            }
            
            .reservation-form {
                margin: calc(var(--space-12) * -1) var(--space-4) 0;
                border-radius: var(--radius-lg);
            }
            
            .form-section {
                padding: var(--space-6);
            }
            
            .step-indicator {
                margin-bottom: var(--space-4);
                padding: 0;
            }
            
            .step-number {
                width: 36px;
                height: 36px;
                font-size: var(--font-size-sm);
            }
            
            .step-label {
                font-size: 10px;
            }
            
            .step-title {
                font-size: var(--font-size-xl);
            }
            
            .step-subtitle {
                font-size: var(--font-size-sm);
            }
            
            .wizard-navigation {
                padding: var(--space-4);
                flex-direction: row;
                flex-wrap: wrap;
                gap: var(--space-3);
            }
            
            .btn-nav {
                flex: 1;
                min-width: 120px;
                justify-content: center;
                padding: var(--space-3) var(--space-4);
                font-size: var(--font-size-sm);
            }
            
            .step-counter {
                width: 100%;
                text-align: center;
                order: -1;
                margin-bottom: var(--space-2);
            }
            
            /* Stack form fields vertically on tablet */
            .form-row {
                grid-template-columns: 1fr;
                gap: var(--space-4);
                max-width: 100%;
            }
            
            .form-row.single {
                max-width: 100%;
            }
            
            .form-group {
                width: 100%;
            }
            
            .form-control {
                width: 100%;
                font-size: var(--font-size-base);
                padding: var(--space-3) var(--space-4);
            }
            
            .pricing-card {
                padding: var(--space-6);
            }
            
            .section-title {
                font-size: var(--font-size-xl);
            }
            
            .btn-lg {
                width: 100%;
                padding: var(--space-4) var(--space-6);
                font-size: var(--font-size-base);
                min-height: 52px;
            }
            
            .booking-summary {
                padding: var(--space-5);
            }
            
            .summary-section h4 {
                font-size: var(--font-size-base);
            }
            
            .summary-section p {
                font-size: var(--font-size-sm);
            }
        }
        
        /* Mobile Phone Optimization */
        @media (max-width: 480px) {
            .hero-section {
                padding: var(--space-10) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-xl);
                margin-bottom: var(--space-3);
            }
            
            .hero-subtitle {
                font-size: 13px;
                padding: 0 var(--space-3);
                line-height: 1.4;
            }
            
            .reservation-form {
                margin: calc(var(--space-10) * -1) var(--space-3) 0;
                border-radius: var(--radius-md);
            }
            
            .form-section {
                padding: var(--space-5);
            }
            
            .step-title {
                font-size: var(--font-size-lg);
                margin-bottom: var(--space-3);
            }
            
            .step-subtitle {
                font-size: 13px;
                margin-bottom: var(--space-5);
            }
            
            .step-number {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
            
            .step-label {
                font-size: 9px;
            }
            
            .form-group {
                margin-bottom: 0;
            }
            
            .form-row {
                gap: var(--space-4);
            }
            
            .form-control {
                font-size: 16px; /* Prevents iOS zoom on focus */
                padding: var(--space-3) var(--space-4);
                width: 100%;
                min-height: 50px;
            }
            
            .form-label {
                font-size: var(--font-size-sm);
                margin-bottom: var(--space-2);
            }
            
            .wizard-navigation {
                padding: var(--space-3);
                gap: var(--space-2);
            }
            
            .btn-nav {
                font-size: 13px;
                padding: var(--space-3);
                min-height: 48px;
            }
            
            .step-counter {
                font-size: var(--font-size-sm);
            }
            
            .pricing-card {
                padding: var(--space-5);
                margin-top: var(--space-6);
            }
            
            .pricing-card h3 {
                font-size: var(--font-size-lg);
                margin-bottom: var(--space-4);
            }
            
            .pricing-item {
                padding: var(--space-2) 0;
                font-size: var(--font-size-sm);
            }
            
            .pricing-total {
                font-size: var(--font-size-lg);
                padding-top: var(--space-3);
                margin-top: var(--space-3);
            }
            
            .btn-lg {
                width: 100%;
                padding: var(--space-4) var(--space-5);
                font-size: var(--font-size-base);
                min-height: 52px;
            }
            
            .submit-section {
                margin-top: var(--space-8);
                padding-top: var(--space-6);
            }
            
            .booking-summary {
                padding: var(--space-4);
                margin-bottom: var(--space-5);
            }
            
            .summary-section {
                margin-bottom: var(--space-4);
            }
            
            .summary-section h4 {
                font-size: var(--font-size-base);
                margin-bottom: var(--space-2);
            }
            
            .summary-section p {
                font-size: 13px;
                margin-bottom: var(--space-2);
            }
            
            .back-link {
                font-size: var(--font-size-sm);
                margin-bottom: var(--space-6);
            }
            
            .toast {
                min-width: auto;
                margin: 0 var(--space-2) var(--space-2) 0;
            }
            
            .toast-header {
                padding: var(--space-3) var(--space-4);
                font-size: var(--font-size-sm);
            }
            
            .toast-body {
                padding: var(--space-3) var(--space-4);
                font-size: 13px;
            }
        }
        
        /* Extra small devices (iPhone SE, small phones) */
        @media (max-width: 360px) {
            .hero-section {
                padding: var(--space-8) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-lg);
            }
            
            .hero-subtitle {
                font-size: 12px;
            }
            
            .reservation-form {
                margin: calc(var(--space-8) * -1) var(--space-2) 0;
            }
            
            .form-section {
                padding: var(--space-4);
            }
            
            .step-indicator {
                padding: 0;
            }
            
            .step-number {
                width: 28px;
                height: 28px;
                font-size: 11px;
            }
            
            .step-label {
                font-size: 8px;
            }
            
            .step-title {
                font-size: var(--font-size-base);
            }
            
            .step-subtitle {
                font-size: 12px;
            }
            
            .form-row {
                gap: var(--space-3);
            }
            
            .form-control {
                padding: var(--space-3);
                min-height: 48px;
            }
            
            .wizard-navigation {
                padding: var(--space-3);
            }
            
            .btn-nav {
                font-size: 12px;
                padding: var(--space-2) var(--space-3);
            }
            
            .pricing-card {
                padding: var(--space-4);
            }
            
            .pricing-card h3 {
                font-size: var(--font-size-base);
            }
            
            .btn-lg {
                min-height: 50px;
                font-size: var(--font-size-sm);
            }
        }
        
        /* Mobile-specific improvements */
        @media (max-width: 768px) {
            /* Improve touch targets */
            .form-control, .btn {
                touch-action: manipulation;
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0.1);
            }
            
            /* Improve select dropdowns on mobile */
            .form-select {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-size: 16px 16px;
                background-position: right 12px center;
                padding-right: var(--space-10);
            }
            
            /* Improve toast positioning on mobile */
            .toast-container {
                top: var(--space-4);
                right: var(--space-3);
                left: var(--space-3);
            }
            
            .toast {
                min-width: auto;
                width: 100%;
                margin: 0 0 var(--space-3) 0;
            }
            
            /* Prevent horizontal scroll */
            .reservation-form {
                overflow-x: hidden;
            }
        }
        
        /* Landscape mobile orientation */
        @media (max-width: 768px) and (orientation: landscape) {
            .hero-section {
                padding: var(--space-6) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-xl);
                margin-bottom: var(--space-2);
            }
            
            .hero-subtitle {
                font-size: var(--font-size-sm);
            }
            
            .reservation-form {
                margin: calc(var(--space-6) * -1) var(--space-4) 0;
            }
            
            .form-section {
                padding: var(--space-5);
            }
            
            .step-indicator {
                margin-bottom: var(--space-3);
            }
            
            /* Use 2 columns in landscape for better space utilization */
            .form-row {
                grid-template-columns: repeat(2, 1fr);
                gap: var(--space-4);
            }
            
            .form-row.single {
                grid-template-columns: 1fr;
                max-width: 400px;
            }
        }
        
        /* Large Desktop Optimization */
        @media (min-width: 1200px) {
            .reservation-form {
                max-width: 800px;
            }
            
            .form-row {
                max-width: 700px;
                gap: var(--space-10);
            }
            
            .form-row.single {
                max-width: 450px;
            }
            
            .form-control {
                padding: var(--space-4) var(--space-6);
                font-size: var(--font-size-lg);
            }
            
            .form-label {
                font-size: var(--font-size-base);
            }
        }
    </style>
</head>
<body>
    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Skip Link for Accessibility -->
        
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <h1 class="hero-title">Make a Reservation</h1>
                <p class="hero-subtitle">Experience luxury and comfort at RansHotel, located in the beautiful Tsito, Ghana</p>
            </div>
        </section>
    
        <!-- Main Content -->
        <div id="main-content">
        <div class="container">
            <div class="reservation-form">
                <div class="wizard-container">
                    <!-- Progress Bar -->
                    <div class="progress-bar">
                        <div class="progress-fill" id="progressFill"></div>
                    </div>
                    
                    <!-- Step Indicator -->
                    <div class="step-indicator">
                        <div class="step active" data-step="1">
                            <div class="step-number">1</div>
                            <div class="step-label">Personal Info</div>
                        </div>
                        <div class="step" data-step="2">
                            <div class="step-number">2</div>
                            <div class="step-label">Room Details</div>
                        </div>
                        <div class="step" data-step="3">
                            <div class="step-number">3</div>
                            <div class="step-label">Review & Book</div>
                        </div>
                    </div>
                    
                    <form method="post" id="reservationForm" novalidate>
                        <!-- Step 1: Personal Information -->
                        <div class="wizard-step active" data-step="1">
                            <div class="form-section">
                                <h2 class="step-title">Personal Information</h2>
                                <p class="step-subtitle">Tell us about yourself to get started</p>
                                
                                <div class="form-row single">
                                    <div class="form-group">
                                        <label for="title" class="form-label">Title *</label>
                                        <select name="title" id="title" class="form-control form-select" required>
                                            <option value="">Select Title</option>
                                            <option value="Dr.">Dr.</option>
                                            <option value="Miss.">Miss.</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Prof.">Prof.</option>
                                            <option value="Rev.">Rev.</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="fname" class="form-label">First Name *</label>
                                        <input type="text" name="fname" id="fname" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="lname" class="form-label">Last Name *</label>
                                        <input type="text" name="lname" id="lname" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="phone" class="form-label">Phone Number *</label>
                                        <input type="tel" name="phone" id="phone" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="national" class="form-label">Nationality</label>
                                        <input type="text" name="national" id="national" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label for="country" class="form-label">Country</label>
                                        <input type="text" name="country" id="country" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Step 2: Room Details -->
                        <div class="wizard-step" data-step="2">
                            <div class="form-section">
                                <h2 class="step-title">Room & Stay Details</h2>
                                <p class="step-subtitle">Choose your preferred room and stay preferences</p>
                                
                                <div class="form-row single">
                                    <div class="form-group">
                                        <label for="troom" class="form-label">Room Type *</label>
                                        <select name="troom" id="troom" class="form-control form-select" required onchange="updatePricing()">
                                            <option value="">Select Room Type</option>
                                            <?php
                                            $roomPricing = PricingHelper::getAllRoomPricing($con);
                                            $roomTypes = [];
                                            
                                            foreach ($roomPricing as $price) {
                                                if (!isset($roomTypes[$price['room_type']]) || $price['price_per_night'] < $roomTypes[$price['room_type']]) {
                                                    $roomTypes[$price['room_type']] = $price['price_per_night'];
                                                }
                                            }
                                            
                                            foreach ($roomTypes as $roomType => $minPrice) {
                                                echo "<option value=\"$roomType\" data-price=\"$minPrice\">$roomType - ₵" . number_format($minPrice, 0) . "/night</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="bed" class="form-label">Bedding Type *</label>
                                        <select name="bed" id="bed" class="form-control form-select" required>
                                            <option value="">Select Bedding</option>
                                            <option value="Single">Single</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="nroom" class="form-label">Number of Rooms *</label>
                                        <select name="nroom" id="nroom" class="form-control form-select" required onchange="updatePricing()">
                                            <option value="">Select Rooms</option>
                                            <?php for($i = 1; $i <= 10; $i++): ?>
                                                <option value="<?php echo $i; ?>"><?php echo $i; ?> Room<?php echo $i > 1 ? 's' : ''; ?></option>
                                            <?php endfor; ?>
                                        </select>
                                        <small class="text-gray-500 text-xs mt-1 block">You can book up to 10 rooms at once</small>
                                    </div>
                                </div>
                                
                                <?php if ($ENABLE_MEAL_PLAN): ?>
                                <div class="form-row single">
                                    <div class="form-group">
                                        <label for="meal" class="form-label">Meal Plan *</label>
                                        <select name="meal" id="meal" class="form-control form-select" required onchange="updatePricing()">
                                            <option value="">Select Meal Plan</option>
                                            <?php
                                            $mealPricing = PricingHelper::getAllMealPricing($con);
                                            
                                            foreach ($mealPricing as $meal) {
                                                $price = number_format($meal['price_per_person_per_day'], 0);
                                                echo "<option value=\"{$meal['meal_plan']}\" data-price=\"{$meal['price_per_person_per_day']}\">{$meal['meal_plan']} - ₵{$price}/person/day</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <?php else: ?>
                                <!-- Meal Plan is disabled - automatically set to 'Room only' -->
                                <input type="hidden" name="meal" id="meal" value="Room only" data-price="0">
                                <?php endif; ?>
                                
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="cin" class="form-label">Check-in Date *</label>
                                        <input type="date" name="cin" id="cin" class="form-control" required min="<?php echo date('Y-m-d'); ?>" onchange="updatePricing()">
                                    </div>
                                    <div class="form-group">
                                        <label for="cout" class="form-label">Check-out Date *</label>
                                        <input type="date" name="cout" id="cout" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" onchange="updatePricing()">
                                    </div>
                                </div>
                                
                                
                            </div>
                        </div>
                        
                        <!-- Step 3: Review & Book -->
                        <div class="wizard-step" data-step="3">
                            <div class="form-section">
                                <h2 class="step-title">Review & Confirm</h2>
                                <p class="step-subtitle">Review your booking details and complete your reservation</p>
                                
                                <!-- Booking Summary -->
                                <div class="booking-summary" id="bookingSummary" style="display: none;">
                                    <div class="summary-section">
                                        <h4>Personal Information</h4>
                                        <p><strong>Name:</strong> <span id="summaryName"></span></p>
                                        <p><strong>Email:</strong> <span id="summaryEmail"></span></p>
                                        <p><strong>Phone:</strong> <span id="summaryPhone"></span></p>
                                    </div>
                                    
                                    <div class="summary-section">
                                        <h4>Stay Details</h4>
                                        <p><strong>Room Type:</strong> <span id="summaryRoom"></span> <span id="summaryRoomsCount"></span></p>
                                        <p><strong>Check-in:</strong> <span id="summaryCheckIn"></span></p>
                                        <p><strong>Check-out:</strong> <span id="summaryCheckOut"></span></p>
                                        <p><strong>Duration:</strong> <span id="summaryDuration"></span> nights</p>
                                    </div>
                                </div>
                                
                                <!-- Pricing Summary -->
                                <div class="pricing-card" id="pricingSummary" style="display: none;">
                                    <h3 style="color: var(--classic-gold); margin-bottom: var(--space-6); text-align: center;">Pricing Summary</h3>
                                    
                                    <div id="numberOfRoomsDisplay" style="margin-bottom: var(--space-4); padding: var(--space-3); background: rgba(255,255,255,0.1); border-radius: var(--radius-md); text-align: center; display: none;">
                                        <span style="font-weight: 600; color: var(--classic-gold);">Booking <span id="numberOfRooms">1</span> Room<span id="roomsPlural">s</span></span>
                                    </div>
                                    
                                    <div class="pricing-item">
                                        <span>Room Cost <span id="roomCostLabel">(per room)</span>:</span>
                                        <span id="roomCost">₵0.00</span>
                                    </div>
                                    <?php if ($ENABLE_MEAL_PLAN): ?>
                                    <div class="pricing-item">
                                        <span>Meal Cost <span id="mealCostLabel">(per room)</span>:</span>
                                        <span id="mealCost">₵0.00</span>
                                    </div>
                                    <?php endif; ?>
                                    <div class="pricing-item">
                                        <span>Subtotal:</span>
                                        <span id="subtotal">₵0.00</span>
                                    </div>
                                    <div class="pricing-item">
                                        <span>Tax (15%):</span>
                                        <span id="tax">₵0.00</span>
                                    </div>
                                    <div class="pricing-item">
                                        <span>Service Charge (10%):</span>
                                        <span id="serviceCharge">₵0.00</span>
                                    </div>
                                    <div class="pricing-total">
                                        <span>Total Amount <span id="totalAmountLabel">(all rooms)</span>:</span>
                                        <span id="totalAmount">₵0.00</span>
                                    </div>
                                </div>
                                
                                <!-- Submit Section -->
                                <div class="submit-section">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fa fa-check" style="margin-right: var(--space-2);"></i>
                                        Complete Reservation
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Wizard Navigation -->
                    <div class="wizard-navigation">
                        <button type="button" class="btn-nav" id="prevBtn" onclick="changeStep(-1)" disabled>
                            <i class="fa fa-arrow-left"></i>
                            Previous
                        </button>
                        
                        <div class="step-counter">
                            <span id="currentStep">1</span> of <span>3</span>
                        </div>
                        
                        <button type="button" class="btn-nav" id="nextBtn" onclick="changeStep(1)">
                            Next
                            <i class="fa fa-arrow-right"></i>
                        </button>
                        
                    </div>
                </div>
            </div>
        </div>
        </div>
    </main>
    
    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>
    
    <!-- Scripts -->
    <script>
        // Wizard functionality
        let currentStep = 1;
        const totalSteps = 3;
        
        function changeStep(direction) {
            const newStep = currentStep + direction;
            
            if (newStep < 1 || newStep > totalSteps) return;
            
            // Validate current step before proceeding
            if (direction > 0 && !validateCurrentStep()) {
                return;
            }
            
            // Hide current step
            document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.remove('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.remove('active');
            
            // Update current step
            currentStep = newStep;
            
            // Show new step
            document.querySelector(`.wizard-step[data-step="${currentStep}"]`).classList.add('active');
            document.querySelector(`.step[data-step="${currentStep}"]`).classList.add('active');
            
            // Update progress bar
            const progressFill = document.getElementById('progressFill');
            progressFill.style.width = `${(currentStep / totalSteps) * 100}%`;
            
            // Update navigation buttons
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const currentStepSpan = document.getElementById('currentStep');
            
            prevBtn.disabled = currentStep === 1;
            currentStepSpan.textContent = currentStep;
            
            if (currentStep === totalSteps) {
                nextBtn.style.display = 'none';
                updateBookingSummary();
            } else {
                nextBtn.style.display = 'flex';
                nextBtn.innerHTML = 'Next <i class="fa fa-arrow-right"></i>';
            }
            
            // Mark previous steps as completed
            for (let i = 1; i < currentStep; i++) {
                document.querySelector(`.step[data-step="${i}"]`).classList.add('completed');
            }
            
            // Scroll to top of form
            document.querySelector('.reservation-form').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
        
        function validateCurrentStep() {
            const currentStepElement = document.querySelector(`.wizard-step[data-step="${currentStep}"]`);
            const requiredFields = currentStepElement.querySelectorAll('[required]');
            
            for (let field of requiredFields) {
                if (!field.value.trim()) {
                    field.focus();
                    showToast(`Please fill in ${field.previousElementSibling.textContent.replace('*', '').trim()}`, 'error');
                    return false;
                }
            }
            
            // Special validation for step 2
            if (currentStep === 2) {
                const checkIn = document.getElementById('cin').value;
                const checkOut = document.getElementById('cout').value;
                
                if (checkIn && checkOut && new Date(checkOut) <= new Date(checkIn)) {
                    showToast('Check-out date must be after check-in date', 'error');
                    return false;
                }
            }
            
            return true;
        }
        
        function updateBookingSummary() {
            const title = document.getElementById('title').value;
            const fname = document.getElementById('fname').value;
            const lname = document.getElementById('lname').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            const room = document.getElementById('troom').value;
            const nrooms = parseInt(document.getElementById('nroom').value) || 1;
            const checkIn = document.getElementById('cin').value;
            const checkOut = document.getElementById('cout').value;
            
            if (title && fname && lname && email && phone && room && checkIn && checkOut) {
                document.getElementById('summaryName').textContent = `${title} ${fname} ${lname}`;
                document.getElementById('summaryEmail').textContent = email;
                document.getElementById('summaryPhone').textContent = phone;
                document.getElementById('summaryRoom').textContent = room;
                
                // Show number of rooms if more than 1
                const summaryRoomsCount = document.getElementById('summaryRoomsCount');
                if (nrooms > 1) {
                    summaryRoomsCount.textContent = `× ${nrooms} rooms`;
                    summaryRoomsCount.style.color = 'var(--classic-gold)';
                    summaryRoomsCount.style.fontWeight = '600';
                } else {
                    summaryRoomsCount.textContent = '';
                }
                
                document.getElementById('summaryCheckIn').textContent = checkIn;
                document.getElementById('summaryCheckOut').textContent = checkOut;
                
                const days = Math.ceil((new Date(checkOut) - new Date(checkIn)) / (1000 * 60 * 60 * 24));
                document.getElementById('summaryDuration').textContent = days;
                
                document.getElementById('bookingSummary').style.display = 'block';
                updatePricing();
            }
        }
        
        // Date validation and pricing calculator
        document.addEventListener('DOMContentLoaded', function() {
            const cinInput = document.getElementById('cin');
            const coutInput = document.getElementById('cout');
            
            cinInput.addEventListener('change', function() {
                const cinDate = new Date(this.value);
                const minCoutDate = new Date(cinDate);
                minCoutDate.setDate(minCoutDate.getDate() + 1);
                coutInput.min = minCoutDate.toISOString().split('T')[0];
                updatePricing();
            });
            
            coutInput.addEventListener('change', function() {
                const coutDate = new Date(this.value);
                const cinDate = new Date(cinInput.value);
                
                if (coutDate <= cinDate) {
                    showToast('Check-out date must be after check-in date', 'error');
                    this.value = '';
                } else {
                    updatePricing();
                }
            });
            
        // Form validation
        const form = document.getElementById('reservationForm');
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                showToast('Please fill in all required fields', 'error');
                
                // Scroll to first invalid field
                const firstInvalid = form.querySelector(':invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            } else {
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin" style="margin-right: var(--space-2);"></i>Processing...';
                submitBtn.disabled = true;
                
                // Re-enable button after 10 seconds as fallback
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }, 10000);
            }
            form.classList.add('was-validated');
        });
        });
        
        function updatePricing() {
            const roomSelect = document.getElementById('troom');
            const mealSelect = document.getElementById('meal');
            const checkInInput = document.getElementById('cin');
            const checkOutInput = document.getElementById('cout');
            const nroomSelect = document.getElementById('nroom');
            const pricingSummary = document.getElementById('pricingSummary');
            
            const roomPrice = parseFloat(roomSelect.options[roomSelect.selectedIndex].dataset.price) || 0;
            // Get meal price from data attribute or hidden input
            const mealPrice = mealSelect ? (parseFloat(mealSelect.options?.[mealSelect.selectedIndex]?.dataset?.price) || parseFloat(mealSelect.dataset?.price) || 0) : 0;
            const nrooms = parseInt(nroomSelect.value) || 1;
            
            let days = 0;
            if (checkInInput.value && checkOutInput.value) {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);
                days = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            }
            
            if (roomPrice > 0 && days > 0) {
                // Calculate per-room pricing
                const roomTotalPerRoom = roomPrice * days;
                const mealTotalPerRoom = mealPrice * days;
                const subtotalPerRoom = roomTotalPerRoom + mealTotalPerRoom;
                const taxPerRoom = subtotalPerRoom * 0.15;
                const serviceChargePerRoom = subtotalPerRoom * 0.10;
                const totalPerRoom = subtotalPerRoom + taxPerRoom + serviceChargePerRoom;
                
                // Calculate totals for all rooms
                const roomTotal = roomTotalPerRoom * nrooms;
                const mealTotal = mealTotalPerRoom * nrooms;
                const subtotal = subtotalPerRoom * nrooms;
                const tax = taxPerRoom * nrooms;
                const serviceCharge = serviceChargePerRoom * nrooms;
                const total = totalPerRoom * nrooms;
                
                // Update display based on number of rooms
                const numberOfRoomsDisplay = document.getElementById('numberOfRoomsDisplay');
                const numberOfRoomsSpan = document.getElementById('numberOfRooms');
                const roomsPluralSpan = document.getElementById('roomsPlural');
                const roomCostLabel = document.getElementById('roomCostLabel');
                const mealCostLabel = document.getElementById('mealCostLabel');
                const totalAmountLabel = document.getElementById('totalAmountLabel');
                
                if (nrooms > 1) {
                    // Show number of rooms
                    numberOfRoomsDisplay.style.display = 'block';
                    numberOfRoomsSpan.textContent = nrooms;
                    roomsPluralSpan.textContent = 's';
                    
                    // Show per-room pricing
                    document.getElementById('roomCost').textContent = '₵' + roomTotalPerRoom.toFixed(2) + ' × ' + nrooms + ' = ₵' + roomTotal.toFixed(2);
                    roomCostLabel.textContent = '(per room)';
                    
                    // Only update meal cost if the element exists (when meal plan is enabled)
                    const mealCostElement = document.getElementById('mealCost');
                    if (mealCostElement) {
                        mealCostElement.textContent = '₵' + mealTotalPerRoom.toFixed(2) + ' × ' + nrooms + ' = ₵' + mealTotal.toFixed(2);
                        mealCostLabel.textContent = '(per room)';
                    }
                    
                    totalAmountLabel.textContent = '(all ' + nrooms + ' rooms)';
                } else {
                    // Hide number of rooms display for single room
                    numberOfRoomsDisplay.style.display = 'none';
                    roomsPluralSpan.textContent = '';
                    
                    // Show single room pricing
                    document.getElementById('roomCost').textContent = '₵' + roomTotal.toFixed(2);
                    roomCostLabel.textContent = '';
                    
                    const mealCostElement = document.getElementById('mealCost');
                    if (mealCostElement) {
                        mealCostElement.textContent = '₵' + mealTotal.toFixed(2);
                        mealCostLabel.textContent = '';
                    }
                    
                    totalAmountLabel.textContent = '';
                }
                
                document.getElementById('subtotal').textContent = '₵' + subtotal.toFixed(2);
                document.getElementById('tax').textContent = '₵' + tax.toFixed(2);
                document.getElementById('serviceCharge').textContent = '₵' + serviceCharge.toFixed(2);
                document.getElementById('totalAmount').textContent = '₵' + total.toFixed(2);
                
                pricingSummary.style.display = 'block';
            } else {
                pricingSummary.style.display = 'none';
            }
        }
        
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toastContainer');
            
            if (!toastContainer) {
                return;
            }
            
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const iconClass = type === 'success' ? 'success' : 'error';
            const title = type === 'success' ? 'Booking Confirmed!' : 'Booking Error!';
            
            // Add close button
            const closeButton = `<button type="button" class="toast-close" onclick="closeToast(this.parentElement.parentElement)">&times;</button>`;
            
            toast.innerHTML = `
                <div class="toast-header">
                    <i class="fa ${icon} toast-icon ${iconClass}"></i>
                    <strong>${title}</strong>
                    ${closeButton}
                </div>
                <div class="toast-body">
                    ${message.replace(/\n/g, '<br>')}
                </div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Show toast with animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            // Auto-hide toast after 8 seconds (longer for success messages)
            const hideDelay = type === 'success' ? 8000 : 5000;
            setTimeout(() => {
                closeToast(toast);
            }, hideDelay);
            
            // Add haptic feedback on mobile
            if (navigator.vibrate) {
                navigator.vibrate(type === 'success' ? [100, 50, 100] : [200]);
            }
        }
        
        function closeToast(toast) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentElement && toast.parentElement.contains(toast)) {
                    toast.parentElement.removeChild(toast);
                }
            }, 300);
        }
        
        // Mobile-specific enhancements
        function initMobileEnhancements() {
            // Prevent zoom on input focus (iOS)
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    if (window.innerWidth <= 768) {
                        const viewport = document.querySelector('meta[name="viewport"]');
                        if (viewport) {
                            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
                        }
                    }
                });
                
                input.addEventListener('blur', function() {
                    if (window.innerWidth <= 768) {
                        const viewport = document.querySelector('meta[name="viewport"]');
                        if (viewport) {
                            viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
                        }
                    }
                });
            });
            
            // Add touch feedback to buttons
            const buttons = document.querySelectorAll('.btn');
            buttons.forEach(button => {
                button.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.98)';
                });
                
                button.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                });
            });
            
            // Improve form field focus on mobile
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    // Scroll to ensure input is visible
                    setTimeout(() => {
                        this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 300);
                });
            });
        }
        
        // Initialize mobile enhancements
        initMobileEnhancements();
        
        
        // Show success/error messages as toasts
        <?php if ($success_message): ?>
            // Show success toast with booking details
            showToast('<?php echo addslashes($success_message); ?>', 'success');
            
            // Clear form after successful booking
            setTimeout(() => {
                document.getElementById('reservationForm').reset();
                document.getElementById('pricingSummary').style.display = 'none';
                
                // Scroll to top after successful booking
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 2000);
        <?php endif; ?>
        
        <?php if ($error_message): ?>
            showToast('<?php echo addslashes($error_message); ?>', 'error');
        <?php endif; ?>
    </script>
<?php endUnifiedAdminPage(); ?>
