<?php
/**
 * Automatic Room Status Update Script
 * Updates room statuses when checkout dates have passed
 * This should be called periodically (via cron or on page load)
 */

session_start();
if(!isset($_SESSION["user"])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

include('../db.php');

header('Content-Type: application/json');

try {
    $today = date('Y-m-d');
    $updated_count = 0;
    $errors = [];
    
    // Find all rooms that are occupied but their checkout date has passed
    $sql = "SELECT r.id as room_id, r.room_number, r.cusid, rb.id as booking_id, rb.cout, rb.stat as booking_status
            FROM room r
            INNER JOIN roombook rb ON rb.id = r.cusid
            WHERE r.status = 'Occupied'
            AND r.place = 'NotFree'
            AND rb.cout < '$today'
            AND rb.stat NOT IN ('Checked Out', 'Cancelled')";
    
    $result = mysqli_query($con, $sql);
    
    if (!$result) {
        throw new Exception('Database query failed: ' . mysqli_error($con));
    }
    
    // Update each room and booking
    while ($row = mysqli_fetch_assoc($result)) {
        $room_id = $row['room_id'];
        $booking_id = $row['booking_id'];
        
        // Start transaction for each update
        mysqli_begin_transaction($con);
        
        try {
            // Update room status to Available
            $update_room = "UPDATE room 
                           SET place = 'Free', 
                               status = 'Available', 
                               cusid = NULL 
                           WHERE id = '$room_id'";
            
            if (!mysqli_query($con, $update_room)) {
                throw new Exception('Failed to update room ' . $room_id . ': ' . mysqli_error($con));
            }
            
            // Update booking status to Checked Out if not already
            if ($row['booking_status'] !== 'Checked Out') {
                $update_booking = "UPDATE roombook 
                                  SET stat = 'Checked Out' 
                                  WHERE id = '$booking_id'";
                
                if (!mysqli_query($con, $update_booking)) {
                    throw new Exception('Failed to update booking ' . $booking_id . ': ' . mysqli_error($con));
                }
            }
            
            mysqli_commit($con);
            $updated_count++;
            
        } catch (Exception $e) {
            mysqli_rollback($con);
            $errors[] = $e->getMessage();
        }
    }
    
    // Also check for bookings that should be marked as Checked In
    // (check-in date is today or in the past, but status is still Confirmed)
    $checkin_sql = "UPDATE roombook 
                   SET stat = 'Checked In' 
                   WHERE stat = 'Confirmed' 
                   AND cin <= '$today' 
                   AND cout >= '$today'";
    
    $checkin_result = mysqli_query($con, $checkin_sql);
    $checkin_count = mysqli_affected_rows($con);
    
    echo json_encode([
        'success' => true,
        'rooms_updated' => $updated_count,
        'bookings_checked_in' => $checkin_count,
        'errors' => $errors,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error updating room statuses: ' . $e->getMessage()
    ]);
}
?>

