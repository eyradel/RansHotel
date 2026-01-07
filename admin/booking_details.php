<?php  
session_start();  
if(!isset($_SESSION["user"]))
{
 header("location:index.php");
}

// Include access control system
include('includes/access_control.php');
include('includes/unified_layout.php');
initAccessControl('room_booking');
?> 

<?php
$curdate=date("Y/m/d");
include ('db.php');

// If no rid parameter, show list of all bookings
if(!isset($_GET["rid"]))
{
	// Start admin page with components
	startUnifiedAdminPage('Room Bookings', 'Manage all room bookings at RansHotel - Located in Tsito, Ghana');
	?>
	<div class="container mx-auto px-4 sm:px-6 lg:px-8">
		<!-- Top Bar -->
		<div class="flex items-center justify-between py-6">
			<div class="flex items-center gap-3">
				<i class="fa fa-bar-chart-o text-blue-600"></i>
				<h1 class="text-2xl font-semibold text-gray-900">Room Bookings</h1>
			</div>
			<a href="reservation_classic.php" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition-colors">
				<i class="fa fa-plus mr-2"></i> New Reservation
			</a>
		</div>

		<!-- Bookings List -->
		<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
			<div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
				<h2 class="text-lg font-medium text-gray-900">All Bookings</h2>
			</div>
			<div class="overflow-x-auto">
				<table class="min-w-full divide-y divide-gray-200">
					<thead class="bg-gray-50">
						<tr>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Booking ID</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest Name</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room Type</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
							<th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
						</tr>
					</thead>
					<tbody class="bg-white divide-y divide-gray-200">
						<?php
						// Get ALL reservations
						$bookings_query = "SELECT 
							rb.id,
							rb.Title,
							rb.FName,
							rb.LName,
							rb.Email,
							rb.Phone,
							rb.TRoom,
							rb.cin,
							rb.cout,
							rb.stat as status,
							rb.final_amount as amount
							FROM roombook rb
							ORDER BY rb.id DESC";
						$bookings_result = mysqli_query($con, $bookings_query);
						
						// Check if there are any bookings
						$booking_count = mysqli_num_rows($bookings_result);
						
						if ($booking_count == 0) {
							echo "<tr><td colspan='10' class='px-6 py-8 text-center text-gray-500'>No bookings found. <a href='reservation_classic.php' class='text-blue-600 hover:underline'>Create a new reservation</a></td></tr>";
						}
						
						while ($row = mysqli_fetch_assoc($bookings_result)) {
							// Format guest name properly
							$guestName = trim(($row['Title'] ?? '') . ' ' . ($row['FName'] ?? '') . ' ' . ($row['LName'] ?? ''));
							if (empty($guestName)) {
								$guestName = 'Guest';
							}
							
							// Status display with better styling
							$statusText = $row['status'] ?? 'Pending';
							$statusTextLower = strtolower($statusText);
							
							// Update status classes based on actual status values
							if (in_array($statusTextLower, ['pending'])) {
								$status_class = 'text-yellow-800';
								$status_bg = 'bg-yellow-100';
							} elseif (in_array($statusTextLower, ['confirmed', 'confirm', 'conform'])) {
								$status_class = 'text-green-800';
								$status_bg = 'bg-green-100';
							} elseif (in_array($statusTextLower, ['checked in', 'checked-in'])) {
								$status_class = 'text-blue-800';
								$status_bg = 'bg-blue-100';
							} elseif (in_array($statusTextLower, ['checked out', 'checked-out'])) {
								$status_class = 'text-gray-800';
								$status_bg = 'bg-gray-100';
							} elseif (in_array($statusTextLower, ['cancelled', 'canceled'])) {
								$status_class = 'text-red-800';
								$status_bg = 'bg-red-100';
							} else {
								$status_class = 'text-gray-800';
								$status_bg = 'bg-gray-100';
							}
							
							echo "<tr class='hover:bg-gray-50'>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>#" . $row['id'] . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($guestName) . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>" . htmlspecialchars($row['Email'] ?? 'N/A') . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-600'>" . htmlspecialchars($row['Phone'] ?? 'N/A') . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . htmlspecialchars($row['TRoom'] ?? 'N/A') . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . (!empty($row['cin']) ? date('M j, Y', strtotime($row['cin'])) : 'N/A') . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . (!empty($row['cout']) ? date('M j, Y', strtotime($row['cout'])) : 'N/A') . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap'><span class='inline-flex px-2 py-1 text-xs font-semibold rounded-full {$status_bg} {$status_class}'>" . htmlspecialchars($statusText) . "</span></td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium'>₵" . number_format($row['amount'] ?? 0, 2) . "</td>";
							echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium'>";
							echo "<a href='booking_details.php?rid=" . urlencode($row['id']) . "' class='text-blue-600 hover:text-blue-900'>View Details</a>";
							echo "</td>";
							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	endUnifiedAdminPage();
	exit();
}
else {
	// Show single booking details (existing code)
	$id = $_GET['rid'];
	
	$sql ="Select * from roombook where id = '$id'";
	$re = mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($re))
	{
		$title = $row['Title'];
		$fname = $row['FName'];
		$lname = $row['LName'];
		$email = $row['Email'];
		$nat = $row['National'];
		$country = $row['Country'];
		$Phone = $row['Phone'];
		$troom = $row['TRoom'];
		$nroom = $row['NRoom'];
		$bed = $row['Bed'];
		$non = $row['NRoom'];
		$meal = $row['Meal'];
		$cin = $row['cin'];
		$cout = $row['cout'];
		$sta = $row['stat'];
		$days = $row['nodays'];
		$final_amount = $row['final_amount'];
		$assigned_room_number = $row['assigned_room_number'] ?? null;
	}
}

// Start admin page with components
startUnifiedAdminPage('Booking Details', 'View booking details at RansHotel - Located in Tsito, Ghana');
?>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Bar with Actions -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 py-6">
                <div class="flex items-center gap-3">
                    <a href="booking_details.php" class="text-gray-500 hover:text-gray-700 transition-colors">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    <i class="fa fa-bar-chart-o text-blue-600"></i>
                    <div>
                        <h1 class="text-2xl font-semibold text-gray-900">Booking Details</h1>
                        <p class="text-sm text-gray-500">Booking ID: #<?php echo $id; ?></p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <?php 
                    $statusLower = strtolower(trim($sta));
                    // Allow confirmation for typical 'pending' / 'confirm' legacy values
                    $canConfirm = in_array($statusLower, ['pending', 'confirm', 'conform']);
                    if ($canConfirm): 
                    ?>
                    <!-- Confirm Button - Primary action for pending/confirm bookings -->
                    <form method="post" class="inline-block" onsubmit="return confirm('Are you sure you want to confirm this booking?');">
                        <input type="hidden" name="conf" value="Confirm">
                        <button type="submit" name="co" class="inline-flex items-center gap-2 px-6 py-3 rounded-lg bg-green-600 text-white text-sm font-semibold hover:bg-green-700 shadow-md hover:shadow-lg transition-all transform hover:scale-105">
                            <i class="fa fa-check-circle"></i>
                            Confirm Booking
                        </button>
                    </form>
                    <?php endif; ?>
                    <?php
                    // Always show a status badge
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-green-100 text-green-800',
                        'confirm' => 'bg-green-100 text-green-800',
                        'conform' => 'bg-green-100 text-green-800',
                        'checked in' => 'bg-blue-100 text-blue-800',
                        'checked-in' => 'bg-blue-100 text-blue-800',
                        'checked out' => 'bg-gray-100 text-gray-800',
                        'checked-out' => 'bg-gray-100 text-gray-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'canceled' => 'bg-red-100 text-red-800'
                    ];
                    $statusClass = $statusColors[$statusLower] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-lg <?php echo $statusClass; ?> text-sm font-semibold">
                        <i class="fa fa-info-circle"></i>
                        <?php echo htmlspecialchars($sta); ?>
                    </span>
                    <a href="booking_details.php" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition-colors">
                        <i class="fa fa-list"></i>
                        Back to List
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-lg font-medium text-gray-900">Booking Confirmation</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Information</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Name</td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900"><?php echo  $title.$fname.$lname; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Email</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo  $email; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Nationality</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo  $nat; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Country</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo  $country; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Phone No</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $Phone; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Type Of Room</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $troom; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">No Of Rooms</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $nroom; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Meal Plan</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $meal; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Bedding</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $bed; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Check-in Date</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cin; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Check-out Date</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $cout; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">No of days</td>
                                        <td class="px-6 py-4 text-sm text-gray-900"><?php echo $days; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Status</td>
                                        <td class="px-6 py-4 text-sm"><span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"><?php echo $sta; ?></span></td>
                                    </tr>
                                    <?php if(isset($assigned_room_number)): ?>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-600">Assigned Room</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium"><?php echo $assigned_room_number; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Summary Card (Right Sidebar) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Booking Summary Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                            <h3 class="text-lg font-semibold text-gray-900">Booking Summary</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Amount</span>
                                <span class="text-2xl font-bold text-gray-900">₵<?php echo number_format($final_amount ?? 0, 2); ?></span>
                            </div>
                            <div class="pt-4 border-t border-gray-200 space-y-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Duration</span>
                                    <span class="font-medium text-gray-900"><?php echo $days ?? 0; ?> days</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Rooms</span>
                                    <span class="font-medium text-gray-900"><?php echo $nroom ?? 1; ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Check-in</span>
                                    <span class="font-medium text-gray-900"><?php echo !empty($cin) ? date('M j, Y', strtotime($cin)) : 'N/A'; ?></span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Check-out</span>
                                    <span class="font-medium text-gray-900"><?php echo !empty($cout) ? date('M j, Y', strtotime($cout)) : 'N/A'; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Actions Card -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                        </div>
                        <div class="p-6 space-y-3">
                            <a href="room_allocation.php" class="block w-full text-left px-4 py-3 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition-colors">
                                <i class="fa fa-bed mr-2 text-blue-600"></i>
                                Assign Room
                            </a>
                            <a href="booking_invoice.php?rid=<?php echo $id; ?>" class="block w-full text-left px-4 py-3 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition-colors">
                                <i class="fa fa-file-text mr-2 text-green-600"></i>
                                View Invoice
                            </a>
                            <a href="mailto:<?php echo htmlspecialchars($email); ?>" class="block w-full text-left px-4 py-3 rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 hover:border-blue-300 transition-colors">
                                <i class="fa fa-envelope mr-2 text-purple-600"></i>
                                Send Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>
					
					<?php
					$rsql ="select * from room";
					$rre= mysqli_query($con,$rsql);
					$r =0 ;
					$sc =0;
					$gh = 0;
					$sr = 0;
					$dr = 0;
						while($rrow=mysqli_fetch_array($rre))
						{
							$r = $r + 1;
							$s = $rrow['type'];
							$p = $rrow['place'];
							if($s=="Superior Room" )
							{
								$sc = $sc+ 1;
							}
							
							if($s=="Guest House")
							{
								$gh = $gh + 1;
							}
							if($s=="Single Room" )
							{
									$sr = $sr + 1;
							}
							if($s=="Deluxe Room" )
							{
									$dr = $dr + 1;
							}
							
						
						}
						?>
						
						<?php
						$csql ="select * from payment";
						$cre= mysqli_query($con,$csql);
						$cr =0 ;
						$csc =0;
						$cgh = 0;
						$csr = 0;
						$cdr = 0;
						while($crow=mysqli_fetch_array($cre))
						{
							$cr = $cr + 1;
							$cs = $crow['troom'];
							
							if($cs=="Superior Room"  )
							{
								$csc = $csc + 1;
							}
							
									if($cs=="Guest House" )
							{
								$cgh = $cgh + 1;
							}
							if($cs=="Single Room" )
							{
								$csr = $csr + 1;
							}
							if($cs=="Deluxe Room" )
							{
								$cdr = $cdr + 1;
							}
							
						
						}
				
					?>
<?php
// Compute availability figures for validation only (no UI rendering)
$f1 = $sc - $csc; // Superior available
$f2 = $gh - $cgh; // Guest House available
$f3 = $sr - $csr; // Single available
$f4 = $dr - $cdr; // Deluxe available
$f5 = $r - $cr;   // Total available
?>
    </div>

<?php
						if(isset($_POST['co']))
						{	
							$st = $_POST['conf'];
							
							 
							
							if($st=="Confirm")
							{
								$urb = "UPDATE `roombook` SET `stat`='$st' WHERE id = '$id'";
									
								if($f1=="NO" )
								{
									echo "<script type='text/javascript'> alert('Sorry! Not Available Superior Room ')</script>";
								}
								else if($f2 =="NO")
									{
										echo "<script type='text/javascript'> alert('Sorry! Not Available Guest House')</script>";
										
									}
									else if ($f3 == "NO")
									{
										echo "<script type='text/javascript'> alert('Sorry! Not Available Single Room')</script>";
									}
										else if($f4=="NO")
										{
										echo "<script type='text/javascript'> alert('Sorry! Not Available Deluxe Room')</script>";
										}
										
										else if( mysqli_query($con,$urb))
											{	
												// Auto-assign room based on room type
												$room_assignment_query = "SELECT id, room_number FROM room WHERE type = '$troom' AND place = 'Free' AND status = 'Available' ORDER BY room_number LIMIT 1";
												$room_assignment_result = mysqli_query($con, $room_assignment_query);
												
												if(mysqli_num_rows($room_assignment_result) > 0) {
													$assigned_room = mysqli_fetch_assoc($room_assignment_result);
													$assigned_room_id = $assigned_room['id'];
													$assigned_room_number = $assigned_room['room_number'];
													
													$assigned_room_number_db = mysqli_real_escape_string($con, $assigned_room_number);
													
													// Mark room as occupied
													$room_update_query = "UPDATE room SET place = 'NotFree', status = 'Occupied', cusid = '$id' WHERE id = '$assigned_room_id'";
													mysqli_query($con, $room_update_query);
													
													// Update booking with assigned room
													$booking_room_update = "UPDATE roombook SET assigned_room_id = '$assigned_room_id', assigned_room_number = '$assigned_room_number_db' WHERE id = '$id'";
													mysqli_query($con, $booking_room_update);
												}
												
												//echo "<script type='text/javascript'> alert('Guest Room booking is conform')</script>";
												//echo "<script type='text/javascript'> window.location='home.php'</script>";
												 $type_of_room = 0;       
														if($troom=="Superior Room")
														{
															$type_of_room = 3840;
														
														}
														else if($troom=="Deluxe Room")
														{
															$type_of_room = 2640;
														}
														else if($troom=="Guest House")
														{
															$type_of_room = 2160;
														}
														else if($troom=="Single Room")
														{
															$type_of_room = 1800;
														}
														
														
														
														
														if($bed=="Single")
														{
															$type_of_bed = $type_of_room * 1/100;
														}
														else if($bed=="Double")
														{
															$type_of_bed = $type_of_room * 2/100;
														}
														else if($bed=="Triple")
														{
															$type_of_bed = $type_of_room * 3/100;
														}
														else if($bed=="Quad")
														{
															$type_of_bed = $type_of_room * 4/100;
														}
														else if($bed=="None")
														{
															$type_of_bed = $type_of_room * 0/100;
														}
														
														
														if($meal=="Room only")
														{
															$type_of_meal=$type_of_bed * 0;
														}
														else if($meal=="Breakfast")
														{
															$type_of_meal=$type_of_bed * 2;
														}else if($meal=="Half Board")
														{
															$type_of_meal=$type_of_bed * 3;
														
														}else if($meal=="Full Board")
														{
															$type_of_meal=$type_of_bed * 4;
														}
														
														
                                                            // Use the precomputed final amount from reservation for payment totals
                                                            $ttot = (float)$final_amount;
                                                            $mepr = 0;
                                                            $btot = 0;
                                                            $fintot = (float)$final_amount;
															
															//echo "<script type='text/javascript'> alert('$count_date')</script>";
															$titleDb = mysqli_real_escape_string($con, $title);
															$fnameDb = mysqli_real_escape_string($con, $fname);
															$lnameDb = mysqli_real_escape_string($con, $lname);
															$troomDb = mysqli_real_escape_string($con, $troom);
															$bedDb = mysqli_real_escape_string($con, $bed);
															$mealDb = mysqli_real_escape_string($con, $meal);
															$cinDb = mysqli_real_escape_string($con, $cin);
															$coutDb = mysqli_real_escape_string($con, $cout);
															
															$psql = "INSERT INTO `payment`(`id`, `title`, `fname`, `lname`, `troom`, `tbed`, `nroom`, `cin`, `cout`, `ttot`,`meal`, `mepr`, `btot`,`fintot`,`noofdays`) VALUES ('$id','$titleDb','$fnameDb','$lnameDb','$troomDb','$bedDb','$nroom','$cinDb','$coutDb','$ttot','$mealDb','$mepr','$btot','$fintot','$days')";
														
														if(mysqli_query($con,$psql))
														{	
															// Only update room if one was already assigned, otherwise skip
															// This prevents assigning all rooms of a type
															if(isset($assigned_room_id) && !empty($assigned_room_id)) {
																$notfree = "NotFree";
																$rpsql = "UPDATE `room` SET `place`='$notfree', `status`='Occupied', `cusid`='$id' WHERE id = '$assigned_room_id'";
																mysqli_query($con, $rpsql);
															}
															
															// Continue with notification logic
															if(true) // Changed condition since room update is now optional
															{
															// Send confirmation notifications to customer
															require_once 'includes/notification_manager.php';
															$notificationManager = new NotificationManager();
															
                                                            // Use reservation final amount for notification totals
															$totalAmount = (float)$final_amount;
															
															// Get assigned room info for notification
															$assigned_room_info = '';
															if(isset($assigned_room_number)) {
																$assigned_room_info = "\\n\\nAssigned Room: " . $assigned_room_number;
															}
															
															// Send admin confirmation notifications to customer
															$notificationResult = $notificationManager->sendAdminConfirmationNotifications(
																$email,           // Customer email
																$fname . ' ' . $lname,  // Customer name
																$Phone,           // Customer phone
																$troom,           // Room type
																$cin,             // Check-in date
																$cout,            // Check-out date
																$id,              // Booking ID
																$meal,            // Meal plan
																$nat,             // Nationality
																$country,         // Country
																$totalAmount      // Total amount
															);
															
															// Show confirmation with notification status
															$notificationStatus = $notificationManager->getNotificationStatus($notificationResult);
															
															// Format notification status for display
															$statusMessage = "Total Sent: " . $notificationStatus['total_sent'] . "\\n";
															$statusMessage .= "Total Failed: " . $notificationStatus['total_failed'] . "\\n\\n";
															$statusMessage .= "Details:\\n";
															foreach ($notificationStatus['details'] as $type => $detail) {
																$status = $detail['success'] ? 'Sent' : 'Failed';
																$statusMessage .= "- " . ucfirst(str_replace('_', ' ', $type)) . ": " . $status . "\\n";
															}
															
															echo "<script type='text/javascript'> alert('Booking Confirmed!" . $assigned_room_info . "\\n\\nCustomer Notifications:\\n" . $statusMessage . "')</script>";
															echo "<script type='text/javascript'> window.location='booking_details.php'</script>";
															}
															
															
														}
												
											}
									
                                        
							}	
					
						}
					
									
									
							
						?>
<?php
// End admin page with components
endUnifiedAdminPage();
?>
