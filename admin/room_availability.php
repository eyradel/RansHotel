<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('room_availability');

// Start admin page with unified layout
startUnifiedAdminPage('Room Availability', 'Real-time room availability tracking and management for RansHotel');
?>

<!-- Room Availability Management Content -->
<div class="container-fluid">
    <!-- Availability Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $total_rooms_query = "SELECT COUNT(*) as total_rooms FROM room";
                                $total_rooms_result = mysqli_query($con, $total_rooms_query);
                                $total_rooms_data = mysqli_fetch_assoc($total_rooms_result);
                                echo number_format($total_rooms_data['total_rooms'] ?? 0);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bed fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Available Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $available_rooms_query = "SELECT COUNT(*) as available_rooms FROM room WHERE status = 'Available'";
                                $available_rooms_result = mysqli_query($con, $available_rooms_query);
                                $available_rooms_data = mysqli_fetch_assoc($available_rooms_result);
                                echo number_format($available_rooms_data['available_rooms'] ?? 0);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Occupied Rooms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $occupied_rooms_query = "SELECT COUNT(*) as occupied_rooms FROM room WHERE status = 'Occupied'";
                                $occupied_rooms_result = mysqli_query($con, $occupied_rooms_query);
                                $occupied_rooms_data = mysqli_fetch_assoc($occupied_rooms_result);
                                echo number_format($occupied_rooms_data['occupied_rooms'] ?? 0);
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Occupancy Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php
                                $total_rooms = $total_rooms_data['total_rooms'] ?? 1;
                                $occupied_rooms = $occupied_rooms_data['occupied_rooms'] ?? 0;
                                $occupancy_rate = ($occupied_rooms / $total_rooms) * 100;
                                echo number_format($occupancy_rate, 1) . "%";
                                ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-percentage fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Room Availability by Type -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Room Availability by Type</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        $room_types = ['Standard', 'Mini Executive', 'Executive'];
                        $colors = ['primary', 'success', 'info'];
                        
                        foreach ($room_types as $index => $room_type) {
                            $room_type_query = "SELECT 
                                COUNT(*) as total,
                                SUM(CASE WHEN status = 'Available' THEN 1 ELSE 0 END) as available,
                                SUM(CASE WHEN status = 'Occupied' THEN 1 ELSE 0 END) as occupied,
                                SUM(CASE WHEN status = 'Maintenance' THEN 1 ELSE 0 END) as maintenance
                                FROM room WHERE troom = '$room_type'";
                            $room_type_result = mysqli_query($con, $room_type_query);
                            $room_type_data = mysqli_fetch_assoc($room_type_result);
                            
                            $total = $room_type_data['total'] ?? 0;
                            $available = $room_type_data['available'] ?? 0;
                            $occupied = $room_type_data['occupied'] ?? 0;
                            $maintenance = $room_type_data['maintenance'] ?? 0;
                            $color = $colors[$index];
                            
                            echo "<div class='col-md-4 mb-3'>";
                            echo "<div class='card border-left-{$color} shadow h-100'>";
                            echo "<div class='card-body'>";
                            echo "<div class='row no-gutters align-items-center'>";
                            echo "<div class='col mr-2'>";
                            echo "<div class='text-xs font-weight-bold text-{$color} text-uppercase mb-1'>{$room_type}</div>";
                            echo "<div class='h5 mb-0 font-weight-bold text-gray-800'>{$available}/{$total} Available</div>";
                            echo "<div class='text-xs text-gray-600'>";
                            echo "Occupied: {$occupied} | Maintenance: {$maintenance}";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='col-auto'>";
                            echo "<i class='fas fa-bed fa-2x text-gray-300'></i>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Room Status Table -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Real-time Room Status</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                            <div class="dropdown-header">Filter Options:</div>
                            <a class="dropdown-item" href="#" onclick="filterRooms('all')">All Rooms</a>
                            <a class="dropdown-item" href="#" onclick="filterRooms('available')">Available Only</a>
                            <a class="dropdown-item" href="#" onclick="filterRooms('occupied')">Occupied Only</a>
                            <a class="dropdown-item" href="#" onclick="filterRooms('maintenance')">Maintenance Only</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="roomStatusTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Room ID</th>
                                    <th>Room Type</th>
                                    <th>Bedding</th>
                                    <th>Status</th>
                                    <th>Current Guest</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $room_status_query = "SELECT 
                                    r.id,
                                    r.troom,
                                    r.bed,
                                    r.status,
                                    rb.fname,
                                    rb.lname,
                                    rb.cin,
                                    rb.cout
                                    FROM room r
                                    LEFT JOIN roombook rb ON r.id = rb.roomid AND rb.status = 'Active'
                                    ORDER BY r.id ASC";
                                $room_status_result = mysqli_query($con, $room_status_query);
                                
                                while ($row = mysqli_fetch_assoc($room_status_result)) {
                                    $status_class = '';
                                    $status_icon = '';
                                    
                                    switch ($row['status']) {
                                        case 'Available':
                                            $status_class = 'success';
                                            $status_icon = 'fa-check-circle';
                                            break;
                                        case 'Occupied':
                                            $status_class = 'warning';
                                            $status_icon = 'fa-user';
                                            break;
                                        case 'Maintenance':
                                            $status_class = 'danger';
                                            $status_icon = 'fa-wrench';
                                            break;
                                        default:
                                            $status_class = 'secondary';
                                            $status_icon = 'fa-question-circle';
                                    }
                                    
                                    echo "<tr>";
                                    echo "<td><strong>#" . $row['id'] . "</strong></td>";
                                    echo "<td>" . htmlspecialchars($row['troom']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['bed']) . "</td>";
                                    echo "<td><span class='badge bg-{$status_class}'><i class='fas {$status_icon} me-1'></i>" . $row['status'] . "</span></td>";
                                    
                                    if ($row['fname'] && $row['lname']) {
                                        echo "<td>" . htmlspecialchars($row['fname'] . ' ' . $row['lname']) . "</td>";
                                        echo "<td>" . date('M j, Y', strtotime($row['cin'])) . "</td>";
                                        echo "<td>" . date('M j, Y', strtotime($row['cout'])) . "</td>";
                                    } else {
                                        echo "<td>-</td>";
                                        echo "<td>-</td>";
                                        echo "<td>-</td>";
                                    }
                                    
                                    echo "<td>";
                                    if ($row['status'] == 'Available') {
                                        echo "<button class='btn btn-sm btn-outline-success me-1' onclick='bookRoom({$row['id']})'><i class='fas fa-calendar-plus'></i> Book</button>";
                                    } else if ($row['status'] == 'Occupied') {
                                        echo "<button class='btn btn-sm btn-outline-info me-1' onclick='viewBooking({$row['id']})'><i class='fas fa-eye'></i> View</button>";
                                        echo "<button class='btn btn-sm btn-outline-warning' onclick='checkOut({$row['id']})'><i class='fas fa-sign-out-alt'></i> Check-out</button>";
                                    } else if ($row['status'] == 'Maintenance') {
                                        echo "<button class='btn btn-sm btn-outline-primary' onclick='completeMaintenance({$row['id']})'><i class='fas fa-check'></i> Complete</button>";
                                    }
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
    </div>

    <!-- Room Availability Calendar -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Room Availability Calendar</h6>
                </div>
                <div class="card-body">
                    <div id="availabilityCalendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Room Status Modal -->
<div class="modal fade" id="roomStatusModal" tabindex="-1" aria-labelledby="roomStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="roomStatusModalLabel">Update Room Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="roomStatusForm">
                    <input type="hidden" id="roomId" name="room_id">
                    <div class="form-group mb-3">
                        <label for="roomStatus">Room Status</label>
                        <select class="form-control" id="roomStatus" name="status" required>
                            <option value="Available">Available</option>
                            <option value="Occupied">Occupied</option>
                            <option value="Maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="statusReason">Reason (Optional)</label>
                        <textarea class="form-control" id="statusReason" name="reason" rows="3" placeholder="Enter reason for status change..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="updateRoomStatus()">Update Status</button>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.text-xs {
    font-size: 0.7rem;
}
.font-weight-bold {
    font-weight: 700 !important;
}
.text-uppercase {
    text-transform: uppercase !important;
}
.text-gray-800 {
    color: #5a5c69 !important;
}
.text-gray-300 {
    color: #dddfeb !important;
}
.text-gray-600 {
    color: #6c757d !important;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
// Room availability functions
function bookRoom(roomId) {
    window.location.href = 'reservation_classic.php?room_id=' + roomId;
}

function viewBooking(roomId) {
    // Implementation for viewing booking details
    alert('View booking functionality will be implemented for room #' + roomId);
}

function checkOut(roomId) {
    if (confirm('Are you sure you want to check out the guest from room #' + roomId + '?')) {
        // Implementation for check-out
        alert('Check-out functionality will be implemented for room #' + roomId);
    }
}

function completeMaintenance(roomId) {
    if (confirm('Mark room #' + roomId + ' as maintenance complete?')) {
        // Implementation for completing maintenance
        alert('Complete maintenance functionality will be implemented for room #' + roomId);
    }
}

function updateRoomStatus() {
    // Implementation for updating room status
    alert('Update room status functionality will be implemented');
}

function filterRooms(status) {
    // Implementation for filtering rooms
    alert('Filter rooms functionality will be implemented for status: ' + status);
}

// Initialize DataTable
$(document).ready(function() {
    $('#roomStatusTable').DataTable({
        "pageLength": 25,
        "order": [[ 0, "asc" ]],
        "columnDefs": [
            { "orderable": false, "targets": 7 }
        ]
    });
});

// Initialize FullCalendar
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('availabilityCalendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            // This will be populated with actual booking data
            {
                title: 'Room #101 - Occupied',
                start: '2024-01-15',
                end: '2024-01-18',
                color: '#f6c23e'
            },
            {
                title: 'Room #102 - Available',
                start: '2024-01-15',
                end: '2024-01-15',
                color: '#1cc88a'
            }
        ],
        eventClick: function(info) {
            alert('Event: ' + info.event.title + '\nStart: ' + info.event.start.toISOString());
        }
    });
    calendar.render();
});

// Auto-refresh room status every 30 seconds
setInterval(function() {
    // Implementation for auto-refresh
    console.log('Refreshing room status...');
}, 30000);
</script>

<?php
// End admin page with unified layout
endUnifiedAdminPage();
?>
