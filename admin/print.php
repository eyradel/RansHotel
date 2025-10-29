
<html>
	<head>
		<meta charset="utf-8">
		<title>Invoice</title>
		<link rel="stylesheet" href="style.css">
		<link rel="license" href="https://www.opensource.org/licenses/mit-license/">
		<script src="script.js"></script>
		<style>
		/* RansHotel Professional Invoice Styles */
		
		* {
	margin: 0;
	padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
			padding: 20px;
			min-height: 100vh;
		}

		.invoice-container {
			max-width: 800px;
			margin: 0 auto;
			background: white;
			border-radius: 12px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
			overflow: hidden;
		}

		/* Header */
		.invoice-header {
			background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
			color: white;
			padding: 40px;
			position: relative;
		}

		.invoice-header::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="white" opacity="0.1"/><circle cx="75" cy="75" r="1" fill="white" opacity="0.1"/><circle cx="50" cy="10" r="0.5" fill="white" opacity="0.1"/><circle cx="10" cy="60" r="0.5" fill="white" opacity="0.1"/><circle cx="90" cy="40" r="0.5" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
			opacity: 0.3;
		}

		.hotel-info {
			position: relative;
			z-index: 1;
		}

		.hotel-name {
			font-size: 2.5rem;
			font-weight: 700;
			margin-bottom: 10px;
			text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
		}

		.hotel-tagline {
			font-size: 1.1rem;
			opacity: 0.9;
			margin-bottom: 20px;
		}

		.hotel-details {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 20px;
			margin-top: 20px;
		}

		.detail-item {
			display: flex;
			align-items: center;
			font-size: 0.95rem;
		}

		.detail-item .label {
			margin-right: 10px;
			width: 80px;
			font-weight: 600;
			opacity: 0.9;
			text-align: left;
		}

		/* Invoice Title */
		.invoice-title {
			text-align: center;
			font-size: 3rem;
			font-weight: 300;
			letter-spacing: 3px;
			margin: 40px 0;
			color: #1e3a8a;
			position: relative;
		}

		.invoice-title::after {
			content: '';
			position: absolute;
			bottom: -10px;
			left: 50%;
			transform: translateX(-50%);
			width: 100px;
			height: 3px;
			background: linear-gradient(90deg, #d4af37, #f4d03f);
			border-radius: 2px;
		}

		/* Content */
		.invoice-content {
			padding: 40px;
		}

		.recipient-section {
			background: #f8fafc;
			padding: 30px;
			border-radius: 8px;
			margin-bottom: 30px;
			border-left: 4px solid #3b82f6;
		}

		.recipient-title {
			font-size: 1.2rem;
			font-weight: 600;
			color: #1e3a8a;
			margin-bottom: 15px;
		}

		.recipient-name {
			font-size: 1.4rem;
			font-weight: 700;
			color: #2d3748;
		}

		/* Meta Information */
		.meta-section {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 30px;
			margin-bottom: 40px;
		}

		.meta-table {
			width: 100%;
			border-collapse: collapse;
		}

		.meta-table th {
			background: #1e3a8a;
			color: white;
			padding: 15px;
			text-align: left;
			font-weight: 600;
			font-size: 0.9rem;
		}

		.meta-table td {
			padding: 15px;
			border: 1px solid #e2e8f0;
			background: white;
			font-weight: 500;
		}

		/* Items Table */
		.items-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 30px;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
		}

		.items-table thead {
			background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
			color: white;
		}

		.items-table th {
			padding: 20px 15px;
			text-align: left;
			font-weight: 600;
			font-size: 0.9rem;
			letter-spacing: 0.5px;
		}

		.items-table td {
			padding: 20px 15px;
			border-bottom: 1px solid #e2e8f0;
			background: white;
		}

		.items-table tbody tr:hover {
			background: #f8fafc;
		}

		.items-table tbody tr:last-child td {
			border-bottom: none;
		}

		.rate, .quantity, .price {
			text-align: right;
			font-weight: 600;
		}

		.currency {
			color: #1e3a8a;
			font-weight: 700;
		}

		/* Balance Section */
		.balance-section {
			background: #f8fafc;
			padding: 30px;
			border-radius: 8px;
			margin-top: 30px;
		}

		.balance-table {
			width: 100%;
			border-collapse: collapse;
		}

		.balance-table th,
		.balance-table td {
			padding: 15px 20px;
			border-bottom: 1px solid #e2e8f0;
		}

		.balance-table th {
			background: #1e3a8a;
			color: white;
			font-weight: 600;
			text-align: left;
		}

		.balance-table td {
			background: white;
			text-align: right;
			font-weight: 600;
			font-size: 1.1rem;
		}

		.balance-table tr:last-child th,
		.balance-table tr:last-child td {
			border-bottom: none;
			background: #1e3a8a;
			color: white;
			font-size: 1.2rem;
			font-weight: 700;
		}

		/* Footer */
		.invoice-footer {
			background: #1e3a8a;
			color: white;
			padding: 30px 40px;
			text-align: center;
		}

		.footer-title {
			font-size: 1.3rem;
			font-weight: 600;
			margin-bottom: 20px;
		}

		.contact-info {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
			gap: 20px;
		}

		.contact-item {
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 0.95rem;
		}

		.contact-item .label {
			margin-right: 8px;
			width: 80px;
			font-weight: 600;
			opacity: 0.9;
			text-align: left;
		}

		/* Print Styles */
		@media print {
			body {
				background: white;
				padding: 0;
			}
			
			.invoice-container {
				box-shadow: none;
				border-radius: 0;
			}
			
			.invoice-header {
				background: #1e3a8a !important;
				-webkit-print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.items-table thead {
				background: #1e3a8a !important;
				-webkit-print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.balance-table th {
				background: #1e3a8a !important;
				-webkit-print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.balance-table tr:last-child th,
			.balance-table tr:last-child td {
				background: #1e3a8a !important;
				-webkit-print-color-adjust: exact;
				color-adjust: exact;
			}
		}

		/* Responsive */
		@media (max-width: 768px) {
			.hotel-details {
				grid-template-columns: 1fr;
			}
			
			.meta-section {
				grid-template-columns: 1fr;
			}
			
			.contact-info {
				grid-template-columns: 1fr;
			}
			
			.invoice-content {
				padding: 20px;
			}
			
			.invoice-header {
				padding: 30px 20px;
			}
		}
		</style>
		
	</head>
	<body>
	<?php
	ob_start();	
	include ('db.php');

	$pid = $_GET['pid'];
	
	$sql ="select * from payment where id = '$pid' ";
	$re = mysqli_query($con,$sql);
	while($row=mysqli_fetch_array($re))
	{
		$id = $row['id'];
		$title = $row['title'];
		$fname = $row['fname'];
		$lname = $row['lname'];
		$troom = $row['troom'];
		$bed = $row['tbed'];
		$nroom = $row['nroom'];
		$cin = $row['cin'];
		$cout = $row['cout'];
		$meal = $row['meal'];
		$ttot = $row['ttot'];
		$mepr = $row['mepr'];
		$btot = $row['btot'];
		$fintot = $row['fintot'];
		$days = $row['noofdays'];
	}
	
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
		?>

		<div class="invoice-container">
			<!-- Header -->
			<div class="invoice-header">
				<div class="hotel-info">
					<h1 class="hotel-name">RansHotel</h1>
					<p class="hotel-tagline">Luxury Hospitality Excellence</p>
					<div class="hotel-details">
						<div class="detail-item">
							<span class="label">Address:</span>
							<span>Tsito, Volta Region, Ghana</span>
						</div>
						<div class="detail-item">
							<span class="label">Phone:</span>
							<span>+233 (0)302 936 062</span>
						</div>
						<div class="detail-item">
							<span class="label">Email:</span>
							<span>info@ranshotel.com</span>
						</div>
						<div class="detail-item">
							<span class="label">Website:</span>
							<span>www.ranshotel.com</span>
						</div>
					</div>
				</div>
			</div>

			<!-- Invoice Title -->
			<div class="invoice-title">INVOICE</div>

			<!-- Content -->
			<div class="invoice-content">
				<!-- Recipient Section -->
				<div class="recipient-section">
					<div class="recipient-title">Bill To:</div>
					<div class="recipient-name"><?php echo $title . ' ' . $fname . ' ' . $lname; ?></div>
				</div>

				<!-- Meta Information -->
				<div class="meta-section">
					<table class="meta-table">
						<tr>
							<th>Invoice Number</th>
							<td><?php echo $id; ?></td>
						</tr>
						<tr>
							<th>Check-in Date</th>
							<td><?php echo $cin; ?></td>
						</tr>
						<tr>
							<th>Check-out Date</th>
							<td><?php echo $cout; ?></td>
						</tr>
					</table>
					<table class="meta-table">
						<tr>
							<th>Room Type</th>
							<td><?php echo $troom; ?></td>
						</tr>
						<tr>
							<th>Number of Rooms</th>
							<td><?php echo $nroom; ?></td>
				</tr>
				<tr>
							<th>Duration</th>
							<td><?php echo $days; ?> days</td>
				</tr>
					</table>
				</div>
				
				<!-- Items Table -->
				<table class="items-table">
				<thead>
					<tr>
							<th>Description</th>
							<th>Days</th>
							<th>Rate</th>
							<th>Qty</th>
							<th>Amount</th>
					</tr>
				</thead>
				<tbody>
					<tr>
							<td><?php echo $troom; ?></td>
							<td><?php echo $days; ?></td>
							<td class="rate"><span class="currency">₵</span><?php echo number_format($type_of_room, 2); ?></td>
							<td class="quantity"><?php echo $nroom; ?></td>
							<td class="price"><span class="currency">₵</span><?php echo number_format($ttot, 2); ?></td>
					</tr>
					<tr>
							<td><?php echo $bed; ?> Bed</td>
							<td><?php echo $days; ?></td>
							<td class="rate"><span class="currency">₵</span><?php echo number_format($type_of_bed, 2); ?></td>
							<td class="quantity"><?php echo $nroom; ?></td>
							<td class="price"><span class="currency">₵</span><?php echo number_format($btot, 2); ?></td>
					</tr>
					<tr>
							<td><?php echo $meal; ?></td>
							<td><?php echo $days; ?></td>
							<td class="rate"><span class="currency">₵</span><?php echo number_format($type_of_meal, 2); ?></td>
							<td class="quantity"><?php echo $nroom; ?></td>
							<td class="price"><span class="currency">₵</span><?php echo number_format($mepr, 2); ?></td>
					</tr>
				</tbody>
			</table>
			
				<!-- Balance Section -->
				<div class="balance-section">
					<table class="balance-table">
				<tr>
							<th>Subtotal</th>
							<td><span class="currency">₵</span><?php echo number_format($fintot, 2); ?></td>
				</tr>
				<tr>
							<th>Amount Paid</th>
							<td><span class="currency">₵</span>0.00</td>
				</tr>
				<tr>
							<th>Balance Due</th>
							<td><span class="currency">₵</span><?php echo number_format($fintot, 2); ?></td>
				</tr>
			</table>
				</div>
			</div>

			<!-- Footer -->
			<div class="invoice-footer">
				<div class="footer-title">Thank You for Choosing RansHotel</div>
				<div class="contact-info">
					<div class="contact-item">
						<span class="label">Email:</span>
						<span>info@ranshotel.com</span>
					</div>
					<div class="contact-item">
						<span class="label">Website:</span>
						<span>www.ranshotel.com</span>
					</div>
					<div class="contact-item">
						<span class="label">Phone:</span>
						<span>+233 (0)302 936 062</span>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
<?php
$free="Free";
$nul = null;
$rpsql = "UPDATE `room` SET `place`='$free',`cusid`='$nul' where `cusid`='$id'";
if(mysqli_query($con,$rpsql))
{
	$delsql= "DELETE FROM `roombook` WHERE id='$id' ";
	
	if(mysqli_query($con,$delsql))
	{
	
	}
}
?>
<?php 

ob_end_flush();

?>