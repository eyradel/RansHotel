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
		if(!isset($_GET["rid"]))
		{
				
			 header("location:index.php");
		}
		else {
				$curdate=date("Y/m/d");
				include ('db.php');
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
		
		
		
			?> 

<?php
// Start admin page with components
startUnifiedAdminPage('Booking Details', 'View booking details at RansHotel - Located in Tsito, Ghana');
?>
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Top Bar -->
            <div class="flex items-center justify-between py-6">
                <div class="flex items-center gap-3">
                    <i class="fa fa-bar-chart-o text-blue-600"></i>
                    <h1 class="text-2xl font-semibold text-gray-900">Booking Details</h1>
                </div>
                <p class="text-sm text-gray-500"><?php echo $curdate; ?></p>
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
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        <form method="post" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select the Confirmation</label>
                                <select name="conf" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                    <option value selected></option>
                                    <option value="Confirm">Confirm</option>
                                </select>
                            </div>
                            <div>
                                <input type="submit" name="co" value="Confirm" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 shadow-sm transition-colors" />
                            </div>
                        </form>
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
																$status = $detail['success'] ? '✅ Sent' : '❌ Failed';
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
