<?php
include('db.php');
require_once 'includes/notification_manager.php';
require_once 'includes/pricing_helper.php';
require_once 'includes/phpmailer_email_system.php';

// Initialize pricing tables if they don't exist
PricingHelper::initializePricingTables($con);
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>RESERVATION RansHotel</title>
    <meta name="description" content="Book your stay at RansHotel - Located in Tsito, Ghana. Comfortable accommodation with modern amenities in a serene environment">
    <meta name="author" content="RansHotel">
	<!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
     <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
        <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Responsive Admin Styles-->
    <link href="assets/css/responsive-admin.css" rel="stylesheet" />
    <!-- Reservation Responsive Styles-->
    <link href="assets/css/reservation-responsive.css" rel="stylesheet" />
     <!-- Google Fonts-->
   <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
    <div id="wrapper">
        <!-- Mobile Navigation Toggle -->
        <div class="navbar-header visible-xs">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="../index.php">RansHotel</a>
        </div>
        
        <nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li>
                        <a href="../index.php"><i class="fa fa-home"></i> Homepage</a>
                    </li>
                    <li>
                        <a href="index.php"><i class="fa fa-sign-in"></i> Admin Login</a>
                    </li>
					</ul>
            </div>
        </nav>
       
        <div id="page-wrapper" >
            <div id="page-inner">
			 <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            RESERVATION <small></small>
                        </h1>
                    </div>
                </div> 
                 
                                 
            <div class="row">
                
                <div class="col-lg-5 col-md-6 col-sm-12 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-user"></i> PERSONAL INFORMATION
                        </div>
                        <div class="panel-body">
						<form name="form" method="post" action="">
                            <?php
                            // Display success message if reservation was successful
                            if(isset($_POST['submit']) && isset($msg) && $msg=="Your code is correct") {
                                echo "<div class='alert alert-success'>
                                    <strong>Success!</strong> Your reservation has been submitted successfully. Thank you for choosing RansHotel.
                                    <br>Reservation details: {$_POST['title']} {$_POST['fname']} {$_POST['lname']}, {$_POST['troom']}, Check-in: {$_POST['cin']}, Check-out: {$_POST['cout']}
                                </div>";
                            }
                            ?>
                            <div class="form-group">
                                            <label>Title*</label>
                                            <select name="title" class="form-control" required >
												<option value selected ></option>
                                                <option value="Dr.">Dr.</option>
                                                <option value="Miss.">Miss.</option>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Mrs.">Mrs.</option>
												<option value="Prof.">Prof.</option>
												<option value="Rev .">Rev .</option>
												<option value="Rev . Fr">Rev . Fr .</option>
                                            </select>
                              </div>
							  <div class="form-group">
                                            <label>First Name</label>
                                            <input name="fname" class="form-control" required>
                                            
                               </div>
							   <div class="form-group">
                                            <label>Last Name</label>
                                            <input name="lname" class="form-control" required>
                                            
                               </div>
							   <div class="form-group">
                                            <label>Email</label>
                                            <input name="email" type="email" class="form-control" required>
                                            
                               </div>
							   <div class="form-group">
                                            <label>Nationality*</label>
                                            <label class="radio-inline">
                                                <input type="radio" name="nation"  value="Ghanaian" checked="">Ghanaian
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="nation"  value="Non-Ghanaian">Non-Ghanaian
                                            </label>
                         
                                </div>
								<?php

								$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");

								?>
								<div class="form-group">
                                            <label>Passport Country*</label>
                                            <select name="country" class="form-control" required>
												<option value selected ></option>
                                                <?php
												foreach($countries as $key => $value):
												echo '<option value="'.$value.'">'.$value.'</option>'; //close your tags!!
												endforeach;
												?>
                                            </select>
								</div>
								<div class="form-group">
                                            <label>Phone Number</label>
                                            <input name="phone" type="tel" class="form-control" placeholder="+233 XX XXX XXXX" required pattern="[+]?[0-9\s\-\(\)]{10,15}" title="Please enter a valid phone number">
                                            <small class="help-block">Enter your phone number with country code (e.g., +233 XX XXX XXXX)</small>
                               </div>
							   
                        </div>
                        
                    </div>
                </div>
                
                  
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <i class="fa fa-calendar"></i> RESERVATION INFORMATION
                        </div>
                        <div class="panel-body">
								<div class="form-group">
                                            <label>Type Of Room *</label>
                                            <select name="troom" id="roomType" class="form-control" required onchange="updatePricing()">
												<option value selected >Select Room Type</option>
                                                <?php
                                                // Get room pricing from database
                                                $roomPricing = PricingHelper::getAllRoomPricing($con);
                                                $roomTypes = [];
                                                
                                                // Group by room type and get the lowest price for display
                                                foreach ($roomPricing as $price) {
                                                    if (!isset($roomTypes[$price['room_type']]) || $price['price_per_night'] < $roomTypes[$price['room_type']]) {
                                                        $roomTypes[$price['room_type']] = $price['price_per_night'];
                                                    }
                                                }
                                                
                                                foreach ($roomTypes as $roomType => $minPrice) {
                                                    echo "<option value=\"$roomType\" data-price=\"$minPrice\">" . strtoupper($roomType) . " - ₵" . number_format($minPrice, 0) . "/night</option>";
                                                }
                                                ?>
                                            </select>
                              </div>
							  <div class="form-group">
                                            <label>Bedding Type</label>
                                            <select name="bed" class="form-control" required>
												<option value selected ></option>
                                                <option value="Single">Single</option>
                                                <option value="Double">Double</option>
												<option value="Triple">Triple</option>
                                                <option value="Quad">Quad</option>
												<option value="None">None</option>
                                                
                                             
                                            </select>
                              </div>
							  <div class="form-group">
                                            <label>No.of Rooms *</label>
                                            <select name="nroom" class="form-control" required>
												<option value selected ></option>
                                                <option value="1">1</option>
                                              <!--  <option value="2">2</option>
												<option value="3">3</option>
												<option value="4">4</option>
												<option value="5">5</option>
												<option value="6">6</option>
												<option value="7">7</option> -->
                                            </select>
                              </div>
							 
							 
							  <div class="form-group">
                                            <label>Meal Plan</label>
                                            <select name="meal" id="mealPlan" class="form-control" required onchange="updatePricing()">
												<option value selected >Select Meal Plan</option>
                                                <?php
                                                // Get meal pricing from database
                                                $mealPricing = PricingHelper::getAllMealPricing($con);
                                                
                                                foreach ($mealPricing as $meal) {
                                                    $price = number_format($meal['price_per_person_per_day'], 0);
                                                    echo "<option value=\"{$meal['meal_plan']}\" data-price=\"{$meal['price_per_person_per_day']}\">{$meal['meal_plan']} - ₵{$price}/person/day</option>";
                                                }
                                                ?>
                                            </select>
                              </div>
							  <div class="form-group">
                                            <label>Check-In *</label>
                                            <input name="cin" type ="date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                                            
                               </div>
							   <div class="form-group">
                                            <label>Check-Out *</label>
                                            <input name="cout" id="checkOut" type ="date" class="form-control" required min="<?php echo date('Y-m-d', strtotime('+1 day')); ?>" onchange="updatePricing()">
                                            
                               </div>
                       </div>
                        
                    </div>
                </div>
                
                <!-- Pricing Summary Panel -->
                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <i class="fa fa-calculator"></i> PRICING SUMMARY
                        </div>
                        <div class="panel-body">
                            <div id="pricingSummary">
                                <p class="text-muted">Select room type, meal plan, and dates to see pricing</p>
                            </div>
                            <div id="priceBreakdown" style="display: none;">
                                <table class="table table-condensed">
                                    <tr>
                                        <td>Room Rate:</td>
                                        <td id="roomRate" class="text-right">₵0</td>
                                    </tr>
                                    <tr>
                                        <td>Meal Plan:</td>
                                        <td id="mealRate" class="text-right">₵0</td>
                                    </tr>
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td id="subtotal" class="text-right">₵0</td>
                                    </tr>
                                    <tr>
                                        <td>Tax (15%):</td>
                                        <td id="tax" class="text-right">₵0</td>
                                    </tr>
                                    <tr>
                                        <td>Service Charge (10%):</td>
                                        <td id="service" class="text-right">₵0</td>
                                    </tr>
                                    <tr class="active">
                                        <td><strong>Total Amount:</strong></td>
                                        <td id="total" class="text-right"><strong>₵0</strong></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
				
				
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <h4><i class="fa fa-shield"></i> HUMAN VERIFICATION & TERMS</h4>
                        
                         <!-- Terms and Conditions -->
                         <div class="alert alert-info">
                             <h5><i class="fa fa-info-circle"></i> Terms & Conditions</h5>
                             <ul class="list-unstyled">
                                 <li><i class="fa fa-check text-success"></i> Check-in time: 2:00 PM | Check-out time: 11:00 AM</li>
                                 <li><i class="fa fa-check text-success"></i> Cancellation policy: Free cancellation up to 24 hours before check-in</li>
                                 <li><i class="fa fa-check text-success"></i> All prices are in Ghanaian Cedis (₵) and include 15% tax + 10% service charge</li>
                                 <li><i class="fa fa-envelope text-primary"></i> <strong>Instant Notifications:</strong> Confirmation will be sent via SMS and email to you and the manager</li>
                             </ul>
                         </div>
                        
                        <!-- Human Verification -->
                        <div class="form-group">
                            <label>Security Verification</label>
                            <p>Please type the code shown below: <strong><?php $Random_code=rand(1000,9999); echo $Random_code; ?></strong></p>
                            <input type="text" name="code1" title="random code" class="form-control" style="max-width: 200px;" placeholder="Enter code" required />
                            <input type="hidden" name="code" value="<?php echo $Random_code; ?>" />
                        </div>
                        
                        <!-- Submit Buttons -->
                        <div class="form-group">
                            <button type="submit" name="submit" class="btn btn-success btn-lg">
                                <i class="fa fa-check"></i> Submit Reservation
                            </button>
                            <button type="reset" class="btn btn-default btn-lg" style="margin-left: 10px;">
                                <i class="fa fa-refresh"></i> Reset Form
                            </button>
                        </div>
						<?php
							$msg = "";
							if(isset($_POST['submit']))
							{
								$code1 = $_POST['code1'];
								$code = $_POST['code']; 
								
								if($code1 != $code)
								{
									$msg = "Invalid code"; 
									echo "<div class='alert alert-danger'><strong>Error!</strong> Invalid verification code. Please try again.</div>";
								}
								else
								{
									// Check if email already exists using prepared statement
									$email = $_POST['email'];
									$check = "SELECT COUNT(*) as count FROM roombook WHERE email = ?";
									$stmt = mysqli_prepare($con, $check);
									mysqli_stmt_bind_param($stmt, "s", $email);
									mysqli_stmt_execute($stmt);
									$result = mysqli_stmt_get_result($stmt);
									$data = mysqli_fetch_assoc($result);
									
									if($data['count'] > 0) {
										echo "<div class='alert alert-warning'><strong>Warning!</strong> A reservation with this email already exists.</div>";
									}
									else
									{
										// Validate check-in and check-out dates
										$cin = $_POST['cin'];
										$cout = $_POST['cout'];
										
										if(strtotime($cout) <= strtotime($cin)) {
											echo "<div class='alert alert-danger'><strong>Error!</strong> Check-out date must be after check-in date.</div>";
										} else {
											$title = $_POST['title'];
											$fname = $_POST['fname'];
											$lname = $_POST['lname'];
											$nation = $_POST['nation'];
											$country = $_POST['country'];
											$phone = $_POST['phone'];
											$troom = $_POST['troom'];
											$bed = $_POST['bed'];
											$nroom = $_POST['nroom'];
											$meal = $_POST['meal'];
											$new = "Not Confirm";
											
											// Calculate days difference in PHP instead of MySQL
											$days = (strtotime($cout) - strtotime($cin)) / (60 * 60 * 24);
											
											// Use prepared statement for insert
											$insertQuery = "INSERT INTO `roombook`(`Title`, `FName`, `LName`, `Email`, `National`, `Country`, `Phone`, `TRoom`, `Bed`, `NRoom`, `Meal`, `cin`, `cout`, `stat`, `nodays`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
											$stmt = mysqli_prepare($con, $insertQuery);
											mysqli_stmt_bind_param($stmt, "ssssssssssssssi", $title, $fname, $lname, $email, $nation, $country, $phone, $troom, $bed, $nroom, $meal, $cin, $cout, $new, $days);
											
											if (mysqli_stmt_execute($stmt))
											{
												$msg = "Your code is correct";
												
												// Get the booking ID (last inserted ID)
												$bookingId = mysqli_insert_id($con);
												
												// Calculate total amount using database pricing
												$pricingData = PricingHelper::calculateBookingTotal($troom, $bed, $meal, $days, $nroom, $con);
												$totalAmount = $pricingData['total'];
												
												// Prepare booking data for notifications
												$bookingData = [
													'bookingId' => $bookingId,
													'customerName' => $fname . ' ' . $lname,
													'email' => $email,
													'phone' => $phone,
													'roomType' => $troom,
													'checkIn' => $cin,
													'checkOut' => $cout,
													'mealPlan' => $meal,
													'nationality' => $nation,
													'country' => $country,
													'totalAmount' => $totalAmount
												];
												
												// Send notifications
												$notificationManager = new NotificationManager();
												$notificationResults = $notificationManager->sendBookingNotifications($bookingData);
												
												// Display success message with notification status
												$notificationStatus = $notificationManager->getNotificationStatus($notificationResults);
												
												// Get detailed notification results
												$customerEmailStatus = $notificationResults['customer_email']['success'] ? '✅ Sent' : '❌ Failed';
												$customerSmsStatus = $notificationResults['customer_sms']['success'] ? '✅ Sent' : '❌ Failed';
												$managerEmailStatus = $notificationResults['manager_email']['success'] ? '✅ Sent' : '❌ Failed';
												$managerSmsStatus = $notificationResults['manager_sms']['success'] ? '✅ Sent' : '❌ Failed';
												
												echo "<div class='alert alert-success'>
													<strong><i class='fa fa-check-circle'></i> Success!</strong> Your reservation has been submitted successfully. Thank you for choosing RansHotel.
													<br><br><strong>Reservation Details:</strong>
													<br>• Guest: $title $fname $lname
													<br>• Room: $troom ($nroom room(s))
													<br>• Check-in: $cin | Check-out: $cout ($days night(s))
													<br>• Meal Plan: $meal
													<br>• Total Amount: <strong>₵" . number_format($totalAmount, 2) . "</strong>
													<br><br><strong>Booking ID:</strong> $bookingId
												  </div>";
											}
											else
											{
												echo "<div class='alert alert-danger'>
													<strong>Error!</strong> There was a problem submitting your reservation: " . mysqli_error($con) . "
												  </div>";
											}
										}
									}
								}
							}
							?>
						</form>
							
                    </div>
                </div>
            </div>
           
                
                </div>
                    
            
				
					</div>
			 <!-- /. PAGE INNER  -->
            </div>
         <!-- /. PAGE WRAPPER  -->
        </div>
     <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
      <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
      <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>
    
    <script>
    // Date validation and pricing calculator
    document.addEventListener('DOMContentLoaded', function() {
        const cinInput = document.querySelector('input[name="cin"]');
        const coutInput = document.querySelector('input[name="cout"]');
        
        cinInput.addEventListener('change', function() {
            const cinDate = new Date(this.value);
            const minCoutDate = new Date(cinDate);
            minCoutDate.setDate(minCoutDate.getDate() + 1);
            coutInput.min = minCoutDate.toISOString().split('T')[0];
            updatePricing();
        });
        
        coutInput.addEventListener('change', function() {
            const coutDate = new Date(this.value);
            const cinDate = new Date(cinInput.value);
            
            if (coutDate <= cinDate) {
                alert('Check-out date must be after check-in date');
                this.value = '';
            } else {
                updatePricing();
            }
        });
    });
    
    function updatePricing() {
        const roomSelect = document.getElementById('roomType');
        const mealSelect = document.getElementById('mealPlan');
        const checkInInput = document.querySelector('input[name="cin"]');
        const checkOutInput = document.getElementById('checkOut');
        const nroomSelect = document.querySelector('select[name="nroom"]');
        
        const roomPrice = parseFloat(roomSelect.options[roomSelect.selectedIndex].dataset.price) || 0;
        const mealPrice = parseFloat(mealSelect.options[mealSelect.selectedIndex].dataset.price) || 0;
        const nrooms = parseInt(nroomSelect.value) || 1;
        
        let days = 0;
        if (checkInInput.value && checkOutInput.value) {
            const checkIn = new Date(checkInInput.value);
            const checkOut = new Date(checkOutInput.value);
            days = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
        }
        
        if (roomPrice > 0 && days > 0) {
            const roomTotal = roomPrice * days * nrooms;
            const mealTotal = mealPrice * days * nrooms;
            const subtotal = roomTotal + mealTotal;
            const tax = subtotal * 0.15;
            const service = subtotal * 0.10;
            const total = subtotal + tax + service;
            
            // Update pricing display
            document.getElementById('roomRate').textContent = '₵' + roomTotal.toFixed(2);
            document.getElementById('mealRate').textContent = '₵' + mealTotal.toFixed(2);
            document.getElementById('subtotal').textContent = '₵' + subtotal.toFixed(2);
            document.getElementById('tax').textContent = '₵' + tax.toFixed(2);
            document.getElementById('service').textContent = '₵' + service.toFixed(2);
            document.getElementById('total').innerHTML = '<strong>₵' + total.toFixed(2) + '</strong>';
            
            // Show pricing breakdown
            document.getElementById('pricingSummary').style.display = 'none';
            document.getElementById('priceBreakdown').style.display = 'block';
        } else {
            // Hide pricing breakdown
            document.getElementById('pricingSummary').style.display = 'block';
            document.getElementById('priceBreakdown').style.display = 'none';
        }
    }
    
    // Add event listeners for number of rooms
    document.addEventListener('DOMContentLoaded', function() {
        const nroomSelect = document.querySelector('select[name="nroom"]');
        if (nroomSelect) {
            nroomSelect.addEventListener('change', updatePricing);
        }
    });
    </script>
   
</body>
</html>
