<?php
session_start();
if(!isset($_SESSION["user"])) {
    header("location:index.php");
}

include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('room_management');
?>

<?php
// Start admin page with components
startUnifiedAdminPage('Room Allocation', 'Manage room assignments and availability by type');
?>

<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex items-center justify-between py-6">
        <div class="flex items-center gap-3">
            <i class="fa fa-bed text-blue-600"></i>
            <h1 class="text-2xl font-semibold text-gray-900">Room Allocation</h1>
        </div>
        <div class="flex gap-3">
            <a href="room.php" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                <i class="fa fa-arrow-left mr-2"></i>
                Back to Rooms
            </a>
        </div>
    </div>

    <!-- Room Type Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <?php
        $room_types = ['Standard', 'Mini Executive', 'Executive'];
        foreach($room_types as $type) {
            $total_query = "SELECT COUNT(*) as total FROM room WHERE type = '$type'";
            $total_result = mysqli_query($con, $total_query);
            $total = mysqli_fetch_assoc($total_result)['total'];
            
            $available_query = "SELECT COUNT(*) as available 
                               FROM room r
                               WHERE r.type = '$type' 
                               AND r.place = 'Free' 
                               AND r.status = 'Available'
                               AND (r.cusid IS NULL OR r.cusid NOT IN (
                                   SELECT id FROM roombook WHERE stat NOT IN ('Cancelled', 'Checked Out') AND cout >= CURDATE()
                               ))";
            $available_result = mysqli_query($con, $available_query);
            $available = mysqli_fetch_assoc($available_result)['available'];
            
            // Count occupied rooms with active bookings
            $occupied_query = "SELECT COUNT(*) as occupied 
                              FROM room r
                              INNER JOIN roombook rb ON rb.id = r.cusid
                              WHERE r.type = '$type'
                              AND r.status = 'Occupied'
                              AND r.place = 'NotFree'
                              AND rb.stat NOT IN ('Cancelled', 'Checked Out')
                              AND rb.cout >= CURDATE()";
            $occupied_result = mysqli_query($con, $occupied_query);
            $occupied = mysqli_fetch_assoc($occupied_result)['occupied'];
            $occupancy_rate = $total > 0 ? round(($occupied / $total) * 100, 1) : 0;
            
            $color_class = $type === 'Executive' ? 'purple' : ($type === 'Mini Executive' ? 'blue' : 'green');
            ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900"><?php echo $type; ?> Rooms</h3>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-<?php echo $color_class; ?>-100 text-<?php echo $color_class; ?>-800">
                        <?php echo $total; ?> Total
                    </span>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Available:</span>
                        <span class="font-semibold text-green-600"><?php echo $available; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Occupied:</span>
                        <span class="font-semibold text-red-600"><?php echo $occupied; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">Occupancy:</span>
                        <span class="font-semibold text-gray-900"><?php echo $occupancy_rate; ?>%</span>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Room Allocation by Type -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <?php
        foreach($room_types as $type) {
            $rooms_query = "SELECT * FROM room WHERE type = '$type' ORDER BY room_number";
            $rooms_result = mysqli_query($con, $rooms_query);
            ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900"><?php echo $type; ?> Rooms</h3>
                    <p class="text-sm text-gray-600">Click to assign to booking</p>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-3">
                        <?php while($room = mysqli_fetch_assoc($rooms_result)) {
                            // Check if room has a valid active booking
                            $has_active_booking = false;
                            if($room['cusid']) {
                                $booking_check = "SELECT id, stat, cout FROM roombook WHERE id = '{$room['cusid']}' AND stat NOT IN ('Cancelled', 'Checked Out') AND cout >= CURDATE()";
                                $booking_check_result = mysqli_query($con, $booking_check);
                                $has_active_booking = mysqli_num_rows($booking_check_result) > 0;
                            }
                            
                            $is_available = $room['place'] === 'Free' && $room['status'] === 'Available' && !$has_active_booking;
                            $is_occupied = (($room['place'] === 'NotFree' || $room['status'] === 'Occupied') && $has_active_booking);
                            $is_maintenance = $room['status'] === 'Maintenance';
                            
                            $status_class = '';
                            $status_text = '';
                            $status_icon = '';
                            
                            if($is_available) {
                                $status_class = 'bg-green-100 text-green-800 border-green-200';
                                $status_text = 'Available';
                                $status_icon = 'fa-check-circle';
                            } elseif($is_occupied) {
                                $status_class = 'bg-red-100 text-red-800 border-red-200';
                                $status_text = 'Occupied';
                                $status_icon = 'fa-user';
                            } elseif($is_maintenance) {
                                $status_class = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                                $status_text = 'Maintenance';
                                $status_icon = 'fa-tools';
                            }
                            ?>
                            <div class="room-card p-3 border rounded-lg cursor-pointer hover:shadow-md transition-all duration-200 <?php echo $status_class; ?>" 
                                 data-room-id="<?php echo $room['id']; ?>" 
                                 data-room-number="<?php echo $room['room_number']; ?>"
                                 data-room-type="<?php echo $room['type']; ?>"
                                 data-available="<?php echo $is_available ? 'true' : 'false'; ?>">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-sm"><?php echo $room['room_number']; ?></div>
                                        <div class="text-xs text-gray-600"><?php echo $room['bedding']; ?></div>
                                    </div>
                                    <i class="fa <?php echo $status_icon; ?> text-sm"></i>
                                </div>
                                <div class="mt-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <!-- Assigned Bookings -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Assigned Bookings</h3>
            <p class="text-sm text-gray-600">Bookings with assigned rooms</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Room</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $assigned_bookings_query = "SELECT * FROM roombook WHERE stat IN ('Pending', 'Confirmed', 'Checked In', 'Confirm') AND assigned_room_id IS NOT NULL AND assigned_room_id != '' AND cout >= CURDATE() ORDER BY id DESC LIMIT 10";
                    $assigned_bookings_result = mysqli_query($con, $assigned_bookings_query);
                    
                    while($booking = mysqli_fetch_assoc($assigned_bookings_result)) {
                        // Status badge styling
                        $status = $booking['stat'];
                        if($status === 'Confirm' || $status === 'Confirmed') {
                            $status_class = 'bg-green-100 text-green-800';
                        } elseif($status === 'Checked In') {
                            $status_class = 'bg-blue-100 text-blue-800';
                        } elseif($status === 'Pending') {
                            $status_class = 'bg-yellow-100 text-yellow-800';
                        } else {
                            $status_class = 'bg-gray-100 text-gray-800';
                        }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?php echo $booking['id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $booking['FName'] . ' ' . $booking['LName']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $booking['TRoom']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-blue-600"><?php echo $booking['assigned_room_number'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('M j, Y', strtotime($booking['cin'])); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('M j, Y', strtotime($booking['cout'])); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_class; ?>">
                                    <?php echo $booking['stat']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2 flex-wrap">
                                    <?php if($status !== 'Confirmed' && $status !== 'Cancelled' && $status !== 'Checked Out'): ?>
                                        <button class="text-green-600 hover:text-green-900 confirm-booking-btn" 
                                                data-booking-id="<?php echo $booking['id']; ?>"
                                                title="Confirm Booking">
                                            <i class="fa fa-check-circle"></i> Confirm
                                        </button>
                                    <?php endif; ?>
                                    <?php if($status !== 'Cancelled' && $status !== 'Checked Out'): ?>
                                        <button class="text-red-600 hover:text-red-900 cancel-booking-btn" 
                                                data-booking-id="<?php echo $booking['id']; ?>"
                                                title="Cancel Booking">
                                            <i class="fa fa-times-circle"></i> Cancel
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Bookings -->
    <div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Pending Bookings (Need Room Assignment)</h3>
            <p class="text-sm text-gray-600">Assign rooms to confirmed bookings</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php
                    $bookings_query = "SELECT * FROM roombook WHERE stat IN ('Pending', 'Confirmed', 'Checked In', 'Confirm') AND (assigned_room_id IS NULL OR assigned_room_id = '') AND cout >= CURDATE() ORDER BY id DESC LIMIT 10";
                    $bookings_result = mysqli_query($con, $bookings_query);
                    
                    while($booking = mysqli_fetch_assoc($bookings_result)) {
                        // Status badge styling
                        $status = $booking['stat'];
                        if($status === 'Confirm' || $status === 'Confirmed') {
                            $status_class = 'bg-green-100 text-green-800';
                        } elseif($status === 'Checked In') {
                            $status_class = 'bg-blue-100 text-blue-800';
                        } elseif($status === 'Pending') {
                            $status_class = 'bg-yellow-100 text-yellow-800';
                        } else {
                            $status_class = 'bg-gray-100 text-gray-800';
                        }
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?php echo $booking['id']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $booking['FName'] . ' ' . $booking['LName']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $booking['TRoom']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('M j, Y', strtotime($booking['cin'])); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo date('M j, Y', strtotime($booking['cout'])); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?php echo $status_class; ?>">
                                    <?php echo $booking['stat']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-2 flex-wrap">
                                    <button class="text-blue-600 hover:text-blue-900 assign-room-btn" 
                                            data-booking-id="<?php echo $booking['id']; ?>"
                                            data-room-type="<?php echo $booking['TRoom']; ?>">
                                        <i class="fa fa-bed"></i> Assign Room
                                    </button>
                                    <?php if($status === 'Pending'): ?>
                                        <button class="text-green-600 hover:text-green-900 confirm-booking-btn" 
                                                data-booking-id="<?php echo $booking['id']; ?>"
                                                title="Confirm Booking">
                                            <i class="fa fa-check-circle"></i> Confirm
                                        </button>
                                    <?php endif; ?>
                                    <?php if($status !== 'Cancelled' && $status !== 'Checked Out'): ?>
                                        <button class="text-red-600 hover:text-red-900 cancel-booking-btn" 
                                                data-booking-id="<?php echo $booking['id']; ?>"
                                                title="Cancel Booking">
                                            <i class="fa fa-times-circle"></i> Cancel
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Room Assignment Modal -->
<div id="roomAssignmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Assign Room</h3>
                <p class="text-sm text-gray-600">Select a room for this booking</p>
            </div>
            <div class="p-6">
                <div id="availableRooms" class="space-y-2 max-h-64 overflow-y-auto">
                    <!-- Available rooms will be populated here -->
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button id="cancelAssignment" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                    Cancel
                </button>
                <button id="confirmAssignment" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                    Assign Room
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let selectedRoomId = null;
    let currentBookingId = null;
    
    // Room assignment functionality
    document.querySelectorAll('.assign-room-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            currentBookingId = this.dataset.bookingId;
            const roomType = this.dataset.roomType;
            
            // Check if booking already has assigned room
            fetch(`ajax/get_booking_details.php?id=${currentBookingId}`)
                .then(async response => {
                    if (!response.ok) {
                        const txt = await response.text();
                        throw new Error(txt || `HTTP ${response.status}`);
                    }
                    try {
                        return await response.json();
                    } catch (e) {
                        const txt = await response.text();
                        throw new Error(txt || 'Invalid JSON response');
                    }
                })
                .then(bookingData => {
                    const container = document.getElementById('availableRooms');
                    
                    if (bookingData.assigned_room_number) {
                        // Show already assigned room
                        container.innerHTML = `
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fa fa-info-circle text-blue-600 mr-3"></i>
                                    <div>
                                        <div class="font-semibold text-blue-800">Already Assigned</div>
                                        <div class="text-sm text-blue-600">Room: ${bookingData.assigned_room_number}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                        document.getElementById('confirmAssignment').style.display = 'none';
                    } else {
                        // Fetch available rooms for this type
                        fetch(`ajax/get_available_rooms.php?type=${roomType}`)
                            .then(async response => {
                                if (!response.ok) {
                                    const txt = await response.text();
                                    throw new Error(txt || `HTTP ${response.status}`);
                                }
                                try {
                                    return await response.json();
                                } catch (e) {
                                    const txt = await response.text();
                                    throw new Error(txt || 'Invalid JSON response');
                                }
                            })
                            .then(data => {
                                container.innerHTML = '';
                                
                                if (data.rooms.length === 0) {
                                    container.innerHTML = '<p class="text-gray-500 text-center">No available rooms of this type</p>';
                                } else {
                                    data.rooms.forEach(room => {
                                        const roomDiv = document.createElement('div');
                                        roomDiv.className = 'p-3 border rounded-lg cursor-pointer hover:bg-blue-50 room-option';
                                        roomDiv.dataset.roomId = room.id;
                                        roomDiv.innerHTML = `
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <div class="font-semibold">${room.room_number}</div>
                                                    <div class="text-sm text-gray-600">${room.bedding}</div>
                                                </div>
                                                <i class="fa fa-check-circle text-green-600"></i>
                                            </div>
                                        `;
                                        
                                        roomDiv.addEventListener('click', function() {
                                            document.querySelectorAll('.room-option').forEach(opt => opt.classList.remove('bg-blue-100', 'border-blue-300'));
                                            this.classList.add('bg-blue-100', 'border-blue-300');
                                            selectedRoomId = this.dataset.roomId;
                                        });
                                        
                                        container.appendChild(roomDiv);
                                    });
                                }
                                document.getElementById('confirmAssignment').style.display = 'block';
                            });
                    }
                    
                    document.getElementById('roomAssignmentModal').classList.remove('hidden');
                });
        });
    });
    
    // Confirm assignment
    document.getElementById('confirmAssignment').addEventListener('click', function() {
        if (selectedRoomId && currentBookingId) {
            fetch('ajax/assign_room.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    bookingId: currentBookingId,
                    roomId: selectedRoomId
                })
            })
            .then(async response => {
                if (!response.ok) {
                    const txt = await response.text();
                    throw new Error(txt || `HTTP ${response.status}`);
                }
                try {
                    return await response.json();
                } catch (e) {
                    const txt = await response.text();
                    throw new Error(txt || 'Invalid JSON response');
                }
            })
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error assigning room: ' + data.message);
                }
            }).catch(err => alert('Error: ' + err.message));
        }
    });
    
    // Cancel assignment
    document.getElementById('cancelAssignment').addEventListener('click', function() {
        document.getElementById('roomAssignmentModal').classList.add('hidden');
        selectedRoomId = null;
        currentBookingId = null;
    });
    
    // Handle confirm booking
    document.addEventListener('click', function(e) {
        if (e.target.closest('.confirm-booking-btn')) {
            const btn = e.target.closest('.confirm-booking-btn');
            const bookingId = btn.getAttribute('data-booking-id');
            
            if (confirm('Are you sure you want to confirm this booking?')) {
                fetch('ajax/update_booking_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        bookingId: bookingId,
                        action: 'confirm'
                    })
                })
                .then(async response => {
                    if (!response.ok) {
                        const txt = await response.text();
                        throw new Error(txt || `HTTP ${response.status}`);
                    }
                    try {
                        return await response.json();
                    } catch (e) {
                        const txt = await response.text();
                        throw new Error(txt || 'Invalid JSON response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        alert('Booking confirmed successfully!');
                        location.reload();
                    } else {
                        alert('Error confirming booking: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
        
        // Handle cancel booking
        if (e.target.closest('.cancel-booking-btn')) {
            const btn = e.target.closest('.cancel-booking-btn');
            const bookingId = btn.getAttribute('data-booking-id');
            
            if (confirm('Are you sure you want to cancel this booking? This will free the assigned room if any.')) {
                fetch('ajax/update_booking_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        bookingId: bookingId,
                        action: 'cancel'
                    })
                })
                .then(async response => {
                    if (!response.ok) {
                        const txt = await response.text();
                        throw new Error(txt || `HTTP ${response.status}`);
                    }
                    try {
                        return await response.json();
                    } catch (e) {
                        const txt = await response.text();
                        throw new Error(txt || 'Invalid JSON response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        alert('Booking cancelled successfully!');
                        location.reload();
                    } else {
                        alert('Error cancelling booking: ' + data.message);
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                });
            }
        }
    });
});
</script>

<?php
// End admin page with components
endUnifiedAdminPage();
?>
