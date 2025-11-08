<?php
include('db.php');
include('includes/access_control.php');
include('includes/unified_layout.php');

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Automatically update room statuses when checkout dates have passed
// This runs silently in the background
try {
    $today = date('Y-m-d');
    
    // Update rooms where checkout date has passed
    $update_rooms = "UPDATE room r
                     INNER JOIN roombook rb ON rb.id = r.cusid
                     SET r.place = 'Free', 
                         r.status = 'Available', 
                         r.cusid = NULL
                     WHERE r.status = 'Occupied'
                     AND r.place = 'NotFree'
                     AND rb.cout < '$today'
                     AND rb.stat NOT IN ('Checked Out', 'Cancelled')";
    mysqli_query($con, $update_rooms);
    
    // Update booking status to Checked Out
    $update_bookings = "UPDATE roombook 
                       SET stat = 'Checked Out' 
                       WHERE stat IN ('Confirmed', 'Checked In')
                       AND cout < '$today'
                       AND stat NOT IN ('Checked Out', 'Cancelled')";
    mysqli_query($con, $update_bookings);
    
    // Auto-check-in bookings where check-in date is today or past
    $checkin_bookings = "UPDATE roombook 
                        SET stat = 'Checked In' 
                        WHERE stat = 'Confirmed' 
                        AND cin <= '$today' 
                        AND cout >= '$today'";
    mysqli_query($con, $checkin_bookings);
    
} catch (Exception $e) {
    // Silently fail - don't interrupt calendar display
    error_log('Calendar auto-update error: ' . $e->getMessage());
}

// Get user info
$user = $_SESSION['user'] ?? 'Admin';
$userRole = getCurrentUserRole();

// Start admin page with unified layout
startUnifiedAdminPage('Booking Calendar', 'RansHotel Admin - View Bookings Calendar');
?>

<!-- FullCalendar CSS -->
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.css' rel='stylesheet' />

<style>
    .calendar-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.05);
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        transition: all 0.3s ease;
    }

    .calendar-container:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12), 0 2px 6px rgba(0, 0, 0, 0.08);
    }

    .calendar-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 20px;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
    }

    .calendar-header > div:first-child {
        flex: 1;
        min-width: 250px;
    }

    .calendar-header h2 {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        letter-spacing: -0.5px;
    }

    .calendar-header p {
        color: #6b7280;
        font-size: 0.95rem;
        margin: 0;
    }

    .calendar-filters {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: center;
        background: #f9fafb;
        padding: 12px;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .filter-group {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 8px 12px;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.2s ease;
    }

    .filter-group:hover {
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transform: translateY(-1px);
    }

    .filter-group label {
        font-weight: 600;
        color: #374151;
        font-size: 13px;
        white-space: nowrap;
    }

    .filter-group select {
        padding: 8px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        font-size: 14px;
        color: #374151;
        cursor: pointer;
        transition: all 0.2s ease;
        min-width: 150px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23374151' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        padding-right: 35px;
    }

    .filter-group select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        background-color: #f8fafc;
    }

    .filter-group select:hover {
        border-color: #9ca3af;
    }

    .legend {
        display: flex;
        gap: 24px;
        flex-wrap: wrap;
        margin-top: 20px;
        padding: 16px;
        background: #f9fafb;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13px;
        font-weight: 500;
        color: #4b5563;
        padding: 6px 12px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    .legend-item:hover {
        transform: scale(1.05);
    }

    .legend-color {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        border: 2px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }

    .legend-item:hover .legend-color {
        transform: scale(1.1);
    }

    .booking-info-panel {
        background: linear-gradient(135deg, #f9fafb 0%, #ffffff 100%);
        border-radius: 12px;
        padding: 20px;
        margin-top: 24px;
        border-left: 4px solid #3b82f6;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .booking-info-panel h4 {
        margin: 0 0 16px 0;
        color: #1f2937;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .booking-info-panel h4::before {
        content: '📋';
        font-size: 20px;
    }

    .booking-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        font-size: 14px;
    }

    .booking-detail-item {
        display: flex;
        flex-direction: column;
        padding: 12px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }

    .booking-detail-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 6px rgba(59, 130, 246, 0.1);
        transform: translateY(-2px);
    }

    .booking-detail-label {
        font-weight: 600;
        color: #6b7280;
        font-size: 11px;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .booking-detail-value {
        color: #1f2937;
        font-weight: 600;
        font-size: 15px;
    }

    /* FullCalendar customizations */
    .fc {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: white;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .fc-toolbar {
        margin-bottom: 20px !important;
        padding: 16px;
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 12px;
        border: 1px solid #e5e7eb;
    }

    .fc-toolbar-title {
        font-size: 1.75rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        letter-spacing: -0.5px;
    }

    .fc-button {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        border: none !important;
        color: white !important;
        padding: 10px 18px !important;
        border-radius: 8px !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        text-transform: capitalize !important;
        transition: all 0.2s ease !important;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2) !important;
    }

    .fc-button:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3) !important;
    }

    .fc-button:active {
        transform: translateY(0) !important;
    }

    .fc-button-active {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%) !important;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
    }

    .fc-button-primary:not(:disabled):active,
    .fc-button-primary:not(:disabled).fc-button-active {
        background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%) !important;
    }

    .fc-daygrid-day {
        border-color: #e5e7eb !important;
        transition: background-color 0.2s ease;
    }

    .fc-daygrid-day:hover {
        background-color: #f9fafb !important;
    }

    .fc-daygrid-day-number {
        color: #374151;
        font-weight: 600;
        font-size: 14px;
        padding: 8px !important;
    }

    .fc-day-today {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%) !important;
        border: 2px solid #3b82f6 !important;
    }

    .fc-day-today .fc-daygrid-day-number {
        color: #1e40af;
        font-weight: 700;
        font-size: 16px;
    }

    .fc-col-header-cell {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-color: #e5e7eb !important;
        padding: 12px 8px !important;
    }

    .fc-col-header-cell-cushion {
        color: #374151;
        font-weight: 700;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .fc-event {
        border-radius: 6px !important;
        border: none !important;
        padding: 6px 10px !important;
        cursor: pointer;
        font-size: 12px !important;
        font-weight: 600 !important;
        margin: 2px 0 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15) !important;
        transition: all 0.2s ease !important;
        overflow: hidden;
        position: relative;
    }

    .fc-event::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: rgba(255, 255, 255, 0.3);
    }

    .fc-event:hover {
        transform: translateY(-2px) scale(1.02) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25) !important;
        z-index: 10 !important;
    }

    .fc-event-title {
        font-weight: 600;
        line-height: 1.4;
    }

    .fc-event-time {
        font-weight: 700;
        opacity: 0.9;
    }

    /* Status colors with gradients */
    .booking-pending {
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
        color: #78350f !important;
    }

    .booking-confirmed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
    }

    .booking-checked-in {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
    }

    .booking-checked-out {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%) !important;
        color: white !important;
    }

    .booking-cancelled {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .calendar-container {
            padding: 20px;
        }

        .calendar-header h2 {
            font-size: 1.75rem;
        }

        .fc-toolbar-title {
            font-size: 1.5rem !important;
        }
    }

    @media (max-width: 768px) {
        .calendar-container {
            padding: 16px;
            border-radius: 12px;
        }

        .calendar-header {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
        }

        .calendar-header h2 {
            font-size: 1.5rem;
        }

        .calendar-filters {
            flex-direction: column;
            width: 100%;
        }

        .filter-group {
            width: 100%;
            justify-content: space-between;
        }

        .filter-group select {
            flex: 1;
            min-width: 0;
        }

        .legend {
            gap: 12px;
            padding: 12px;
        }

        .legend-item {
            font-size: 12px;
            padding: 4px 8px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
        }

        .fc-toolbar {
            flex-direction: column !important;
            gap: 12px !important;
            padding: 12px !important;
        }

        .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
            text-align: center;
        }

        .fc-button {
            padding: 8px 14px !important;
            font-size: 12px !important;
        }

        .fc-daygrid-day-number {
            font-size: 13px !important;
            padding: 6px !important;
        }

        .fc-col-header-cell {
            padding: 8px 4px !important;
        }

        .fc-col-header-cell-cushion {
            font-size: 11px !important;
        }

        .fc-event {
            font-size: 11px !important;
            padding: 4px 8px !important;
        }

        .booking-details {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .booking-detail-item {
            padding: 10px;
        }
    }

    @media (max-width: 480px) {
        .calendar-container {
            padding: 12px;
            margin: 0 -8px;
            border-radius: 8px;
        }

        .calendar-header h2 {
            font-size: 1.25rem;
        }

        .calendar-header p {
            font-size: 0.875rem;
        }

        .fc {
            padding: 8px;
        }

        .fc-toolbar {
            padding: 8px !important;
        }

        .fc-button {
            padding: 6px 12px !important;
            font-size: 11px !important;
        }

        .fc-daygrid-day-number {
            font-size: 12px !important;
            padding: 4px !important;
        }

        .fc-event {
            font-size: 10px !important;
            padding: 3px 6px !important;
        }

        .legend {
            gap: 8px;
            padding: 10px;
        }

        .legend-item {
            font-size: 11px;
            padding: 3px 6px;
        }

        .legend-color {
            width: 16px;
            height: 16px;
        }
    }

    /* Loading animation */
    .fc-loading {
        opacity: 0.6;
    }

    /* Scrollbar styling */
    .fc-scroller::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .fc-scroller::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }

    .fc-scroller::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .fc-scroller::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

<div class="p-4 sm:p-6 lg:p-8">
    <div class="calendar-container">
        <div class="calendar-header">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Booking Calendar</h2>
                <p class="text-gray-600">View all bookings and check availability at a glance</p>
            </div>
            <div class="calendar-filters">
                <div class="filter-group">
                    <label for="statusFilter">Status:</label>
                    <select id="statusFilter">
                        <option value="">All Statuses</option>
                        <option value="Pending">Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Checked In">Checked In</option>
                        <option value="Checked Out">Checked Out</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="roomFilter">Room Type:</label>
                    <select id="roomFilter">
                        <option value="">All Room Types</option>
                        <?php
                        $room_query = "SELECT DISTINCT TRoom FROM roombook WHERE TRoom IS NOT NULL ORDER BY TRoom";
                        $room_result = mysqli_query($con, $room_query);
                        while ($room_row = mysqli_fetch_assoc($room_result)) {
                            echo '<option value="' . htmlspecialchars($room_row['TRoom']) . '">' . htmlspecialchars($room_row['TRoom']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color booking-pending"></div>
                <span>Pending</span>
            </div>
            <div class="legend-item">
                <div class="legend-color booking-confirmed"></div>
                <span>Confirmed</span>
            </div>
            <div class="legend-item">
                <div class="legend-color booking-checked-in"></div>
                <span>Checked In</span>
            </div>
            <div class="legend-item">
                <div class="legend-color booking-checked-out"></div>
                <span>Checked Out</span>
            </div>
            <div class="legend-item">
                <div class="legend-color booking-cancelled"></div>
                <span>Cancelled</span>
            </div>
        </div>

        <div id="calendar"></div>

        <div id="bookingInfoPanel" class="booking-info-panel" style="display: none;">
            <h4>Booking Details</h4>
            <div class="booking-details" id="bookingDetails"></div>
        </div>
    </div>
</div>

<!-- FullCalendar JS -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.5/main.min.js'></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        events: function(fetchInfo, successCallback, failureCallback) {
            // Get filter values
            var statusFilter = document.getElementById('statusFilter').value;
            var roomFilter = document.getElementById('roomFilter').value;
            
            // Build query parameters
            var params = new URLSearchParams({
                start: fetchInfo.startStr,
                end: fetchInfo.endStr
            });
            
            if (statusFilter) {
                params.append('status', statusFilter);
            }
            
            if (roomFilter) {
                params.append('room', roomFilter);
            }
            
            // Fetch events from server
            fetch('ajax/get_calendar_bookings.php?' + params.toString())
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        var events = data.bookings.map(function(booking) {
                            // Determine color based on status
                            var className = 'booking-' + booking.status.toLowerCase().replace(' ', '-');
                            
                            return {
                                id: booking.id,
                                title: booking.title,
                                start: booking.checkin,
                                end: booking.checkout,
                                extendedProps: {
                                    customer: booking.customer,
                                    room: booking.room,
                                    room_number: booking.room_number || '',
                                    room_status: booking.room_status || '',
                                    status: booking.status,
                                    rooms: booking.rooms,
                                    phone: booking.phone,
                                    email: booking.email
                                },
                                className: className,
                                backgroundColor: getStatusColor(booking.status),
                                borderColor: getStatusColor(booking.status),
                                textColor: getStatusTextColor(booking.status)
                            };
                        });
                        successCallback(events);
                    } else {
                        console.error('Error loading bookings:', data.message);
                        successCallback([]);
                    }
                })
                .catch(error => {
                    console.error('Error fetching bookings:', error);
                    successCallback([]);
                });
        },
        eventClick: function(info) {
            var booking = info.event.extendedProps;
            var checkin = new Date(info.event.start);
            var checkout = new Date(info.event.end);
            
            // Format dates
            var formatDate = function(date) {
                return date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            };
            
            // Display booking details
            var detailsHtml = `
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Booking ID</span>
                    <span class="booking-detail-value">#${info.event.id}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Customer</span>
                    <span class="booking-detail-value">${booking.customer}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Room Type</span>
                    <span class="booking-detail-value">${booking.room}</span>
                </div>
                ${booking.room_number ? `
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Room Number</span>
                    <span class="booking-detail-value">${booking.room_number}</span>
                </div>
                ` : ''}
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Number of Rooms</span>
                    <span class="booking-detail-value">${booking.rooms}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Status</span>
                    <span class="booking-detail-value">${booking.status}</span>
                </div>
                ${booking.room_status && booking.room_status === 'Occupied' ? `
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Room Status</span>
                    <span class="booking-detail-value" style="color: #ef4444; font-weight: 600;">Occupied</span>
                </div>
                ` : ''}
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Check-in</span>
                    <span class="booking-detail-value">${formatDate(checkin)}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Check-out</span>
                    <span class="booking-detail-value">${formatDate(checkout)}</span>
                </div>
                ${booking.phone ? `
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Phone</span>
                    <span class="booking-detail-value">${booking.phone}</span>
                </div>
                ` : ''}
                ${booking.email ? `
                <div class="booking-detail-item">
                    <span class="booking-detail-label">Email</span>
                    <span class="booking-detail-value">${booking.email}</span>
                </div>
                ` : ''}
            `;
            
            document.getElementById('bookingDetails').innerHTML = detailsHtml;
            document.getElementById('bookingInfoPanel').style.display = 'block';
            
            // Scroll to details panel
            document.getElementById('bookingInfoPanel').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        },
        eventDidMount: function(info) {
            // Add tooltip
            info.el.setAttribute('title', 
                info.event.extendedProps.customer + ' - ' + 
                info.event.extendedProps.room + ' (' + 
                info.event.extendedProps.status + ')'
            );
        },
        height: 'auto',
        navLinks: true,
        dayMaxEvents: true,
        weekNumbers: true,
        weekNumberCalculation: 'ISO',
        editable: false,
        selectable: false,
        nowIndicator: true
    });
    
    calendar.render();
    
    // Add filter change listeners
    document.getElementById('statusFilter').addEventListener('change', function() {
        calendar.refetchEvents();
    });
    
    document.getElementById('roomFilter').addEventListener('change', function() {
        calendar.refetchEvents();
    });
    
    // Helper function to get status color
    function getStatusColor(status) {
        var colors = {
            'Pending': '#fbbf24',
            'Confirmed': '#10b981',
            'Checked In': '#3b82f6',
            'Checked Out': '#6b7280',
            'Cancelled': '#ef4444'
        };
        return colors[status] || '#6b7280';
    }
    
    // Helper function to get text color based on status
    function getStatusTextColor(status) {
        if (status === 'Pending') {
            return '#78350f';
        }
        return 'white';
    }
});
</script>

<?php
endUnifiedAdminPage();
?>

