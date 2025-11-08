<?php
session_start();
if(!isset($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

include('../db.php');

header('Content-Type: application/json');

try {
    // Get date range from request
    $start = isset($_GET['start']) ? $_GET['start'] : date('Y-m-d');
    $end = isset($_GET['end']) ? $_GET['end'] : date('Y-m-d', strtotime('+1 month'));
    
    // Validate and format dates
    $startDate = date('Y-m-d', strtotime($start));
    $endDate = date('Y-m-d', strtotime($end));
    
    if (!$startDate || !$endDate) {
        throw new Exception('Invalid date format');
    }
    
    // Get filters
    $statusFilter = isset($_GET['status']) ? mysqli_real_escape_string($con, $_GET['status']) : '';
    $roomFilter = isset($_GET['room']) ? mysqli_real_escape_string($con, $_GET['room']) : '';
    
    // Build query with proper escaping
    $start = mysqli_real_escape_string($con, $startDate);
    $end = mysqli_real_escape_string($con, $endDate);
    
    // Query to get all bookings that overlap with the date range
    // Also include occupied rooms that are linked to bookings via cusid
    // A booking overlaps if: check-in <= end AND check-out >= start
    $sql = "SELECT DISTINCT 
                rb.id, 
                rb.Title, 
                rb.FName, 
                rb.LName, 
                rb.Email, 
                rb.Phone, 
                COALESCE(rb.TRoom, r.type) as TRoom, 
                COALESCE(rb.Bed, r.bedding) as Bed, 
                rb.NRoom, 
                rb.cin, 
                rb.cout, 
                CASE 
                    WHEN r.status = 'Occupied' AND r.place = 'NotFree' THEN 'Checked In'
                    ELSE rb.stat 
                END as stat,
                r.room_number,
                r.status as room_status
            FROM roombook rb
            LEFT JOIN room r ON r.cusid = rb.id
            WHERE rb.cin IS NOT NULL 
            AND rb.cout IS NOT NULL 
            AND rb.cin <= '$end' 
            AND rb.cout >= '$start'";
    
    // Add status filter
    if (!empty($statusFilter)) {
        $statusFilterEscaped = mysqli_real_escape_string($con, $statusFilter);
        // Handle special case for "Checked In" which might come from room status
        if ($statusFilter === 'Checked In') {
            $sql .= " AND (rb.stat = '$statusFilterEscaped' OR (r.status = 'Occupied' AND r.place = 'NotFree'))";
        } else {
            $sql .= " AND rb.stat = '$statusFilterEscaped'";
        }
    }
    
    // Add room type filter
    if (!empty($roomFilter)) {
        $roomFilterEscaped = mysqli_real_escape_string($con, $roomFilter);
        $sql .= " AND (rb.TRoom = '$roomFilterEscaped' OR r.type = '$roomFilterEscaped')";
    }
    
    $sql .= " ORDER BY rb.cin ASC";
    
    // Execute query
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($con));
    }
    
    $bookings = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Format customer name
        $customerName = trim(($row['Title'] ?? '') . ' ' . ($row['FName'] ?? '') . ' ' . ($row['LName'] ?? ''));
        if (empty($customerName)) {
            $customerName = 'Guest';
        }
        
        // Format booking title for calendar
        $roomType = $row['TRoom'] ?? 'Room';
        $roomNumber = $row['room_number'] ?? '';
        
        // Add room number if available (occupied rooms)
        if (!empty($roomNumber)) {
            $bookingTitle = $customerName . ' - ' . $roomNumber . ' (' . $roomType . ')';
        } else {
            $bookingTitle = $customerName . ' - ' . $roomType;
        }
        
        if (!empty($row['NRoom']) && $row['NRoom'] > 1) {
            $bookingTitle .= ' (' . $row['NRoom'] . ' rooms)';
        }
        
        // Ensure dates are valid - skip if dates are invalid
        if (empty($row['cin']) || empty($row['cout'])) {
            continue; // Skip bookings with missing dates
        }
        
        $checkin = $row['cin'];
        $checkout = $row['cout'];
        
        // Validate dates
        $checkinDate = DateTime::createFromFormat('Y-m-d', $checkin);
        $checkoutDate = DateTime::createFromFormat('Y-m-d', $checkout);
        
        if (!$checkinDate || !$checkoutDate) {
            continue; // Skip invalid dates
        }
        
        // FullCalendar expects end date to be exclusive, so add 1 day
        $checkoutDate->modify('+1 day');
        $checkoutExclusive = $checkoutDate->format('Y-m-d');
        
        $bookings[] = [
            'id' => $row['id'],
            'title' => $bookingTitle,
            'checkin' => $checkin,
            'checkout' => $checkoutExclusive,
            'customer' => $customerName,
            'room' => $row['TRoom'] ?? 'N/A',
            'room_number' => $row['room_number'] ?? '',
            'room_status' => $row['room_status'] ?? '',
            'status' => $row['stat'] ?? 'Pending',
            'rooms' => $row['NRoom'] ?? '1',
            'phone' => $row['Phone'] ?? '',
            'email' => $row['Email'] ?? ''
        ];
    }
    
    // Debug info (can be removed in production)
    $debug = isset($_GET['debug']) && $_GET['debug'] == '1';
    
    $response = [
        'success' => true,
        'bookings' => $bookings,
        'count' => count($bookings)
    ];
    
    if ($debug) {
        $response['debug'] = [
            'query' => $sql,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status_filter' => $statusFilter,
            'room_filter' => $roomFilter
        ];
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching bookings: ' . $e->getMessage()
    ]);
}
?>

