<?php
session_start();
if(!isset($_SESSION['user'])) {
    header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
initAccessControl('messages');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>Newsletter Management - RansHotel Admin</title>
    <meta name="description" content="Manage newsletter subscribers and send newsletters">
    <meta name="author" content="RansHotel">
    
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Inter:wght@300;400;500;600;700&display=swap' rel='stylesheet' />
    
    <style>
        body {
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        .header {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .main-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        
        .stats-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            flex: 1;
            min-width: 200px;
            margin: 10px;
        }
        
        .stats-card h3 {
            font-size: 36px;
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .stats-card p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        
        .content-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin: 20px 0;
        }
        
        .card-header {
            background: #f8f9fa;
            padding: 15px 20px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        
        .card-header h3 {
            margin: 0;
            color: #2c3e50;
            font-size: 18px;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 5px;
        }
        
        .btn-primary { background-color: #007bff; color: white; }
        .btn-success { background-color: #28a745; color: white; }
        .btn-warning { background-color: #ffc107; color: #212529; }
        .btn-danger { background-color: #dc3545; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        
        .btn:hover {
            opacity: 0.8;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin: 10px 0;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #dee2e6;
            border-radius: 8px 8px 0 0;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 20px;
            border-top: 1px solid #dee2e6;
            text-align: right;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #2c3e50;
        }
        
        .form-control {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0,123,255,0.25);
        }
        
        .close {
            float: right;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            color: #aaa;
        }
        
        .close:hover {
            color: #000;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-success { background-color: #28a745; color: white; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .badge-danger { background-color: #dc3545; color: white; }
        
        .popup-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            padding: 20px;
            z-index: 9999;
            max-width: 400px;
            border-left: 4px solid #007bff;
        }
        
        .popup-notification.success {
            border-left-color: #28a745;
        }
        
        .popup-notification.error {
            border-left-color: #dc3545;
        }
        
        .popup-notification.warning {
            border-left-color: #ffc107;
        }
        
        .popup-notification h4 {
            margin: 0 0 10px 0;
            color: #2c3e50;
        }
        
        .popup-notification p {
            margin: 0;
            color: #666;
        }
        
        .popup-close {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            color: #aaa;
        }
        
        .popup-close:hover {
            color: #000;
        }
        
        .actions-container {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .action-btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 5px;
        }
        
        .action-btn:hover {
            background-color: #0056b3;
            color: white;
            text-decoration: none;
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 10px;
            }
            
            .stats-container {
                flex-direction: column;
            }
            
            .stats-card {
                margin: 5px 0;
            }
            
            .actions-container {
                flex-direction: column;
            }
            
            .action-btn {
                width: 100%;
                text-align: center;
                margin: 5px 0;
            }
            
            .table-container {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .table {
                min-width: 600px;
            }
            
            .table th,
            .table td {
                white-space: nowrap;
                font-size: 12px;
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 style="margin: 0; font-size: 24px;">RansHotel Admin</h1>
                <p style="margin: 5px 0 0 0; opacity: 0.8;">Newsletter Management System</p>
            </div>
            <div>
                <span style="margin-right: 20px;">Welcome, <?php echo $_SESSION['user']; ?></span>
                <a href="dashboard_simple.php" style="color: white; text-decoration: none; margin-right: 15px;">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
                <a href="logout.php" style="color: white; text-decoration: none;">
                    <i class="fa fa-sign-out"></i> Logout
                </a>
            </div>
        </div>
    </div>

    <div class="main-content">
        <?php
        // Handle newsletter sending
        if(isset($_POST['send_newsletter'])) {
            $title = mysqli_real_escape_string($con, $_POST['title']);
            $subject = mysqli_real_escape_string($con, $_POST['subject']);
            $news = mysqli_real_escape_string($con, $_POST['news']);
            $test_email = mysqli_real_escape_string($con, $_POST['test_email']);
            
            // Save newsletter to log
            $log = "INSERT INTO `newsletterlog`(`title`, `subject`, `news`) VALUES ('$title','$subject','$news')";
            if(mysqli_query($con, $log)) {
                $newsletter_id = mysqli_insert_id($con);
                
                // Get approved subscribers
                $subscribers_sql = "SELECT * FROM contact WHERE approval = 'Allowed'";
                $subscribers_result = mysqli_query($con, $subscribers_sql);
                
                $sent_count = 0;
                $failed_count = 0;
                $total_subscribers = 0;
                $results = [];
                
                if($subscribers_result) {
                    $total_subscribers = mysqli_num_rows($subscribers_result);
                    
                    // Send to each subscriber
                    while($subscriber = mysqli_fetch_array($subscribers_result)) {
                        $to = $subscriber['email'];
                        $name = $subscriber['fullname'];
                        
                        // Create email content
                        $email_subject = $subject;
                        $email_body = "
                        <html>
                        <head>
                            <title>$title</title>
                            <style>
                                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                                .header { background-color: #2c3e50; color: white; padding: 20px; text-align: center; }
                                .content { padding: 20px; background-color: #f8f9fa; }
                                .footer { background-color: #6c757d; color: white; padding: 15px; text-align: center; font-size: 12px; }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='header'>
                                    <h1>RansHotel Newsletter</h1>
                                </div>
                                <div class='content'>
                                    <h2>$title</h2>
                                    <p>Dear $name,</p>
                                    <div style='background-color: white; padding: 20px; border-radius: 5px; margin: 20px 0;'>
                                        $news
                                    </div>
                                    <p>Best regards,<br><strong>RansHotel Team</strong></p>
                                </div>
                                <div class='footer'>
                                    <p>You received this email because you subscribed to our newsletter.</p>
                                    <p>RansHotel - Tsito, Volta Region, Ghana | +233 (0)302 936 062</p>
                                </div>
                            </div>
                        </body>
                        </html>
                        ";
                        
                        // Enhanced email headers
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                        $headers .= "From: RansHotel Newsletter <newsletter@ranshotel.com>" . "\r\n";
                        $headers .= "Reply-To: info@ranshotel.com" . "\r\n";
                        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
                        
                        // Send email
                        if(mail($to, $email_subject, $email_body, $headers)) {
                            $sent_count++;
                            $results[] = ['email' => $to, 'status' => 'success', 'message' => 'Sent successfully'];
                        } else {
                            $failed_count++;
                            $results[] = ['email' => $to, 'status' => 'failed', 'message' => 'Failed to send'];
                        }
                    }
                }
                
                // Send test email if provided
                if(!empty($test_email)) {
                    $test_body = str_replace('Dear ' . $name, 'Dear Test User', $email_body);
                    if(mail($test_email, $email_subject . ' (TEST)', $test_body, $headers)) {
                        $results[] = ['email' => $test_email, 'status' => 'test', 'message' => 'Test email sent successfully'];
                    }
                }
                
                // Store results in session for popup display
                $_SESSION['newsletter_results'] = [
                    'sent_count' => $sent_count,
                    'failed_count' => $failed_count,
                    'total_subscribers' => $total_subscribers,
                    'newsletter_id' => $newsletter_id,
                    'results' => $results
                ];
                
                // Redirect to show results
                header("location: newsletter_enhanced.php?sent=1");
                exit();
            } else {
                echo '<div class="alert alert-danger">Error saving newsletter: ' . mysqli_error($con) . '</div>';
            }
        }
        
        // Handle subscriber management
        if(isset($_GET['approve'])) {
            $id = $_GET['approve'];
            $sql = "UPDATE contact SET approval = 'Allowed' WHERE id = '$id'";
            if(mysqli_query($con, $sql)) {
                echo '<div class="alert alert-success">Subscriber approved successfully!</div>';
            }
        }
        
        if(isset($_GET['disapprove'])) {
            $id = $_GET['disapprove'];
            $sql = "UPDATE contact SET approval = 'Not Allowed' WHERE id = '$id'";
            if(mysqli_query($con, $sql)) {
                echo '<div class="alert alert-warning">Subscriber disapproved successfully!</div>';
            }
        }
        
        if(isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $sql = "DELETE FROM contact WHERE id = '$id'";
            if(mysqli_query($con, $sql)) {
                echo '<div class="alert alert-danger">Subscriber deleted successfully!</div>';
            }
        }
        ?>

        <!-- Newsletter Statistics -->
        <div class="stats-container">
            <?php
            // Get newsletter statistics
            $total_subscribers = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM contact"))['count'];
            $approved_subscribers = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM contact WHERE approval = 'Allowed'"))['count'];
            $pending_subscribers = $total_subscribers - $approved_subscribers;
            $newsletters_sent = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as count FROM newsletterlog"))['count'];
            ?>
            
            <div class="stats-card">
                <h3 style="color: #007bff;"><?php echo $total_subscribers; ?></h3>
                <p>Total Subscribers</p>
            </div>
            
            <div class="stats-card">
                <h3 style="color: #28a745;"><?php echo $approved_subscribers; ?></h3>
                <p>Approved Subscribers</p>
            </div>
            
            <div class="stats-card">
                <h3 style="color: #ffc107;"><?php echo $pending_subscribers; ?></h3>
                <p>Pending Approval</p>
            </div>
            
            <div class="stats-card">
                <h3 style="color: #17a2b8;"><?php echo $newsletters_sent; ?></h3>
                <p>Newsletters Sent</p>
            </div>
        </div>

        <!-- Actions -->
        <div class="actions-container">
            <button class="action-btn" onclick="openNewsletterModal()">
                <i class="fa fa-envelope"></i> Send Newsletter
            </button>
            <button class="action-btn" onclick="openTestModal()" style="background-color: #28a745;">
                <i class="fa fa-flask"></i> Test Email
            </button>
            <a href="dashboard_simple.php" class="action-btn" style="background-color: #6c757d;">
                <i class="fa fa-dashboard"></i> Back to Dashboard
            </a>
        </div>

        <!-- Subscribers Table -->
        <div class="content-card">
            <div class="card-header">
                <h3>Newsletter Subscribers</h3>
            </div>
            <div class="card-body">
                <div class="table-container">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT * FROM contact ORDER BY id DESC";
                            $query = mysqli_query($con, $sql);
                            while($row = mysqli_fetch_assoc($query)) {
                                echo "<tr>";
                                echo "<td>#" . $row['id'] . "</td>";
                                echo "<td><strong>" . $row['fullname'] . "</strong></td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['phoneno'] . "</td>";
                                echo "<td>" . $row['cdate'] . "</td>";
                                
                                // Status badge
                                $statusClass = ($row['approval'] == 'Allowed') ? 'badge-success' : 'badge-warning';
                                $statusText = ($row['approval'] == 'Allowed') ? 'Approved' : 'Pending';
                                echo "<td><span class='badge " . $statusClass . "'>" . $statusText . "</span></td>";
                                
                                // Actions
                                echo "<td>";
                                if($row['approval'] == 'Allowed') {
                                    echo "<a href='?disapprove=" . $row['id'] . "' class='btn btn-warning btn-sm'>Disapprove</a>";
                                } else {
                                    echo "<a href='?approve=" . $row['id'] . "' class='btn btn-success btn-sm'>Approve</a>";
                                }
                                echo "<a href='?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Newsletter Modal -->
    <div id="newsletterModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Send Newsletter</h3>
                <span class="close" onclick="closeNewsletterModal()">&times;</span>
            </div>
            <form method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Newsletter Title *</label>
                        <input type="text" class="form-control" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="subject">Email Subject *</label>
                        <input type="text" class="form-control" name="subject" id="subject" required>
                    </div>
                    <div class="form-group">
                        <label for="news">Newsletter Content *</label>
                        <textarea class="form-control" name="news" id="news" rows="8" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="test_email">Test Email (Optional)</label>
                        <input type="email" class="form-control" name="test_email" id="test_email" placeholder="Enter email to send test copy">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeNewsletterModal()">Cancel</button>
                    <button type="submit" name="send_newsletter" class="btn btn-primary">Send Newsletter</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Test Email Modal -->
    <div id="testModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Test Email System</h3>
                <span class="close" onclick="closeTestModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <strong>Email System Status:</strong><br>
                    <?php
                    if(function_exists('mail')) {
                        echo "✅ PHP mail() function is available<br>";
                        echo "✅ Email system is ready to send newsletters<br>";
                        echo "📧 Approved subscribers: " . $approved_subscribers . "<br>";
                        echo "📊 Total subscribers: " . $total_subscribers;
                    } else {
                        echo "❌ PHP mail() function is not available<br>";
                        echo "⚠️ Email system may not work properly";
                    }
                    ?>
                </div>
                <p>Use the "Send Newsletter" button to send newsletters to all approved subscribers.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeTestModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    
    <script>
    function openNewsletterModal() {
        document.getElementById('newsletterModal').style.display = 'block';
    }
    
    function closeNewsletterModal() {
        document.getElementById('newsletterModal').style.display = 'none';
    }
    
    function openTestModal() {
        document.getElementById('testModal').style.display = 'block';
    }
    
    function closeTestModal() {
        document.getElementById('testModal').style.display = 'none';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var newsletterModal = document.getElementById('newsletterModal');
        var testModal = document.getElementById('testModal');
        if (event.target == newsletterModal) {
            newsletterModal.style.display = "none";
        }
        if (event.target == testModal) {
            testModal.style.display = "none";
        }
    }
    
    // Show popup notification if newsletter was sent
    <?php if(isset($_GET['sent']) && isset($_SESSION['newsletter_results'])): ?>
    $(document).ready(function() {
        var results = <?php echo json_encode($_SESSION['newsletter_results']); ?>;
        showNewsletterResults(results);
        <?php unset($_SESSION['newsletter_results']); ?>
    });
    <?php endif; ?>
    
    function showNewsletterResults(results) {
        var popup = $('<div class="popup-notification success">' +
            '<button class="popup-close" onclick="$(this).parent().fadeOut()">&times;</button>' +
            '<h4>Newsletter Sent Successfully!</h4>' +
            '<p><strong>Results:</strong></p>' +
            '<p>✅ Sent: ' + results.sent_count + ' emails</p>' +
            '<p>❌ Failed: ' + results.failed_count + ' emails</p>' +
            '<p>📊 Total Subscribers: ' + results.total_subscribers + '</p>' +
            '<p>📧 Newsletter ID: #' + results.newsletter_id + '</p>' +
            '</div>');
        
        $('body').append(popup);
        
        // Auto-hide after 10 seconds
        setTimeout(function() {
            popup.fadeOut();
        }, 10000);
    }
    </script>
</body>
</html>
