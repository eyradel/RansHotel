<?php
session_start();
if(!isset($_SESSION['user'])){
    header("location:index.php");
}
include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('notifications');
require_once 'includes/notification_manager.php';
require_once 'includes/email_notification.php';
require_once 'includes/sms_notification.php';

// Start admin page with components
startUnifiedAdminPage('Notifications', 'Manage notifications and communications for RansHotel');
?>
    <div class="p-4 sm:p-6 lg:p-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fa fa-bell text-white text-lg"></i>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Notifications</h1>
            </div>
            <p class="text-gray-600">Manage SMS & Email Communications</p>
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
                            
                            // Process nested results for both SMS and email
                            $successCount = 0;
                            $failCount = 0;
                            
                            // Count SMS results
                            if(isset($results['sms'])) {
                                foreach($results['sms'] as $result) {
                                    if(is_array($result) && isset($result['success']) && $result['success']) {
                                        $successCount++;
                                    } else {
                                        $failCount++;
                                    }
                                }
                            }
                            
                            // Count email results
                            if(isset($results['email'])) {
                                foreach($results['email'] as $result) {
                                    if(is_array($result) && isset($result['success']) && $result['success']) {
                                        $successCount++;
                                    } else {
                                        $failCount++;
                                    }
                                }
                            }
                        } elseif($notificationType == 'sms') {
                            $smsNotification = new SMSNotification();
                            $results = [];
                            foreach($customers as $customer) {
                                $results[] = $smsNotification->sendSMS($customer['phone'], $message);
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
                            
                            $successCount = 0;
                            $failCount = 0;
                            foreach($results as $result) {
                                if(is_array($result) && isset($result['success']) && $result['success']) {
                                    $successCount++;
                                } else {
                                    $failCount++;
                                }
                            }
                        }
                        
                        echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4'>
                            <strong>Bulk notification sent!</strong> 
                            Successfully sent to $successCount recipients, $failCount failed.
                        </div>";
                    } else {
                        echo "<div class='bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg mb-4'>
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
                        
                        echo "<div class='bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4'>
                            <strong>Reminder sent!</strong> Check-in reminder sent to {$booking['FName']} {$booking['LName']}.
                        </div>";
                    }
                }
                ?>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <!-- Bulk Notification Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Send Bulk Notification</h3>
                        </div>
                        <div class="p-6">
                            <form method="post" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Notification Type</label>
                                    <select name="notification_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                                        <option value="">Select Type</option>
                                        <option value="both">SMS & Email</option>
                                        <option value="sms">SMS Only</option>
                                        <option value="email">Email Only</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                    <textarea name="message" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" rows="4" required placeholder="Enter your message here..."></textarea>
                                </div>
                                <button type="submit" name="send_bulk_notification" class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors duration-200 font-medium flex items-center justify-center space-x-2">
                                    <i class="fa fa-send"></i>
                                    <span>Send to All Customers</span>
                                </button>
                            </form>
                        </div>
                    </div>
                    
                    <!-- Individual Reminder Form -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Send Check-in Reminder</h3>
                        </div>
                        <div class="p-6">
                            <form method="post" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Booking</label>
                                    <select name="booking_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
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
                                <button type="submit" name="send_reminder" class="w-full bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors duration-200 font-medium flex items-center justify-center space-x-2">
                                    <i class="fa fa-bell"></i>
                                    <span>Send Reminder</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Bookings -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php
                                $sql = "SELECT * FROM roombook ORDER BY id DESC LIMIT 10";
                                $query = mysqli_query($con, $sql);
                                while($row = mysqli_fetch_assoc($query)) {
                                    echo "<tr class='hover:bg-gray-50'>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$row['id']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$row['Title']} {$row['FName']} {$row['LName']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$row['Email']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$row['Phone']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$row['TRoom']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$row['cin']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$row['cout']}</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap'>";
                                    $status = $row['stat'];
                                    $statusColors = ['Confirmed' => 'bg-green-100 text-green-800', 'Checked In' => 'bg-blue-100 text-blue-800', 'Checked Out' => 'bg-gray-100 text-gray-800', 'Cancelled' => 'bg-red-100 text-red-800'];
                                    $statusColor = $statusColors[$status] ?? 'bg-gray-100 text-gray-800';
                                    echo "<span class='px-2 py-1 text-xs font-medium rounded-full {$statusColor}'>{$status}</span>";
                                    echo "</td>";
                                    echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>";
                                    echo "<button onclick='sendReminder({$row['id']})' class='text-green-600 hover:text-green-900' title='Send Reminder'>";
                                    echo "<i class='fa fa-bell'></i>";
                                    echo "</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
    </div>
    
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
<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>


