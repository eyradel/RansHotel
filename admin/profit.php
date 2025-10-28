<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

// Include access control system
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('profit');

// Start admin page with components
startUnifiedAdminPage('Profit Reports', 'View profit details and financial reports for RansHotel');

// Include database connection
include('db.php');
?>

<!-- Enhanced Profit Reports Content -->
<div class="container-fluid">
    <!-- Statistics Cards Grid -->
    <div class="stats-grid">
        <!-- Revenue Card -->
        <div class="stat-card revenue-card">
            <div class="stat-icon">
                <i class="fas fa-dollar-sign"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">
                    <?php
                    $revenue_query = "SELECT SUM(fintot) as total_revenue FROM payment";
                    $revenue_result = mysqli_query($con, $revenue_query);
                    $revenue_data = mysqli_fetch_assoc($revenue_result);
                    echo "₵" . number_format($revenue_data['total_revenue'] ?? 0, 2);
                    ?>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 12.5%
                </div>
            </div>
        </div>

        <!-- Monthly Revenue Card -->
        <div class="stat-card monthly-card">
            <div class="stat-icon">
                <i class="fas fa-calendar"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Monthly Revenue</div>
                <div class="stat-value">
                    <?php
                    $monthly_revenue_query = "SELECT SUM(fintot) as monthly_revenue FROM payment WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
                    $monthly_revenue_result = mysqli_query($con, $monthly_revenue_query);
                    $monthly_revenue_data = mysqli_fetch_assoc($monthly_revenue_result);
                    echo "₵" . number_format($monthly_revenue_data['monthly_revenue'] ?? 0, 2);
                    ?>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 8.2%
                </div>
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="stat-card bookings-card">
            <div class="stat-icon">
                <i class="fas fa-bed"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value">
                    <?php
                    $bookings_query = "SELECT COUNT(*) as total_bookings FROM roombook";
                    $bookings_result = mysqli_query($con, $bookings_query);
                    $bookings_data = mysqli_fetch_assoc($bookings_result);
                    echo number_format($bookings_data['total_bookings'] ?? 0);
                    ?>
                </div>
                <div class="stat-change positive">
                    <i class="fas fa-arrow-up"></i> 15.3%
                </div>
            </div>
        </div>

        <!-- Average Value Card -->
        <div class="stat-card average-card">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-label">Avg Booking Value</div>
                <div class="stat-value">
                    <?php
                    $avg_booking_query = "SELECT AVG(fintot) as avg_booking FROM payment";
                    $avg_booking_result = mysqli_query($con, $avg_booking_query);
                    $avg_booking_data = mysqli_fetch_assoc($avg_booking_result);
                    echo "₵" . number_format($avg_booking_data['avg_booking'] ?? 0, 2);
                    ?>
                </div>
                <div class="stat-change negative">
                    <i class="fas fa-arrow-down"></i> 2.1%
                </div>
            </div>
        </div>
            </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <!-- Revenue Chart -->
        <div class="chart-container main-chart">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Revenue Overview</h3>
                    <div class="chart-actions">
                        <button class="btn-icon" onclick="exportChart('revenue')" title="Export PNG">
                            <i class="fas fa-download"></i>
                        </button>
                        <button class="btn-icon" onclick="exportChart('revenue', 'pdf')" title="Export PDF">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                    </div>
                </div> 
                <div class="chart-content">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Room Type Revenue Pie Chart -->
        <div class="chart-container side-chart">
            <div class="chart-card">
                <div class="chart-header">
                    <h3 class="chart-title">Revenue by Room Type</h3>
                </div>
                <div class="chart-content">
                    <canvas id="roomTypeChart"></canvas>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color primary"></span>
                            <span class="legend-label">Standard</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color success"></span>
                            <span class="legend-label">Mini Executive</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color info"></span>
                            <span class="legend-label">Executive</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Grid -->
    <div class="reports-grid">
        <!-- Monthly Revenue Table -->
        <div class="report-container">
            <div class="report-card">
                <div class="report-header">
                    <h3 class="report-title">Monthly Revenue Breakdown</h3>
                    <div class="report-period">Last 12 Months</div>
                </div>
                <div class="report-content">
                    <div class="table-container">
                        <table class="data-table" id="monthlyRevenueTable">
                                    <thead>
                                        <tr>
                                    <th>Month</th>
                                    <th>Revenue</th>
                                    <th>Bookings</th>
                                    <th>Avg. Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									<?php
                                $monthly_breakdown_query = "
                                    SELECT 
                                        DATE_FORMAT(created_at, '%Y-%m') as month,
                                        SUM(fintot) as revenue,
                                        COUNT(*) as bookings,
                                        AVG(fintot) as avg_value
                                    FROM payment 
                                    WHERE created_at >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                                    ORDER BY month DESC
                                ";
                                $monthly_breakdown_result = mysqli_query($con, $monthly_breakdown_query);
                                
                                while ($row = mysqli_fetch_assoc($monthly_breakdown_result)) {
                                    echo "<tr>";
                                    echo "<td>" . date('F Y', strtotime($row['month'] . '-01')) . "</td>";
                                    echo "<td>₵" . number_format($row['revenue'], 2) . "</td>";
                                    echo "<td>" . number_format($row['bookings']) . "</td>";
                                    echo "<td>₵" . number_format($row['avg_value'], 2) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                                    </tbody>
                                </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Performing Rooms -->
        <div class="report-container">
            <div class="report-card">
                <div class="report-header">
                    <h3 class="report-title">Top Performing Room Types</h3>
                    <div class="report-period">Performance Analysis</div>
                </div>
                <div class="report-content">
                    <div class="table-container">
                        <table class="data-table" id="topRoomsTable">
                            <thead>
                                <tr>
                                    <th>Room Type</th>
                                    <th>Revenue</th>
                                    <th>Bookings</th>
                                    <th>Occupancy %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $top_rooms_query = "
                                    SELECT 
                                        rb.TRoom as room_type,
                                        SUM(rb.final_amount) as revenue,
                                        COUNT(rb.id) as bookings,
                                        ROUND((COUNT(rb.id) / (SELECT COUNT(*) FROM room) * 100), 2) as occupancy_rate
                                    FROM roombook rb
                                    GROUP BY rb.TRoom
                                    ORDER BY revenue DESC
                                ";
                                $top_rooms_result = mysqli_query($con, $top_rooms_query);
                                
                                while ($row = mysqli_fetch_assoc($top_rooms_result)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['room_type']) . "</td>";
                                    echo "<td>₵" . number_format($row['revenue'] ?? 0, 2) . "</td>";
                                    echo "<td>" . number_format($row['bookings']) . "</td>";
                                    echo "<td>" . number_format($row['occupancy_rate'], 1) . "%</td>";
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
            </div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

<style>
/* Modern Grid Layout Styles */
.container-fluid {
    padding: 2rem;
    background: #f8fafc;
    min-height: 100vh;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
}

.revenue-card::before { background: linear-gradient(90deg, #3b82f6, #1d4ed8); }
.monthly-card::before { background: linear-gradient(90deg, #10b981, #059669); }
.bookings-card::before { background: linear-gradient(90deg, #8b5cf6, #7c3aed); }
.average-card::before { background: linear-gradient(90deg, #f59e0b, #d97706); }

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1rem;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
}

.revenue-card .stat-icon { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #3b82f6; }
.monthly-card .stat-icon { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #10b981; }
.bookings-card .stat-icon { background: linear-gradient(135deg, #e9d5ff, #ddd6fe); color: #8b5cf6; }
.average-card .stat-icon { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #f59e0b; }

.stat-content {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.stat-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #1e293b;
    line-height: 1;
}

.stat-change {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.875rem;
    font-weight: 500;
}

.stat-change.positive { color: #10b981; }
.stat-change.negative { color: #ef4444; }

/* Charts Grid */
.charts-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.chart-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.chart-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8fafc;
}

.chart-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.chart-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-icon {
    width: 32px;
    height: 32px;
    border: none;
    border-radius: 8px;
    background: #e2e8f0;
    color: #64748b;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-icon:hover {
    background: #3b82f6;
    color: white;
}

.chart-content {
    padding: 1.5rem;
    flex: 1;
    position: relative;
}

.chart-content canvas {
    max-height: 300px;
}

.chart-legend {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-top: 1rem;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.legend-color.primary { background: #3b82f6; }
.legend-color.success { background: #10b981; }
.legend-color.info { background: #06b6d4; }

.legend-label {
    font-size: 0.875rem;
    color: #64748b;
}

/* Reports Grid */
.reports-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.report-container {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
}

.report-card {
    height: 100%;
    display: flex;
    flex-direction: column;
}

.report-header {
    padding: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
    background: #f8fafc;
}

.report-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 0.25rem 0;
}

.report-period {
    font-size: 0.875rem;
    color: #64748b;
}

.report-content {
    flex: 1;
    padding: 1.5rem;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.data-table th {
    background: #f8fafc;
    color: #374151;
    font-weight: 600;
    text-align: left;
    padding: 0.75rem;
    border-bottom: 2px solid #e2e8f0;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.data-table td {
    padding: 0.75rem;
    border-bottom: 1px solid #e2e8f0;
    color: #374151;
}

.data-table tbody tr:hover {
    background: #f8fafc;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
    
    .reports-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .stat-card {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
    }
    
    .chart-header,
    .report-header {
        padding: 1rem;
    }
    
    .chart-content,
    .report-content {
        padding: 1rem;
    }
}

/* Animation */
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

.stat-card,
.chart-container,
.report-container {
    animation: fadeInUp 0.6s ease-out;
}

.stat-card:nth-child(1) { animation-delay: 0.1s; }
.stat-card:nth-child(2) { animation-delay: 0.2s; }
.stat-card:nth-child(3) { animation-delay: 0.3s; }
.stat-card:nth-child(4) { animation-delay: 0.4s; }
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
                        SUM(rb.final_amount) as revenue
                    FROM roombook rb
                    GROUP BY rb.TRoom
                    ORDER BY revenue DESC
                ";
                $room_type_data_result = mysqli_query($con, $room_type_data_query);
                $room_data = [];
                while ($row = mysqli_fetch_assoc($room_type_data_result)) {
                    $room_data[] = $row['revenue'] ?? 0;
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
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': ₵' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
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
</script>
			
<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>