<?php
session_start();
if(!isset($_SESSION['admin'])){
    header("location:index.php");
}
include('db.php');
require_once 'includes/notification_manager.php';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Notifications - RansHotel</title>
    <meta name="description" content="Manage notifications and communications for RansHotel">
    <meta name="author" content="RansHotel">
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Responsive Admin Styles-->
    <link href="assets/css/responsive-admin.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper">
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="home.php"><i class="fa fa-dashboard"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="reservation.php"><i class="fa fa-calendar"></i> Reservations</a>
                    </li>
                    <li>
                        <a href="roombook.php"><i class="fa fa-bed"></i> Room Bookings</a>
                    </li>
                    <li>
                        <a href="notifications.php" class="active-menu"><i class="fa fa-bell"></i> Notifications</a>
                    </li>
                    <li>
                        <a href="messages.php"><i class="fa fa-envelope"></i> Messages</a>
                    </li>
                    <li>
                        <a href="payment.php"><i class="fa fa-credit-card"></i> Payments</a>
                    </li>
                    <li>
                        <a href="room.php"><i class="fa fa-home"></i> Rooms</a>
                    </li>
                    <li>
                        <a href="usersetting.php"><i class="fa fa-users"></i> Users</a>
                    </li>
                    <li>
                        <a href="settings.php"><i class="fa fa-cog"></i> Settings</a>
                    </li>
                    <li>
                        <a href="logout.php"><i class="fa fa-sign-out"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
       
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Notifications <small>Manage SMS & Email Communications</small>
                        </h1>
                    </div>
                </div> 
                 
                <?php
                // Handle form submissions
                if(isset($_POST['send_bulk_notification'])) {
                    $message = $_POST['message'];
                    $notificationType = $_POST['notification_type'];
                    
                    // Get all customers from database
                    $sql = "SELECT DISTINCT Email as email, CONCAT(FName, ' ', LName) as name, Phone as phone FROM roombook WHERE Email IS NOT NULL AND Email != ''";
                    $query = mysqli_query($con, $sql);
                    
                    $customers = [];
                    while($row = mysqli_fetch_assoc($query)) {
                        $customers[] = $row;
                    }
                    
                    if(!empty($customers)) {
                        $notificationManager = new NotificationManager();
                        
                        if($notificationType == 'both') {
                            $results = $notificationManager->sendBulkNotification($message, $customers);
                        } elseif($notificationType == 'sms') {
                            $smsNotification = new SMSNotification();
                            $results = [];
                            foreach($customers as $customer) {
                                $results[] = $smsNotification->sendSMS($customer['phone'], $message);
                            }
                        } elseif($notificationType == 'email') {
                            $emailNotification = new EmailNotification();
                            $results = [];
                            foreach($customers as $customer) {
                                $results[] = $emailNotification->sendEmail(
                                    $customer['email'],
                                    'Important Update from RansHotel',
                                    $notificationManager->buildBulkEmailBody($message, $customer['name']),
                                    $message
                                );
                            }
                        }
                        
                        $successCount = 0;
                        $failCount = 0;
                        foreach($results as $result) {
                            if(is_array($result) && isset($result['success']) && $result['success']) {
                                $successCount++;
                            } else {
                                $failCount++;
                            }
                        }
                        
                        echo "<div class='alert alert-success'>
                            <strong>Bulk notification sent!</strong> 
                            Successfully sent to $successCount recipients, $failCount failed.
                        </div>";
                    } else {
                        echo "<div class='alert alert-warning'>
                            <strong>No customers found!</strong> No valid email addresses or phone numbers in the database.
                        </div>";
                    }
                }
                
                if(isset($_POST['send_reminder'])) {
                    $bookingId = $_POST['booking_id'];
                    
                    // Get booking details
                    $sql = "SELECT * FROM roombook WHERE id = ?";
                    $stmt = mysqli_prepare($con, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $bookingId);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    $booking = mysqli_fetch_assoc($result);
                    
                    if($booking) {
                        $notificationManager = new NotificationManager();
                        $results = $notificationManager->sendCheckInReminder(
                            $booking['Email'],
                            $booking['Phone'],
                            $booking['FName'] . ' ' . $booking['LName'],
                            $booking['cin'],
                            $booking['TRoom']
                        );
                        
                        echo "<div class='alert alert-success'>
                            <strong>Reminder sent!</strong> Check-in reminder sent to {$booking['FName']} {$booking['LName']}.
                        </div>";
                    }
                }
                ?>
                
                <div class="row">
                    <!-- Bulk Notification Form -->
                    <div class="col-md-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                Send Bulk Notification
                            </div>
                            <div class="panel-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label>Notification Type</label>
                                        <select name="notification_type" class="form-control" required>
                                            <option value="">Select Type</option>
                                            <option value="both">SMS & Email</option>
                                            <option value="sms">SMS Only</option>
                                            <option value="email">Email Only</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea name="message" class="form-control" rows="4" required placeholder="Enter your message here..."></textarea>
                                    </div>
                                    <button type="submit" name="send_bulk_notification" class="btn btn-primary">
                                        <i class="fa fa-send"></i> Send to All Customers
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Individual Reminder Form -->
                    <div class="col-md-6">
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                Send Check-in Reminder
                            </div>
                            <div class="panel-body">
                                <form method="post">
                                    <div class="form-group">
                                        <label>Select Booking</label>
                                        <select name="booking_id" class="form-control" required>
                                            <option value="">Select Booking</option>
                                            <?php
                                            $sql = "SELECT id, FName, LName, cin, TRoom FROM roombook WHERE cin >= CURDATE() ORDER BY cin ASC";
                                            $query = mysqli_query($con, $sql);
                                            while($row = mysqli_fetch_assoc($query)) {
                                                echo "<option value='{$row['id']}'>
                                                    {$row['FName']} {$row['LName']} - {$row['TRoom']} - {$row['cin']}
                                                </option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="send_reminder" class="btn btn-info">
                                        <i class="fa fa-bell"></i> Send Reminder
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Bookings -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Recent Bookings
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Customer</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Room</th>
                                                <th>Check-in</th>
                                                <th>Check-out</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM roombook ORDER BY id DESC LIMIT 10";
                                            $query = mysqli_query($con, $sql);
                                            while($row = mysqli_fetch_assoc($query)) {
                                                echo "<tr>";
                                                echo "<td>{$row['id']}</td>";
                                                echo "<td>{$row['Title']} {$row['FName']} {$row['LName']}</td>";
                                                echo "<td>{$row['Email']}</td>";
                                                echo "<td>{$row['Phone']}</td>";
                                                echo "<td>{$row['TRoom']}</td>";
                                                echo "<td>{$row['cin']}</td>";
                                                echo "<td>{$row['cout']}</td>";
                                                echo "<td><span class='label label-info'>{$row['stat']}</span></td>";
                                                echo "<td>
                                                    <a href='#' class='btn btn-sm btn-success' onclick='sendReminder({$row['id']})'>
                                                        <i class='fa fa-bell'></i> Reminder
                                                    </a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Notification Settings -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                Notification Settings
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h4>SMS Configuration</h4>
                                        <p><strong>API Key:</strong> qqYaIprq4RZ25q9JENdRqQbKZ</p>
                                        <p><strong>Sender ID:</strong> RansHotel</p>
                                        <p><strong>Provider:</strong> mNotify</p>
                                    </div>
                                    <div class="col-md-6">
                                        <h4>Email Configuration</h4>
                                        <p><strong>From Email:</strong> eyramdela14@gmail.com</p>
                                        <p><strong>Manager Email:</strong> eyramdela14@gmail.com</p>
                                        <p><strong>Manager Phone:</strong> 0540202096</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- JS Scripts-->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.metisMenu.js"></script>
    <script src="assets/js/custom-scripts.js"></script>
    
    <script>
    function sendReminder(bookingId) {
        if(confirm('Send check-in reminder for this booking?')) {
            // Create a form and submit it
            var form = document.createElement('form');
            form.method = 'POST';
            form.innerHTML = '<input type="hidden" name="booking_id" value="' + bookingId + '">' +
                           '<input type="hidden" name="send_reminder" value="1">';
            document.body.appendChild(form);
            form.submit();
        }
    }
    </script>
</body>
</html>
