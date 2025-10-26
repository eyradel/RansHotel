<?php
include('db.php');
require_once 'includes/notification_manager.php';
require_once 'includes/pricing_helper.php';
require_once 'includes/phpmailer_email_system.php';

// Initialize pricing tables if they don't exist
PricingHelper::initializePricingTables($con);

// Handle form submission
$success_message = '';
$error_message = '';

if ($_POST) {
    $title = $_POST['title'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $national = $_POST['national'];
    $country = $_POST['country'];
    $phone = $_POST['phone'];
    $troom = $_POST['troom'];
    $bed = $_POST['bed'];
    $nroom = $_POST['nroom'];
    $meal = $_POST['meal'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];
    
    // Calculate days
    $checkin = new DateTime($cin);
    $checkout = new DateTime($cout);
    $days = $checkin->diff($checkout)->days;
    
    // Get pricing
    $roomPrice = PricingHelper::getRoomPrice($troom, $bed, $con);
    $mealPrice = PricingHelper::getMealPrice($meal, $con);
    
    // Calculate totals
    $roomTotal = $roomPrice * $days * $nroom;
    $mealTotal = $mealPrice * $days * $nroom;
    $subtotal = $roomTotal + $mealTotal;
    $tax = $subtotal * 0.15; // 15% tax
    $serviceCharge = $subtotal * 0.10; // 10% service charge
    $totalAmount = $subtotal + $tax + $serviceCharge;
    
    // Insert into database
    $sql = "INSERT INTO `roombook` (`Title`, `FName`, `LName`, `Email`, `National`, `Country`, `Phone`, `TRoom`, `Bed`, `NRoom`, `Meal`, `cin`, `cout`, `stat`, `nodays`, `room_price`, `meal_price`, `total_amount`, `tax_amount`, `service_charge`, `final_amount`, `currency`, `payment_status`) VALUES ('$title', '$fname', '$lname', '$email', '$national', '$country', '$phone', '$troom', '$bed', '$nroom', '$meal', '$cin', '$cout', 'Conform', '$days', '$roomPrice', '$mealPrice', '$subtotal', '$tax', '$serviceCharge', '$totalAmount', 'GHS', 'pending')";
    
    if (mysqli_query($con, $sql)) {
        $bookingId = mysqli_insert_id($con);
        
        // Send notifications
        $notificationManager = new NotificationManager();
        $notificationResults = $notificationManager->sendBookingNotifications([
            'booking_id' => $bookingId,
            'customerName' => "$title $fname $lname",
            'email' => $email,
            'phone' => $phone,
            'roomType' => $troom,
            'checkIn' => $cin,
            'checkOut' => $cout,
            'bookingId' => $bookingId,
            'mealPlan' => $meal,
            'nationality' => $national,
            'country' => $country,
            'totalAmount' => $totalAmount
        ]);
        
        // Get notification status
        $notificationStatus = $notificationManager->getNotificationStatus($notificationResults);
        
        $success_message = "Reservation submitted successfully! Booking ID: $bookingId";
        
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
    } else {
        $error_message = "Error: " . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation - RansHotel</title>
    <meta name="description" content="Book your stay at RansHotel - Located in Tsito, Ghana">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Georgia:wght@400;600&display=swap" rel="stylesheet">
    
    <!-- Classic Design System -->
    <link href="../css/classic-design-system.css" rel="stylesheet">
    
    <style>
        /* Page-specific styles */
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
            max-width: 700px;
            overflow: hidden;
        }
        
        /* Onboarding Wizard Styles */
        .wizard-container {
            position: relative;
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
            justify-content: space-between;
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
            display: flex;
            flex-wrap: wrap;
            gap: var(--space-6);
            margin-bottom: var(--space-4);
            justify-content: center;
            align-items: flex-start;
        }
        
        .form-row .form-group {
            flex: 0 0 auto;
            min-width: 280px;
            max-width: 320px;
        }
        
        .form-row.full {
            justify-content: center;
        }
        
        .form-row.single {
            justify-content: center;
        }
        
        .form-row.single .form-group {
            max-width: 400px;
        }
        
        .pricing-card {
            background: var(--classic-navy);
            color: var(--classic-white);
            border-radius: var(--radius-xl);
            padding: var(--space-8);
            margin-top: var(--space-8);
        }
        
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
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-section {
                padding: var(--space-16) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-3xl);
            }
            
            .hero-subtitle {
                font-size: var(--font-size-base);
                padding: 0 var(--space-4);
            }
            
            .reservation-form {
                margin: calc(var(--space-16) * -1) var(--space-4) 0;
                border-radius: var(--radius-lg);
            }
            
            .form-section {
                padding: var(--space-6);
            }
            
            .step-indicator {
                margin-bottom: var(--space-6);
            }
            
            .step-number {
                width: 32px;
                height: 32px;
                font-size: var(--font-size-sm);
            }
            
            .step-label {
                font-size: var(--font-size-xs);
            }
            
            .wizard-navigation {
                padding: var(--space-4) var(--space-6);
                flex-direction: column;
                gap: var(--space-4);
            }
            
            .btn-nav {
                width: 100%;
                justify-content: center;
            }
            
            .step-counter {
                order: -1;
            }
            
            .form-row {
                flex-direction: column;
                align-items: center;
                gap: var(--space-4);
            }
            
            .form-row .form-group {
                max-width: 100%;
                min-width: auto;
            }
            
            .pricing-card {
                padding: var(--space-6);
            }
            
            .section-title {
                font-size: var(--font-size-xl);
            }
            
            .btn-lg {
                padding: var(--space-3) var(--space-6);
                font-size: var(--font-size-base);
                min-height: 48px;
            }
        }
        
        @media (max-width: 480px) {
            .hero-section {
                padding: var(--space-12) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-2xl);
                margin-bottom: var(--space-3);
            }
            
            .hero-subtitle {
                font-size: var(--font-size-sm);
                padding: 0 var(--space-3);
            }
            
            .reservation-form {
                margin: calc(var(--space-12) * -1) var(--space-3) 0;
                border-radius: var(--radius-md);
            }
            
            .form-section {
                padding: var(--space-4);
            }
            
            .section-title {
                font-size: var(--font-size-lg);
                margin-bottom: var(--space-6);
            }
            
            .form-group {
                margin-bottom: var(--space-4);
            }
            
            .form-control {
                font-size: var(--font-size-sm);
                padding: var(--space-3) var(--space-4);
                max-width: 100%;
            }
            
            .form-label {
                font-size: var(--font-size-xs);
            }
            
            .pricing-card {
                padding: var(--space-4);
                margin-top: var(--space-6);
            }
            
            .pricing-item {
                padding: var(--space-2) 0;
                font-size: var(--font-size-sm);
            }
            
            .pricing-total {
                font-size: var(--font-size-lg);
            }
            
            .btn-lg {
                width: 100%;
                padding: var(--space-3) var(--space-4);
                font-size: var(--font-size-sm);
                min-height: 44px;
            }
            
            .back-link {
                font-size: var(--font-size-sm);
                margin-bottom: var(--space-6);
            }
            
            .toast {
                min-width: 280px;
                margin: 0 var(--space-3) var(--space-3) 0;
            }
            
            .toast-header {
                padding: var(--space-3) var(--space-4);
            }
            
            .toast-body {
                padding: var(--space-3) var(--space-4);
                font-size: var(--font-size-sm);
            }
        }
        
        /* Extra small devices */
        @media (max-width: 360px) {
            .hero-section {
                padding: var(--space-8) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-xl);
            }
            
            .reservation-form {
                margin: calc(var(--space-8) * -1) var(--space-2) 0;
            }
            
            .form-section {
                padding: var(--space-4);
            }
            
            .pricing-card {
                padding: var(--space-3);
            }
        }
        
        /* Mobile-specific improvements */
        @media (max-width: 768px) {
            /* Improve touch targets */
            .form-control, .btn {
                min-height: 48px;
                touch-action: manipulation;
            }
            
            /* Better spacing for mobile */
        .form-group {
            margin-bottom: var(--space-3);
        }
        
        .form-control {
            width: 100%;
            max-width: 280px;
            padding: var(--space-3) var(--space-4);
            border: 2px solid var(--classic-gray-light);
            border-radius: var(--radius-md);
            font-size: var(--font-size-base);
            transition: all var(--transition-fast);
            background: var(--classic-white);
            color: var(--classic-navy);
            box-sizing: border-box;
            margin: 0;
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
            
            /* Improve select dropdowns on mobile */
            .form-select {
                background-size: 20px 20px;
                background-position: right 12px center;
            }
            
            /* Better button spacing */
            .btn {
                margin-bottom: var(--space-2);
            }
            
            /* Improve toast positioning on mobile */
            .toast-container {
                top: var(--space-4);
                right: var(--space-4);
                left: var(--space-4);
            }
            
            .toast {
                min-width: auto;
                width: 100%;
                margin: 0 0 var(--space-3) 0;
            }
        }
        
        /* Landscape mobile orientation */
        @media (max-width: 768px) and (orientation: landscape) {
            .hero-section {
                padding: var(--space-8) 0;
            }
            
            .hero-title {
                font-size: var(--font-size-2xl);
            }
            
            .reservation-form {
                margin: calc(var(--space-8) * -1) var(--space-4) 0;
            }
            
            .form-section {
                padding: var(--space-6);
            }
        }
    </style>
</head>
<body>
    <!-- Skip Link for Accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
    
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title">Make a Reservation</h1>
            <p class="hero-subtitle">Experience luxury and comfort at RansHotel, located in the beautiful Tsito, Ghana</p>
        </div>
    </section>
    
    <!-- Main Content -->
    <main id="main-content">
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
                                            <option value="1">1 Room</option>
                                        </select>
                                    </div>
                                </div>
                                
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
                                        <p><strong>Room Type:</strong> <span id="summaryRoom"></span></p>
                                        <p><strong>Check-in:</strong> <span id="summaryCheckIn"></span></p>
                                        <p><strong>Check-out:</strong> <span id="summaryCheckOut"></span></p>
                                        <p><strong>Duration:</strong> <span id="summaryDuration"></span> nights</p>
                                    </div>
                                </div>
                                
                                <!-- Pricing Summary -->
                                <div class="pricing-card" id="pricingSummary" style="display: none;">
                                    <h3 style="color: var(--classic-gold); margin-bottom: var(--space-6); text-align: center;">Pricing Summary</h3>
                                    
                                    <div class="pricing-item">
                                        <span>Room Cost:</span>
                                        <span id="roomCost">₵0.00</span>
                                    </div>
                                    <div class="pricing-item">
                                        <span>Meal Cost:</span>
                                        <span id="mealCost">₵0.00</span>
                                    </div>
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
                                        <span>Total Amount:</span>
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
            const checkIn = document.getElementById('cin').value;
            const checkOut = document.getElementById('cout').value;
            
            if (title && fname && lname && email && phone && room && checkIn && checkOut) {
                document.getElementById('summaryName').textContent = `${title} ${fname} ${lname}`;
                document.getElementById('summaryEmail').textContent = email;
                document.getElementById('summaryPhone').textContent = phone;
                document.getElementById('summaryRoom').textContent = room;
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
            const mealPrice = parseFloat(mealSelect.options[mealSelect.selectedIndex].dataset.price) || 0;
            const nrooms = parseInt(nroomSelect.value) || 1;
            
            let days = 0;
            if (checkInInput.value && checkOutInput.value) {
                const checkIn = new Date(checkInInput.value);
                const checkOut = new Date(checkOutInput.value);
                days = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            }
            
            if (roomPrice > 0 && days > 0) {
                const roomTotal = roomPrice * days * nrooms;
                const mealTotal = mealPrice * days * nrooms;
                const subtotal = roomTotal + mealTotal;
                const tax = subtotal * 0.15;
                const serviceCharge = subtotal * 0.10;
                const total = subtotal + tax + serviceCharge;
                
                document.getElementById('roomCost').textContent = '₵' + roomTotal.toFixed(2);
                document.getElementById('mealCost').textContent = '₵' + mealTotal.toFixed(2);
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
</body>
</html>
