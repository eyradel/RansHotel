<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

// Include access control system
include('includes/access_control.php');
initAccessControl('dashboard');
?> 
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - RansHotel Admin</title>
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/font-awesome.css" rel="stylesheet">
    
    <style>
        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column !important;
                align-items: flex-start !important;
            }
            
            .header-content h1 {
                font-size: 20px !important;
                margin-bottom: 10px !important;
            }
            
            .stats-container {
                flex-direction: column !important;
            }
            
            .stats-card {
                margin-bottom: 15px !important;
            }
            
            .stats-card h3 {
                font-size: 28px !important;
            }
            
            .actions-container {
                flex-direction: column !important;
            }
            
            .action-btn {
                width: 100% !important;
                margin: 5px 0 !important;
                text-align: center !important;
            }
            
            .content-container {
                flex-direction: column !important;
            }
            
            .table-container {
                overflow-x: auto !important;
                -webkit-overflow-scrolling: touch !important;
            }
            
            .table {
                min-width: 600px !important;
            }
            
            .table th,
            .table td {
                white-space: nowrap !important;
                font-size: 12px !important;
            }
            
            .table th:nth-child(2),
            .table td:nth-child(2) {
                min-width: 120px !important;
            }
            
            .table th:nth-child(3),
            .table td:nth-child(3) {
                min-width: 100px !important;
            }
            
            .main-content {
                padding: 10px !important;
            }
        }
        
        @media (max-width: 480px) {
            .header-content h1 {
                font-size: 18px !important;
            }
            
            .stats-card h3 {
                font-size: 24px !important;
            }
            
            .stats-card p {
                font-size: 12px !important;
            }
            
            .table th,
            .table td {
                padding: 6px !important;
                font-size: 11px !important;
            }
            
            .table {
                min-width: 500px !important;
            }
            
            .mobile-card {
                display: block !important;
            }
            
            .table {
                display: none !important;
            }
        }
        
        .mobile-card {
            display: none;
        }
        
        .booking-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        
        .booking-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .booking-id {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .booking-status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 500;
        }
        
        .booking-details {
            font-size: 13px;
            color: #666;
        }
        
        .booking-details div {
            margin-bottom: 5px;
        }
        
        .booking-action {
            margin-top: 10px;
        }
    </style>
</head>
<body style="background-color: #f5f5f5; margin: 0; padding: 0; font-family: Arial, sans-serif;">

<!-- Header -->
<div style="background-color: #2c3e50; color: white; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
    <div class="header-content" style="display: flex; justify-content: space-between; align-items: center; max-width: 1200px; margin: 0 auto;">
        <h1 style="margin: 0; font-size: 24px;">RansHotel Admin Dashboard</h1>
        <div>
            <span style="background-color: #3498db; padding: 4px 8px; border-radius: 4px; font-size: 12px; margin-right: 10px;"><?php echo ucfirst(getCurrentUserRole()); ?></span>
            <span style="color: #bdc3c7;"><?php echo date('F j, Y'); ?></span>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content" style="max-width: 1200px; margin: 0 auto; padding: 20px;">
    <?php
    include('db.php');
    
    // Initialize variables to prevent undefined variable warnings
    $c = 0; // Pending bookings
    $r = 0; // Confirmed bookings
    $f = 0; // Newsletter subscribers
    
    // Get booking statistics
    $sql = "select * from roombook";
    $re = mysqli_query($con, $sql);
    if($re) {
        while($row = mysqli_fetch_array($re)) {
            $new = $row['stat'];
            if($new == "Not Confirm") {
                $c = $c + 1;
            }
        }
    }
    
    // Get confirmed bookings count
    $rsql = "SELECT * FROM `roombook`";
    $rre = mysqli_query($con, $rsql);
    if($rre) {
        while($row = mysqli_fetch_array($rre)) {
            $br = $row['stat'];
            if($br == "Confirm") {
                $r = $r + 1;
            }
        }
    }
    
    // Get newsletter subscribers count
    $fsql = "SELECT * FROM `contact`";
    $fre = mysqli_query($con, $fsql);
    if($fre) {
        while($row = mysqli_fetch_array($fre)) {
            $f = $f + 1;
        }
    }
    ?>

    <!-- Statistics Cards -->
    <div class="stats-container" style="display: flex; flex-wrap: wrap; margin: -10px;">
        <div style="flex: 1; min-width: 200px; padding: 10px;">
            <div class="stats-card" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="font-size: 36px; margin: 0 0 10px 0; color: #2c3e50;"><?php echo $c; ?></h3>
                <p style="margin: 0; color: #666; font-size: 14px;">Pending Bookings</p>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 200px; padding: 10px;">
            <div class="stats-card" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="font-size: 36px; margin: 0 0 10px 0; color: #27ae60;"><?php echo $r; ?></h3>
                <p style="margin: 0; color: #666; font-size: 14px;">Confirmed Bookings</p>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 200px; padding: 10px;">
            <div class="stats-card" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="font-size: 36px; margin: 0 0 10px 0; color: #f39c12;"><?php echo $f; ?></h3>
                <p style="margin: 0; color: #666; font-size: 14px;">Newsletter Subscribers</p>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 200px; padding: 10px;">
            <div class="stats-card" style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-align: center;">
                <h3 style="font-size: 36px; margin: 0 0 10px 0; color: #e74c3c;"><?php echo $c + $r; ?></h3>
                <p style="margin: 0; color: #666; font-size: 14px;">Total Bookings</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); margin: 20px 0;">
        <div style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #dee2e6; border-radius: 8px 8px 0 0;">
            <h3 style="margin: 0; font-size: 18px; color: #2c3e50;">Quick Actions</h3>
        </div>
        <div class="actions-container" style="padding: 20px; display: flex; flex-wrap: wrap;">
            <?php if (canAccess('room_booking')): ?>
            <a href="roombook.php" class="action-btn" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">
                <i class="fa fa-bed" style="margin-right: 8px;"></i>Manage Bookings
            </a>
            <?php endif; ?>
            
            <?php if (canAccess('notifications')): ?>
            <a href="notifications.php" class="action-btn" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">
                <i class="fa fa-bell" style="margin-right: 8px;"></i>Send Notifications
            </a>
            <?php endif; ?>
            
            <?php if (canAccess('messages')): ?>
            <a href="messages.php" class="action-btn" style="display: inline-block; padding: 10px 20px; background-color: #ffc107; color: #212529; text-decoration: none; border-radius: 4px; margin: 5px;">
                <i class="fa fa-envelope" style="margin-right: 8px;"></i>Newsletter
            </a>
            <?php endif; ?>
            
            <?php if (canAccess('user_management')): ?>
            <a href="user_management_pro.php" class="action-btn" style="display: inline-block; padding: 10px 20px; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">
                <i class="fa fa-users" style="margin-right: 8px;"></i>User Management
            </a>
            <?php endif; ?>
            
            <a href="logout.php" class="action-btn" style="display: inline-block; padding: 10px 20px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 4px; margin: 5px;">
                <i class="fa fa-sign-out" style="margin-right: 8px;"></i>Logout
            </a>
        </div>
    </div>

    <!-- Recent Bookings and System Status -->
    <div class="content-container" style="display: flex; flex-wrap: wrap; margin: -10px;">
        <div style="flex: 2; min-width: 300px; padding: 10px;">
            <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #dee2e6; border-radius: 8px 8px 0 0;">
                    <h3 style="margin: 0; font-size: 18px; color: #2c3e50;">Recent Bookings</h3>
                </div>
                <div class="table-container" style="padding: 20px;">
                    <!-- Desktop Table View -->
                    <table class="table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background-color: #f8f9fa;">
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600; color: #2c3e50;">ID</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600; color: #2c3e50;">Guest Name</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600; color: #2c3e50;">Room Type</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600; color: #2c3e50;">Check-in</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600; color: #2c3e50;">Status</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-weight: 600; color: #2c3e50;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tsql = "select * from roombook ORDER BY id DESC LIMIT 10";
                            $tre = mysqli_query($con, $tsql);
                            while($trow = mysqli_fetch_array($tre)) {
                                $statusColor = ($trow['stat'] == 'Confirm') ? '#28a745' : '#ffc107';
                                $statusTextColor = ($trow['stat'] == 'Confirm') ? 'white' : '#212529';
                                echo "<tr>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #dee2e6;'>#" . $trow['id'] . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #dee2e6;'>" . $trow['FName'] . " " . $trow['LName'] . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #dee2e6;'>" . $trow['TRoom'] . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #dee2e6;'>" . $trow['cin'] . "</td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #dee2e6;'><span style='background-color: " . $statusColor . "; color: " . $statusTextColor . "; padding: 4px 8px; border-radius: 4px; font-size: 12px;'>" . $trow['stat'] . "</span></td>";
                                echo "<td style='padding: 12px; border-bottom: 1px solid #dee2e6;'><a href='roombook.php?rid=" . $trow['id'] . "' style='display: inline-block; padding: 5px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 12px;'>Manage</a></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    
                    <!-- Mobile Card View -->
                    <div class="mobile-card">
                        <?php
                        $tsql2 = "select * from roombook ORDER BY id DESC LIMIT 10";
                        $tre2 = mysqli_query($con, $tsql2);
                        while($trow2 = mysqli_fetch_array($tre2)) {
                            $statusColor = ($trow2['stat'] == 'Confirm') ? '#28a745' : '#ffc107';
                            $statusTextColor = ($trow2['stat'] == 'Confirm') ? 'white' : '#212529';
                            echo "<div class='booking-card'>";
                            echo "<div class='booking-card-header'>";
                            echo "<div class='booking-id'>#" . $trow2['id'] . "</div>";
                            echo "<div class='booking-status' style='background-color: " . $statusColor . "; color: " . $statusTextColor . ";'>" . $trow2['stat'] . "</div>";
                            echo "</div>";
                            echo "<div class='booking-details'>";
                            echo "<div><strong>Guest:</strong> " . $trow2['FName'] . " " . $trow2['LName'] . "</div>";
                            echo "<div><strong>Room:</strong> " . $trow2['TRoom'] . "</div>";
                            echo "<div><strong>Check-in:</strong> " . $trow2['cin'] . "</div>";
                            echo "</div>";
                            echo "<div class='booking-action'>";
                            echo "<a href='roombook.php?rid=" . $trow2['id'] . "' style='display: inline-block; padding: 8px 16px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;'>Manage Booking</a>";
                            echo "</div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 250px; padding: 10px;">
            <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                <div style="background: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #dee2e6; border-radius: 8px 8px 0 0;">
                    <h3 style="margin: 0; font-size: 18px; color: #2c3e50;">System Status</h3>
                </div>
                <div style="padding: 20px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span>Database Connection</span>
                        <span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Online</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span>Email System</span>
                        <span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Active</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span>SMS System</span>
                        <span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Active</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span>Access Control</span>
                        <span style="background-color: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px;">Enabled</span>
                    </div>
                    <hr style="border: none; border-top: 1px solid #dee2e6; margin: 20px 0;">
                    <div style="text-align: center;">
                        <small style="color: #6c757d;" class="last-updated">
                            Last updated: <?php echo date('H:i:s'); ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script src="assets/js/jquery-1.10.2.js"></script>
<script src="assets/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Real-time updates every 30 seconds
    setInterval(function() {
        updateDashboard();
    }, 30000);
    
    // Initial load
    updateDashboard();
});

function updateDashboard() {
    // Update statistics
    updateStatistics();
    
    // Update recent bookings
    updateRecentBookings();
    
    // Update system status
    updateSystemStatus();
}

function updateStatistics() {
    $.ajax({
        url: 'ajax/get_statistics.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Update statistics cards with animation
                $('.stats-card h3').each(function(index) {
                    var $this = $(this);
                    var newValue = data.stats[index];
                    var currentValue = parseInt($this.text());
                    
                    if (currentValue !== newValue) {
                        // Animate number change
                        animateNumber($this, currentValue, newValue);
                    }
                });
            }
        },
        error: function() {
            console.log('Failed to update statistics');
        }
    });
}

function updateRecentBookings() {
    $.ajax({
        url: 'ajax/get_recent_bookings.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Update desktop table
                updateTable(data.bookings);
                
                // Update mobile cards
                updateMobileCards(data.bookings);
            }
        },
        error: function() {
            console.log('Failed to update recent bookings');
        }
    });
}

function updateSystemStatus() {
    $.ajax({
        url: 'ajax/get_system_status.php',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.success) {
                // Update last updated time
                $('.last-updated').text('Last updated: ' + data.timestamp);
                
                // Update status indicators if needed
                updateStatusIndicators(data.status);
            }
        },
        error: function() {
            console.log('Failed to update system status');
        }
    });
}

function animateNumber($element, start, end) {
    $element.addClass('updating');
    
    var duration = 1000;
    var startTime = null;
    
    function animate(currentTime) {
        if (startTime === null) startTime = currentTime;
        var progress = Math.min((currentTime - startTime) / duration, 1);
        
        var current = Math.floor(start + (end - start) * progress);
        $element.text(current);
        
        if (progress < 1) {
            requestAnimationFrame(animate);
        } else {
            $element.removeClass('updating');
        }
    }
    
    requestAnimationFrame(animate);
}

function updateTable(bookings) {
    var tableBody = $('.table tbody');
    tableBody.empty();
    
    bookings.forEach(function(booking) {
        var statusColor = (booking.stat === 'Confirm') ? '#28a745' : '#ffc107';
        var statusTextColor = (booking.stat === 'Confirm') ? 'white' : '#212529';
        
        var row = '<tr>' +
            '<td style="padding: 12px; border-bottom: 1px solid #dee2e6;">#' + booking.id + '</td>' +
            '<td style="padding: 12px; border-bottom: 1px solid #dee2e6;">' + booking.FName + ' ' + booking.LName + '</td>' +
            '<td style="padding: 12px; border-bottom: 1px solid #dee2e6;">' + booking.TRoom + '</td>' +
            '<td style="padding: 12px; border-bottom: 1px solid #dee2e6;">' + booking.cin + '</td>' +
            '<td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><span style="background-color: ' + statusColor + '; color: ' + statusTextColor + '; padding: 4px 8px; border-radius: 4px; font-size: 12px;">' + booking.stat + '</span></td>' +
            '<td style="padding: 12px; border-bottom: 1px solid #dee2e6;"><a href="roombook.php?rid=' + booking.id + '" style="display: inline-block; padding: 5px 10px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 12px;">Manage</a></td>' +
            '</tr>';
        
        tableBody.append(row);
    });
}

function updateMobileCards(bookings) {
    var mobileCards = $('.mobile-card');
    mobileCards.empty();
    
    bookings.forEach(function(booking) {
        var statusColor = (booking.stat === 'Confirm') ? '#28a745' : '#ffc107';
        var statusTextColor = (booking.stat === 'Confirm') ? 'white' : '#212529';
        
        var card = '<div class="booking-card">' +
            '<div class="booking-card-header">' +
            '<div class="booking-id">#' + booking.id + '</div>' +
            '<div class="booking-status" style="background-color: ' + statusColor + '; color: ' + statusTextColor + ';">' + booking.stat + '</div>' +
            '</div>' +
            '<div class="booking-details">' +
            '<div><strong>Guest:</strong> ' + booking.FName + ' ' + booking.LName + '</div>' +
            '<div><strong>Room:</strong> ' + booking.TRoom + '</div>' +
            '<div><strong>Check-in:</strong> ' + booking.cin + '</div>' +
            '</div>' +
            '<div class="booking-action">' +
            '<a href="roombook.php?rid=' + booking.id + '" style="display: inline-block; padding: 8px 16px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 14px;">Manage Booking</a>' +
            '</div>' +
            '</div>';
        
        mobileCards.append(card);
    });
}

function updateStatusIndicators(status) {
    // Update status indicators if they change
    $('.status-indicator').each(function() {
        var $this = $(this);
        var statusType = $this.data('status');
        
        if (status[statusType] !== undefined) {
            var isOnline = status[statusType];
            var badgeClass = isOnline ? 'badge-success' : 'badge-danger';
            var text = isOnline ? 'Online' : 'Offline';
            
            $this.removeClass('badge-success badge-danger').addClass(badgeClass).text(text);
        }
    });
}

// Add visual feedback for updates
function showUpdateNotification() {
    var notification = $('<div class="update-notification">Dashboard updated</div>');
    $('body').append(notification);
    
    setTimeout(function() {
        notification.fadeOut(function() {
            notification.remove();
        });
    }, 2000);
}

// Show notification when updates occur
$(document).on('dashboardUpdated', function() {
    showUpdateNotification();
});
</script>

<style>
.updating {
    color: #007bff !important;
    font-weight: bold;
}

.update-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #28a745;
    color: white;
    padding: 10px 20px;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    z-index: 9999;
    font-size: 14px;
}

.status-indicator {
    transition: all 0.3s ease;
}
</style>

</body>
</html>
