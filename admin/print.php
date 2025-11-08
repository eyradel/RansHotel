
<html>
	<head>
		<meta charset="utf-8">
		<title>Invoice</title>
		<link rel="stylesheet" href="style.css">
		<link rel="license" href="https://www.opensource.org/licenses/mit-license/">
		<script src="script.js"></script>
		<style>
		/* RansHotel Elegant Invoice Styles */
		
		@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Cormorant+Garamond:wght@300;400;600&family=Lato:wght@300;400;700&display=swap');
		
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Lato', 'Georgia', serif;
			background: #f5f3f0;
			padding: 40px 20px;
			min-height: 100vh;
			color: #2c2c2c;
		}

		.invoice-container {
			max-width: 900px;
			margin: 0 auto;
			background: #ffffff;
			box-shadow: 0 0 50px rgba(0, 0, 0, 0.08);
			overflow: hidden;
			border: 1px solid #e8e5e0;
		}

		/* Elegant Header */
		.invoice-header {
			background: #1a1a1a;
			color: #f5f3f0;
			padding: 60px 50px;
			position: relative;
			border-bottom: 4px solid #d4af37;
		}

		.invoice-header::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: 
				repeating-linear-gradient(
					45deg,
					transparent,
					transparent 2px,
					rgba(212, 175, 55, 0.03) 2px,
					rgba(212, 175, 55, 0.03) 4px
				);
			opacity: 0.5;
		}

		.hotel-info {
			position: relative;
			z-index: 1;
		}

		.hotel-name {
			font-family: 'Playfair Display', serif;
			font-size: 3.5rem;
			font-weight: 700;
			margin-bottom: 8px;
			letter-spacing: 2px;
			color: #f5f3f0;
			text-transform: uppercase;
		}

		.hotel-tagline {
			font-family: 'Cormorant Garamond', serif;
			font-size: 1.4rem;
			font-weight: 300;
			font-style: italic;
			color: #d4af37;
			margin-bottom: 35px;
			letter-spacing: 1px;
		}

		.hotel-details {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 25px;
			margin-top: 35px;
			padding-top: 35px;
			border-top: 1px solid rgba(212, 175, 55, 0.3);
		}

		.detail-item {
			display: flex;
			align-items: flex-start;
			font-size: 0.95rem;
			line-height: 1.8;
			color: #e8e5e0;
		}

		.detail-item .label {
			margin-right: 12px;
			width: 90px;
			font-weight: 600;
			color: #d4af37;
			text-transform: uppercase;
			font-size: 0.85rem;
			letter-spacing: 0.5px;
		}

		/* Elegant Invoice Title */
		.invoice-title {
			text-align: center;
			font-family: 'Playfair Display', serif;
			font-size: 4rem;
			font-weight: 400;
			letter-spacing: 8px;
			margin: 50px 0;
			color: #1a1a1a;
			position: relative;
			text-transform: uppercase;
		}

		.invoice-title::before,
		.invoice-title::after {
			content: '';
			position: absolute;
			top: 50%;
			width: 120px;
			height: 1px;
			background: linear-gradient(90deg, transparent, #d4af37, transparent);
		}

		.invoice-title::before {
			left: 10%;
		}

		.invoice-title::after {
			right: 10%;
		}

		/* Content */
		.invoice-content {
			padding: 50px;
		}

		.recipient-section {
			background: #faf9f7;
			padding: 35px 40px;
			margin-bottom: 45px;
			border-left: 5px solid #d4af37;
			border-top: 1px solid #e8e5e0;
			border-right: 1px solid #e8e5e0;
			border-bottom: 1px solid #e8e5e0;
		}

		.recipient-title {
			font-family: 'Cormorant Garamond', serif;
			font-size: 1.1rem;
			font-weight: 400;
			color: #8b7355;
			margin-bottom: 12px;
			text-transform: uppercase;
			letter-spacing: 2px;
			font-style: italic;
		}

		.recipient-name {
			font-family: 'Playfair Display', serif;
			font-size: 1.8rem;
			font-weight: 600;
			color: #1a1a1a;
			letter-spacing: 0.5px;
		}

		/* Meta Information */
		.meta-section {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 40px;
			margin-bottom: 50px;
		}

		.meta-table {
			width: 100%;
			border-collapse: collapse;
			border: 1px solid #e8e5e0;
		}

		.meta-table th {
			background: #1a1a1a;
			color: #f5f3f0;
			padding: 18px 20px;
			text-align: left;
			font-weight: 600;
			font-size: 0.85rem;
			text-transform: uppercase;
			letter-spacing: 1px;
			border-bottom: 2px solid #d4af37;
		}

		.meta-table td {
			padding: 18px 20px;
			border-bottom: 1px solid #f0ede8;
			background: #faf9f7;
			font-weight: 400;
			color: #2c2c2c;
			font-size: 1rem;
		}

		.meta-table tr:last-child td {
			border-bottom: none;
		}

		/* Items Table */
		.items-table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 40px;
			border: 1px solid #e8e5e0;
		}

		.items-table thead {
			background: #1a1a1a;
			color: #f5f3f0;
		}

		.items-table th {
			padding: 22px 20px;
			text-align: left;
			font-weight: 600;
			font-size: 0.85rem;
			letter-spacing: 1.5px;
			text-transform: uppercase;
			border-bottom: 3px solid #d4af37;
			font-family: 'Lato', sans-serif;
		}

		.items-table th:last-child,
		.items-table td:last-child {
			text-align: right;
		}

		.items-table td {
			padding: 22px 20px;
			border-bottom: 1px solid #f0ede8;
			background: #ffffff;
			color: #2c2c2c;
			font-size: 1rem;
		}

		.items-table tbody tr {
			transition: background 0.2s ease;
		}

		.items-table tbody tr:nth-child(even) {
			background: #faf9f7;
		}

		.items-table tbody tr:last-child td {
			border-bottom: none;
		}

		.rate, .quantity, .price {
			text-align: right;
			font-weight: 600;
			font-family: 'Lato', sans-serif;
		}

		.currency {
			color: #d4af37;
			font-weight: 700;
			font-size: 1.1em;
		}

		/* Balance Section */
		.balance-section {
			background: #faf9f7;
			padding: 0;
			margin-top: 40px;
			border: 1px solid #e8e5e0;
		}

		.balance-table {
			width: 100%;
			border-collapse: collapse;
		}

		.balance-table th,
		.balance-table td {
			padding: 20px 30px;
			border-bottom: 1px solid #e8e5e0;
		}

		.balance-table th {
			background: #1a1a1a;
			color: #f5f3f0;
			font-weight: 600;
			text-align: left;
			font-size: 0.95rem;
			text-transform: uppercase;
			letter-spacing: 1px;
			border-bottom: 2px solid #d4af37;
		}

		.balance-table td {
			background: #ffffff;
			text-align: right;
			font-weight: 600;
			font-size: 1.1rem;
			color: #2c2c2c;
		}

		.balance-table tr:last-child th,
		.balance-table tr:last-child td {
			border-bottom: none;
			background: #1a1a1a;
			color: #d4af37;
			font-size: 1.4rem;
			font-weight: 700;
			padding: 25px 30px;
			font-family: 'Playfair Display', serif;
		}

		/* Footer */
		.invoice-footer {
			background: #1a1a1a;
			color: #f5f3f0;
			padding: 45px 50px;
			text-align: center;
			border-top: 4px solid #d4af37;
		}

		.footer-title {
			font-family: 'Cormorant Garamond', serif;
			font-size: 1.6rem;
			font-weight: 400;
			font-style: italic;
			margin-bottom: 30px;
			color: #d4af37;
			letter-spacing: 1px;
		}

		.contact-info {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
			gap: 25px;
			padding-top: 30px;
			border-top: 1px solid rgba(212, 175, 55, 0.3);
		}

		.contact-item {
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 0.95rem;
			color: #e8e5e0;
		}

		.contact-item .label {
			margin-right: 10px;
			width: 90px;
			font-weight: 600;
			color: #d4af37;
			text-transform: uppercase;
			font-size: 0.85rem;
			letter-spacing: 0.5px;
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
				border: none;
			}
			
			.invoice-header {
				background: #1a1a1a !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.items-table thead {
				background: #1a1a1a !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.meta-table th {
				background: #1a1a1a !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.balance-table th {
				background: #1a1a1a !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.balance-table tr:last-child th,
			.balance-table tr:last-child td {
				background: #1a1a1a !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
				color-adjust: exact;
			}
			
			.invoice-footer {
				background: #1a1a1a !important;
				-webkit-print-color-adjust: exact;
				print-color-adjust: exact;
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