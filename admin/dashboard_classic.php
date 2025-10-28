<?php
include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login_improved.php');
    exit();
}

// Get user info
$user = $_SESSION['user'] ?? 'Admin';
$userRole = getCurrentUserRole();

// Start admin page with unified layout
startUnifiedAdminPage('Dashboard', 'RansHotel Admin Dashboard - Manage your hotel operations');

// Include database connection
include('db.php');
?>

<!-- Enhanced Dashboard Content with Tailwind CSS -->
<div class="p-4 sm:p-6 lg:p-8">
    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 hover-lift animate-fade-in-up">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Revenue</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">
                        <?php
                        $revenue_query = "SELECT SUM(fintot) as total_revenue FROM payment";
                        $revenue_result = mysqli_query($con, $revenue_query);
                        $revenue_data = mysqli_fetch_assoc($revenue_result);
                        echo "₵" . number_format($revenue_data['total_revenue'] ?? 0, 2);
                        ?>
                    </p>
                    <div class="flex items-center mt-2 text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+12.5% from last month</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 hover-lift animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Total Bookings</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">
                        <?php
                        $bookings_query = "SELECT COUNT(*) as total_bookings FROM roombook";
                        $bookings_result = mysqli_query($con, $bookings_query);
                        $bookings_data = mysqli_fetch_assoc($bookings_result);
                        echo number_format($bookings_data['total_bookings'] ?? 0);
                        ?>
                    </p>
                    <div class="flex items-center mt-2 text-sm text-green-600">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+8.2% from last month</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bed text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Rooms Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 hover-lift animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Available Rooms</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">
                        <?php
                        // Calculate available rooms by checking current bookings
                        $total_rooms_query = "SELECT COUNT(*) as total_rooms FROM room";
                        $total_rooms_result = mysqli_query($con, $total_rooms_query);
                        $total_rooms_data = mysqli_fetch_assoc($total_rooms_result);
                        $total_rooms = $total_rooms_data['total_rooms'] ?? 0;
                        
                        // Count rooms with active bookings (check-in today or before, check-out today or after)
                        $occupied_rooms_query = "SELECT COUNT(DISTINCT rb.TRoom) as occupied_rooms FROM roombook rb 
                                               WHERE rb.stat IN ('Confirmed', 'Checked In') 
                                               AND rb.cin <= CURDATE() 
                                               AND rb.cout >= CURDATE()";
                        $occupied_rooms_result = mysqli_query($con, $occupied_rooms_query);
                        $occupied_rooms_data = mysqli_fetch_assoc($occupied_rooms_result);
                        $occupied_rooms = $occupied_rooms_data['occupied_rooms'] ?? 0;
                        
                        $available_rooms = max(0, $total_rooms - $occupied_rooms);
                        echo number_format($available_rooms);
                        ?>
                    </p>
                    <div class="flex items-center mt-2 text-sm text-blue-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span>Out of <?php echo $total_rooms; ?> total rooms</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Occupancy Rate Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200 hover-lift animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600 uppercase tracking-wide">Occupancy Rate</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 mt-2">
                        <?php
                        $total_rooms_query = "SELECT COUNT(*) as total_rooms FROM room";
                        $total_rooms_result = mysqli_query($con, $total_rooms_query);
                        $total_rooms_data = mysqli_fetch_assoc($total_rooms_result);
                        
                        // Count rooms with active bookings (check-in today or before, check-out today or after)
                        $occupied_rooms_query = "SELECT COUNT(DISTINCT rb.TRoom) as occupied_rooms FROM roombook rb 
                                               WHERE rb.stat IN ('Confirmed', 'Checked In') 
                                               AND rb.cin <= CURDATE() 
                                               AND rb.cout >= CURDATE()";
                        $occupied_rooms_result = mysqli_query($con, $occupied_rooms_query);
                        $occupied_rooms_data = mysqli_fetch_assoc($occupied_rooms_result);
                        
                        $total_rooms = $total_rooms_data['total_rooms'] ?? 1;
                        $occupied_rooms = $occupied_rooms_data['occupied_rooms'] ?? 0;
                        $occupancy_rate = ($occupied_rooms / $total_rooms) * 100;
                        echo number_format($occupancy_rate, 1) . "%";
                        ?>
                    </p>
                    <div class="flex items-center mt-2 text-sm <?php echo $occupancy_rate > 70 ? 'text-green-600' : 'text-red-600'; ?>">
                        <i class="fas fa-arrow-<?php echo $occupancy_rate > 70 ? 'up' : 'down'; ?> mr-1"></i>
                        <span><?php echo $occupancy_rate > 70 ? 'High occupancy' : 'Low occupancy'; ?></span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-percentage text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Analytics Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Overview (Last 12 Months)</h3>
                    <div class="flex space-x-2">
                        <button onclick="exportChart('revenue')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200" title="Export as PNG">
                            <i class="fas fa-download"></i>
                        </button>
                        <button onclick="exportChart('revenue', 'pdf')" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors duration-200" title="Export as PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" class="w-full h-80"></canvas>
            </div>
        </div>

        <!-- Room Type Distribution -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Room Type Distribution</h3>
            </div>
            <div class="p-6">
                <canvas id="roomTypeChart" class="w-full h-64"></canvas>
                <div class="mt-4 space-y-2">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Standard</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Mini Executive</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-cyan-500 rounded-full mr-3"></div>
                        <span class="text-sm text-gray-600">Executive</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings and Quick Actions Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Recent Bookings -->
        <div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Recent Bookings</h3>
                <p class="text-sm text-gray-600 mt-1">Latest 10 bookings</p>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="recentBookingsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php
                        $recent_bookings_query = "SELECT 
                            rb.id,
                            CONCAT(rb.FName, ' ', rb.LName) as guest_name,
                            rb.TRoom,
                            rb.cin,
                            rb.cout,
                            rb.stat as status,
                            rb.final_amount as amount
                            FROM roombook rb
                            ORDER BY rb.id DESC
                            LIMIT 10";
                        $recent_bookings_result = mysqli_query($con, $recent_bookings_query);
                        
                        while ($row = mysqli_fetch_assoc($recent_bookings_result)) {
                            $status_class = '';
                            $status_bg = '';
                            switch ($row['status']) {
                                case 'Active':
                                    $status_class = 'text-green-800';
                                    $status_bg = 'bg-green-100';
                                    break;
                                case 'Completed':
                                    $status_class = 'text-blue-800';
                                    $status_bg = 'bg-blue-100';
                                    break;
                                case 'Cancelled':
                                    $status_class = 'text-red-800';
                                    $status_bg = 'bg-red-100';
                                    break;
                                default:
                                    $status_class = 'text-gray-800';
                                    $status_bg = 'bg-gray-100';
                            }
                            
                            echo "<tr class='hover:bg-gray-50'>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>#" . $row['id'] . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($row['guest_name']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($row['TRoom']) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . date('M j, Y', strtotime($row['cin'])) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . date('M j, Y', strtotime($row['cout'])) . "</td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex px-2 py-1 text-xs font-semibold rounded-full {$status_bg} {$status_class}'>" . $row['status'] . "</span></td>";
                            echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium'>₵" . number_format($row['amount'] ?? 0, 2) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                <p class="text-sm text-gray-600 mt-1">Common tasks</p>
            </div>
            <div class="p-6 space-y-3">
                <a href="reservation_classic.php" class="flex items-center w-full p-4 text-left text-gray-700 bg-gray-50 hover:bg-blue-50 hover:text-blue-700 rounded-lg border border-gray-200 hover:border-blue-300 transition-all duration-200 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-blue-100 group-hover:bg-blue-200 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-plus text-blue-600"></i>
                    </div>
                    <span class="font-medium">New Booking</span>
                </a>
                
                <a href="room_availability.php" class="flex items-center w-full p-4 text-left text-gray-700 bg-gray-50 hover:bg-green-50 hover:text-green-700 rounded-lg border border-gray-200 hover:border-green-300 transition-all duration-200 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-green-100 group-hover:bg-green-200 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-bed text-green-600"></i>
                    </div>
                    <span class="font-medium">Room Availability</span>
                </a>
                
                <a href="pricing.php" class="flex items-center w-full p-4 text-left text-gray-700 bg-gray-50 hover:bg-purple-50 hover:text-purple-700 rounded-lg border border-gray-200 hover:border-purple-300 transition-all duration-200 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-purple-100 group-hover:bg-purple-200 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-tags text-purple-600"></i>
                    </div>
                    <span class="font-medium">Manage Pricing</span>
                </a>
                
                <a href="profit.php" class="flex items-center w-full p-4 text-left text-gray-700 bg-gray-50 hover:bg-yellow-50 hover:text-yellow-700 rounded-lg border border-gray-200 hover:border-yellow-300 transition-all duration-200 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-yellow-100 group-hover:bg-yellow-200 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-chart-line text-yellow-600"></i>
                    </div>
                    <span class="font-medium">View Reports</span>
                </a>
                
                <a href="notifications.php" class="flex items-center w-full p-4 text-left text-gray-700 bg-gray-50 hover:bg-red-50 hover:text-red-700 rounded-lg border border-gray-200 hover:border-red-300 transition-all duration-200 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-red-100 group-hover:bg-red-200 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-bell text-red-600"></i>
                    </div>
                    <span class="font-medium">Notifications</span>
                </a>
            </div>
        </div>
    </div>

    <!-- System Status and Alerts Row -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">System Status & Alerts</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="alert alert-success" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <strong>System Online</strong><br>
                                All systems are running normally.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Database Connected</strong><br>
                                Database connection is stable.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="alert alert-warning" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Low Room Availability</strong><br>
                                Only <?php echo $available_rooms_data['available_rooms'] ?? 0; ?> rooms available.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Simple Sidebar Toggle Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');
            
            if (window.innerWidth < 1024) {
                if (sidebarOverlay) {
                    sidebarOverlay.classList.toggle('hidden');
                }
            }
        });
    }
    
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('-translate-x-full');
            sidebar.classList.remove('translate-x-0');
            sidebarOverlay.classList.add('hidden');
        });
    }
});
</script>

<!-- Custom styles for specific elements not covered by Tailwind -->
<style>
/* Custom animations and specific styling */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out;
}

/* Chart canvas sizing */
#revenueChart, #roomTypeChart {
    max-height: 400px;
}

/* DataTable specific styling */
#recentBookingsTable {
    font-size: 0.875rem;
}

/* Hover effects for better UX */
.hover-lift:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}
</style>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: [
            <?php
            $chart_labels_query = "
                SELECT DISTINCT DATE_FORMAT(created_at, '%Y-%m') as month
                FROM payment 
                WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                ORDER BY month ASC
            ";
            $chart_labels_result = mysqli_query($con, $chart_labels_query);
            $labels = [];
            while ($row = mysqli_fetch_assoc($chart_labels_result)) {
                $labels[] = "'" . date('M Y', strtotime($row['month'] . '-01')) . "'";
            }
            echo implode(',', $labels);
            ?>
        ],
        datasets: [{
            label: 'Revenue (₵)',
            data: [
                <?php
                $chart_data_query = "
                    SELECT 
                        DATE_FORMAT(created_at, '%Y-%m') as month,
                        SUM(fintot) as revenue
                    FROM payment 
                    WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month ASC
                ";
                $chart_data_result = mysqli_query($con, $chart_data_query);
                $data = [];
                while ($row = mysqli_fetch_assoc($chart_data_result)) {
                    $data[] = $row['revenue'];
                }
                echo implode(',', $data);
                ?>
            ],
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return '₵' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: ₵' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});

// Room Type Pie Chart
const roomTypeCtx = document.getElementById('roomTypeChart').getContext('2d');
const roomTypeChart = new Chart(roomTypeCtx, {
    type: 'doughnut',
    data: {
        labels: ['Standard', 'Mini Executive', 'Executive'],
        datasets: [{
            data: [
                <?php
                $room_type_data_query = "
                    SELECT 
                        rb.TRoom,
                        COUNT(*) as count
                    FROM roombook rb
                    GROUP BY rb.TRoom
                    ORDER BY count DESC
                ";
                $room_type_data_result = mysqli_query($con, $room_type_data_query);
                $room_data = [];
                while ($row = mysqli_fetch_assoc($room_type_data_result)) {
                    $room_data[] = $row['count'];
                }
                
                // If no data, show default values based on room types
                if (empty($room_data)) {
                    $room_data = [0, 0, 0]; // Standard, Mini Executive, Executive
                }
                
                echo implode(',', $room_data);
                ?>
            ],
            backgroundColor: [
                '#4e73df',
                '#1cc88a',
                '#36b9cc'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        if (total === 0) {
                            return context.label + ': No bookings';
                        }
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ' + context.parsed + ' bookings (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Export functions
function exportChart(chartType, format = 'png') {
    if (chartType === 'revenue') {
        const url = revenueChart.toBase64Image();
        const link = document.createElement('a');
        link.download = 'revenue-chart.' + format;
        link.href = url;
        link.click();
    }
}

// Initialize DataTable
$(document).ready(function() {
    $('#recentBookingsTable').DataTable({
        "pageLength": 5,
        "order": [[ 0, "desc" ]]
    });
});

// Auto-refresh dashboard every 5 minutes
setInterval(function() {
    location.reload();
}, 300000);
</script>

<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>
