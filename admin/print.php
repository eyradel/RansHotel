
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

		.detail-item i {
			margin-right: 10px;
			width: 20px;
			text-align: center;
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

		.contact-item i {
			margin-right: 8px;
			width: 20px;
			text-align: center;
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
		<header>
			<h1>Invoice</h1>
			<address >
				<p>RansHotel,</p>
				<p>Tsito,<br>Volta Region,<br>Ghana.</p>
				<p>+233 (0)302 936 062</p>
				<p>info@ranshotel.com</p>
			</address>
			<span><img alt="" src="assets/img/sun.png"></span>
		</header>
		<article>
			<h1>Recipient</h1>
			<address >
				<p><?php echo $title.$fname." ".$lname ?> <br></p>
			</address>
			<table class="meta">
				<tr>
					<th><span >Invoice #</span></th>
					<td><span ><?php echo $id; ?></span></td>
				</tr>
				<tr>
					<th><span >Date</span></th>
					<td><span ><?php echo $cout; ?> </span></td>
				</tr>
				
			</table>
			<table class="inventory">
				<thead>
					<tr>
						<th><span >Item</span></th>
						<th><span >No of Days</span></th>
						<th><span >Rate</span></th>
						<th><span >Quantity</span></th>
						<th><span >Price</span></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><span ><?php echo $troom; ?></span></td>
						<td><span ><?php echo $days; ?> </span></td>
						<td><span data-prefix>₵</span><span ><?php  echo $type_of_room;?></span></td>
						<td><span ><?php echo $nroom;?> </span></td>
						<td><span data-prefix>₵</span><span><?php echo $ttot; ?></span></td>
					</tr>
					<tr>
						<td><span ><?php echo $bed; ?>  Bed </span></td>
						<td><span ><?php echo $days; ?></span></td>
						<td><span data-prefix>₵</span><span ><?php  echo $type_of_bed;?></span></td>
						<td><span ><?php echo $nroom;?> </span></td>
						<td><span data-prefix>₵</span><span><?php echo $btot; ?></span></td>
					</tr>
					<tr>
						<td><span ><?php echo $meal; ?>  </span></td>
						<td><span ><?php echo $days; ?></span></td>
						<td><span data-prefix>₵</span><span ><?php  echo $type_of_meal?></span></td>
						<td><span ><?php echo $nroom;?> </span></td>
						<td><span data-prefix>₵</span><span><?php echo $mepr; ?></span></td>
					</tr>
				</tbody>
			</table>
			
			<table class="balance">
				<tr>
					<th><span >Total</span></th>
					<td><span data-prefix>₵</span><span><?php echo $fintot; ?></span></td>
				</tr>
				<tr>
					<th><span >Amount Paid</span></th>
					<td><span data-prefix>₵</span><span >0.00</span></td>
				</tr>
				<tr>
					<th><span >Balance Due</span></th>
					<td><span data-prefix>₵</span><span><?php echo $fintot; ?></span></td>
				</tr>
			</table>
		</article>
		<aside>
			<h1><span >Contact us</span></h1>
			<div >
				<p align="center">Email :- info@Rans.com || Web :- www.Rans.com || Phone :- +94 65 222 44 55 </p>
			</div>
		</aside>
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