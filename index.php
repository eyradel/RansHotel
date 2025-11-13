<?php
include('db.php');
require_once 'admin/includes/pricing_helper.php';

// Initialize pricing tables if they don't exist
PricingHelper::initializePricingTables($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>RANS HOTEL</title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Resort Inn Responsive , Smartphone Compatible web template , Samsung, LG, Sony Ericsson, Motorola web design" />
<meta name="description" content="RANS HOTEL - Located in Tsito, Ghana, Volta region. Comfortable accommodation with modern amenities in a serene environment. Book your stay today." />
<meta name="author" content="RANS HOTEL" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
		function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //for-mobile-apps -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/font-awesome.css" rel="stylesheet"> 
<link rel="stylesheet" href="css/chocolat.css" type="text/css" media="screen">
<link href="css/easy-responsive-tabs.css" rel='stylesheet' type='text/css'/>
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" property="" />
<link rel="stylesheet" href="css/jquery-ui.css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
    <link href="css/classic-design-system.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="js/modernizr-2.6.2.min.js"></script>
<!--fonts-->
<style>
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }

/* Carousel Fixes */
.rslides {
    position: relative;
    list-style: none;
    overflow: hidden;
    width: 100%;
    padding: 0;
    margin: 0;
}

.rslides li {
    -webkit-backface-visibility: hidden;
    position: absolute;
    display: none;
    width: 100%;
    left: 0;
    top: 0;
}

.rslides li:first-child {
    position: relative;
    display: block;
    float: left;
}

.rslides img {
    display: block;
    height: auto;
    float: left;
    width: 100%;
    border: 0;
}

.callbacks_container {
    position: relative;
    float: left;
    width: 100%;
}

.callbacks {
    position: relative;
    list-style: none;
    overflow: hidden;
    width: 100%;
    padding: 0;
    margin: 0;
}

.callbacks li {
    position: absolute;
    width: 100%;
    left: 0;
    top: 0;
}

.callbacks_tabs {
    list-style: none;
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
    margin: 0;
    padding: 0;
}

.callbacks_tabs li {
    display: inline-block;
    margin: 0 7px;
}

.callbacks_tabs a {
    text-indent: -9999px;
    overflow: hidden;
    display: block;
    width: 12px;
    height: 12px;
    background: rgba(255,255,255,0.3);
    border-radius: 50%;
    transition: all 0.3s ease;
}

.callbacks_tabs .rslides_here a {
    background: #ffce14;
}

.callbacks_tabs a:hover {
    background: rgba(255,255,255,0.6);
}

/* Feature Cards Responsive */
@media (max-width: 768px) {
    .feature-cards-section .feature-card {
        border-right: none !important;
        border-bottom: 1px solid var(--border-light);
        margin-bottom: 20px;
    }
    
    .feature-cards-section .feature-card:last-child {
        border-bottom: none;
    }
    
    .feature-cards-section h3 {
        font-size: 20px !important;
        margin-bottom: 30px !important;
    }
    
    .feature-icon i {
        font-size: 36px !important;
    }
    
    .feature-text {
        font-size: 12px !important;
    }
}

@media (max-width: 480px) {
    .feature-cards-section {
        padding: 40px 0 !important;
    }
    
    .feature-cards-section h3 {
        font-size: 18px !important;
        margin-bottom: 25px !important;
    }
    
    .feature-icon i {
        font-size: 32px !important;
    }
    
    .feature-text {
        font-size: 11px !important;
    }
    
    .feature-accent {
        width: 40px !important;
        height: 2px !important;
    }
}

/* Make room card images responsive */
.price-gd-top img {
    display: block;
    width: 100%;
    height: auto;
}

/* Always render room name below image to avoid overlap */
.price-gd-top h4 {
    position: static;
    background: #000;
    color: #fff;
    padding: 10px 12px;
    margin: 0;
    text-align: center;
}

/* Banner-bottom responsiveness: headline and three feature items */
.banner-bottom .agileits_banner_bottom h3 {
    text-align: center;
    line-height: 1.4;
    margin-bottom: 25px;
}

.w3ls_banner_bottom_grids .cbp-ig-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    padding: 0;
    margin: 0;
}

.w3ls_banner_bottom_grids .cbp-ig-grid li {
    list-style: none;
    flex: 1 1 30%;
    min-width: 220px;
}

@media (max-width: 992px) {
    .banner-bottom .agileits_banner_bottom h3 { font-size: 20px; }
}

@media (max-width: 768px) {
    .w3ls_banner_bottom_grids .cbp-ig-grid li { flex: 1 1 48%; }
}

@media (max-width: 480px) {
    .w3ls_banner_bottom_grids .cbp-ig-grid { gap: 12px; }
    .w3ls_banner_bottom_grids .cbp-ig-grid li { flex: 1 1 100%; }
}

/* Global responsive helpers */
img { max-width: 100%; height: auto; }

/* High-quality carousel rendering */
.w3layouts-banner-top {
    background-size: cover;
    background-position: center center;
    background-repeat: no-repeat;
}
@media (min-width: 769px) {
    .w3layouts-banner-top { min-height: 70vh; }
}
@media (max-width: 768px) {
    .w3layouts-banner-top { min-height: 50vh; }
}

/* Fixed navbar */
.w3_navigation {
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    z-index: 1000;
    width: 100%;
    box-shadow: 0 4px 16px rgba(15, 36, 83, 0.12);
}
.w3_navigation .navbar {
    background: transparent;
}

/* Navbar spacing on small screens */
@media (max-width: 768px) {
    .w3_navigation .navbar-default .navbar-nav > li > a {
        padding: 10px 12px;
        font-size: 13px;
    }
    .navbar-header .navbar-brand { font-size: 20px; }
}

/* Banner slider text scaling */
.agileits-banner-info h3 { font-size: 36px; }
.agileits-banner-info p { font-size: 16px; }
@media (max-width: 992px) {
    .agileits-banner-info h3 { font-size: 28px; }
    .agileits-banner-info p { font-size: 14px; }
}
@media (max-width: 768px) {
    .agileits-banner-info h3 { font-size: 22px; }
    .agileits-banner-info p { font-size: 13px; }
}

/* Gallery grid tighten spacing on mobile */
@media (max-width: 576px) {
    .portfolio-w3ls .gallery-grid { margin-bottom: 12px; }
}

/* Modal responsiveness */
@media (max-width: 576px) {
    .modal-dialog { width: 95%; margin: 10px auto; }
}

/* Visitors slider images */
.w3layouts_work_grid_left img { width: 100%; height: auto; }
.flexslider .slides > li { display: none; }
.flexslider .slides > li:first-child { display: block; }
.flexslider {
    border: none;
    box-shadow: none;
    background: transparent;
}

/* Rooms & Rates layout */
.priceing-table-main {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 30px;
}
.priceing-table-main .price-grid {
    float: none;
    margin: 0;
    padding: 0;
    flex: 1 1 260px;
    max-width: 300px;
}
.priceing-table-main .price-block {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    text-align: center;
}
.priceing-table-main .price-gd-top img {
    border-radius: 8px 8px 0 0;
}
.priceing-table-main .price-selet {
    justify-content: center;
}
@media (max-width: 768px) {
    .priceing-table-main {
        gap: 20px;
    }
    .priceing-table-main .price-grid {
        flex: 1 1 45%;
        max-width: 320px;
    }
}
@media (max-width: 576px) {
    .priceing-table-main .price-grid {
        flex: 1 1 100%;
        max-width: 100%;
    }
}
</style>
<!--//fonts-->
</head>
<body>
<!-- header -->
<div class="banner-top">
			<div class="social-bnr-agileits">
				<ul class="social-icons3">
								<li><a href="https://www.facebook.com/" class="fa fa-facebook icon-border facebook"> </a></li>
								<li><a href="https://twitter.com/" class="fa fa-twitter icon-border twitter"> </a></li>
								<li><a href="https://plus.google.com/u/0/" class="fa fa-google-plus icon-border googleplus"> </a></li> 
							</ul>
			</div>
			<div class="contact-bnr-w3-agile">
				<ul>
					<li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:info@ranshotel.com">INFO@RANSHOTEL.COM</a></li>
					<li><i class="fa fa-phone" aria-hidden="true"></i>+233 (0)302 936 062</li>	
					<li class="s-bar">
						<div class="search">
							<input class="search_box" type="checkbox" id="search_box">
							<label class="icon-search" for="search_box"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></label>
							<div class="search_form">
								<form action="#" method="post">
									<input type="search" name="Search" placeholder=" " required=" " />
									<input type="submit" value="Search">
								</form>
							</div>
						</div>
					</li>
				</ul>
			</div>
			<div class="clearfix"></div>
		</div>
	<div class="w3_navigation">
		<div class="container">
			<nav class="navbar navbar-default">
				<div class="navbar-header navbar-left">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<h1><a class="navbar-brand" href="index.php">RANS <span>HOTEL</span><p class="logo_w3l_agile_caption">Tsito, Ghana</p></a></h1>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
					<nav class="menu menu--iris">
						<ul class="nav navbar-nav menu__list">
							<li class="menu__item menu__item--current"><a href="" class="menu__link">Home</a></li>
							<li class="menu__item"><a href="#about" class="menu__link scroll">About</a></li>
							<li class="menu__item"><a href="#team" class="menu__link scroll">Team</a></li>
							<li class="menu__item"><a href="#gallery" class="menu__link scroll">Gallery</a></li>
							<li class="menu__item"><a href="#rooms" class="menu__link scroll">Rooms</a></li>
							<li class="menu__item"><a href="#contact" class="menu__link scroll">Contact Us</a></li>
						</ul>
					</nav>
				</div>
			</nav>

		</div>
	</div>
<!-- //header -->
		<!-- banner -->
	<div id="home" class="w3ls-banner">
		<!-- banner-text -->
		<div class="slider">
			<div class="callbacks_container">
				<ul class="rslides callbacks callbacks1" id="slider4">
					<li>
						<div class="w3layouts-banner-top" data-bg="images/rans/Rans Hotel-18.jpg" style="background-image:url(''); background-size:cover; background-position:center;">
							<div class="container">
								<div class="agileits-banner-info">
								<h4>Rans</h4>
									<h3>We know what you love</h3>
										<p>Welcome to our hotels</p>
									<div class="agileits_w3layouts_more menu__item">
				<a href="#" class="menu__link" data-toggle="modal" data-target="#myModal">Learn More</a>
			</div>
									</div>	
								</div>
							</div>
						</li>
					<li>
						<div class="w3layouts-banner-top w3layouts-banner-top1" data-bg="images/rans/Rans Hotel-47.jpg" style="background-image:url(''); background-size:cover; background-position:center;">
							<div class="container">
								<div class="agileits-banner-info">
								<h4>Rans</h4>
									<h3>Stay with friends & families</h3>
										<p>Come & enjoy precious moment with us</p>
									<div class="agileits_w3layouts_more menu__item">
				<a href="#" class="menu__link" data-toggle="modal" data-target="#myModal">Learn More</a>
			</div>
									</div>	
								</div>
							</div>
						</li>
					<li>
						<div class="w3layouts-banner-top w3layouts-banner-top2" data-bg="images/rans/Rans Hotel-67.jpg" style="background-image:url(''); background-size:cover; background-position:center;">
							<div class="container">
								<div class="agileits-banner-info">
								<h4>Rans</h4>
								<h3>want luxurious vacation?</h3>
										<p>Get accommodation today</p>
									<div class="agileits_w3layouts_more menu__item">
											<a href="#" class="menu__link" data-toggle="modal" data-target="#myModal">Learn More</a>
										</div>
								</div>
							</div>
							</div>
						</li>
					<li>
						<div class="w3layouts-banner-top" data-bg="images/rans/Rans Hotel-4.jpg" style="background-image:url(''); background-size:cover; background-position:center;">
							<div class="container">
								<div class="agileits-banner-info">
								<h4>Rans</h4>
									<h3>Comfort with scenic views</h3>
										<p>Relax and unwind at Rans Hotel</p>
									<div class="agileits_w3layouts_more menu__item">
				<a href="#" class="menu__link" data-toggle="modal" data-target="#myModal">Learn More</a>
			</div>
									</div>	
								</div>
							</div>
						</li>
					<li>
						<div class="w3layouts-banner-top" data-bg="images/rans/Rans Hotel-9.jpg" style="background-image:url(''); background-size:cover; background-position:center;">
							<div class="container">
								<div class="agileits-banner-info">
								<h4>Rans</h4>
									<h3>Experience premium hospitality</h3>
										<p>Your comfort is our priority</p>
									<div class="agileits_w3layouts_more menu__item">
				<a href="#" class="menu__link" data-toggle="modal" data-target="#myModal">Learn More</a>
			</div>
									</div>	
								</div>
							</div>
						</li>
					</ul>
			</div>
			<div class="clearfix"> </div>
			<!--banner Slider starts Here-->
		</div>
		<!-- Carousel Navigation -->
		<ul class="callbacks_tabs">
			<li><a href="#" class="callbacks1_s1">1</a></li>
			<li><a href="#" class="callbacks1_s2">2</a></li>
			<li><a href="#" class="callbacks1_s3">3</a></li>
		</ul>
		    <div class="thim-click-to-bottom">
				<a href="#about" class="scroll">
					<i class="fa fa-long-arrow-down" aria-hidden="true"></i>
				</a>
			</div>
	</div>	
	<!-- //banner --> 
<!--//Header-->
<!-- //Modal1 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
						<!-- Modal1 -->
							<div class="modal-dialog">
							<!-- Modal content-->
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4>SUN  <span>RISE</span></h4>
                                        <img src="images/rans/Rans Hotel-1.jpg" alt=" " class="img-responsive" loading="lazy">
										<h5>We know what you love</h5>
										<p>Providing guests unique and enchanting views from their rooms with its exceptional amenities, makes Star Hotel one of bests in its kind.Try our food menu, awesome services and friendly staff while you are here.</p>
									</div>
								</div>
							</div>
						</div>
<!-- //Modal1 -->
<div id="availability-agileits">
<div class="col-md-12 book-form-left-w3layouts">
	<a href="admin/reservation_classic.php"><h2>ROOM RESERVATION</h2></a>
</div>

			<div class="clearfix"> </div>
</div>
<!-- banner-bottom -->
	<div class="banner-bottom">
		<div class="container">	
			<div class="agileits_banner_bottom">
				<h3><span>Experience a good stay, enjoy fantastic offers</span> Find our friendly welcoming reception</h3>
			</div>
			<div class="w3ls_banner_bottom_grids">
				<ul class="cbp-ig-grid">
					<li>
						<div class="w3_grid_effect">
							<span class="cbp-ig-icon w3_road"></span>
							<h4 class="cbp-ig-title">COMFORT</h4>
							<span class="cbp-ig-category">Rans</span>
						</div>
					</li>
					<li>
						<div class="w3_grid_effect">
							<span class="cbp-ig-icon w3_cube"></span>
							<h4 class="cbp-ig-title">MOUNTAIN VIEW</h4>
							<span class="cbp-ig-category">Rans</span>
						</div>
					</li>
					<li>
						<div class="w3_grid_effect">
							<span class="cbp-ig-icon w3_ticket"></span>
							<h4 class="cbp-ig-title">WIFI COVERAGE</h4>
							<span class="cbp-ig-category">Rans</span>
						</div>
					</li>
				</ul>
			</div>
		</div>
	</div>
<!-- //banner-bottom -->
<!-- /about -->
 	<div class="about-wthree" id="about">
		  <div class="container">
				   <div class="ab-w3l-spa">
                            <h3 class="title-w3-agileits title-black-wthree">About Our Rans</h3> 
						   <p class="about-para-w3ls">RANS Hotel is located in the Volta region of Ghana, just 22km (13 miles) away from the city of Ho. Our hotel is situated in a beautiful and serene environment, surrounded by lush greenery and stunning views. We offer our guests a peaceful and relaxing getaway from the hustle and bustle of city life.</p>
                           <img src="images/rans/Rans Hotel-60.jpg" class="img-responsive" alt="Hair Salon" loading="lazy">
										<div class="w3l-slider-img">
                                            <img src="images/rans/Rans Hotel-63.jpg" class="img-responsive" alt="Hair Salon" loading="lazy">
										</div>
                                       <div class="w3ls-info-about">
										    <h4>You'll love all the amenities we offer!</h4>
											<p>Lorem ipsum dolor sit amet, ut magna aliqua. </p>
										</div>
		          </div>
		   	<div class="clearfix"> </div>
    </div>
</div>
 	<!-- //about -->
<!-- Feature Cards Section -->
<div class="feature-cards-section" style="background-color: #f8f9fa; padding: 60px 0;">
    <div class="container">
        <h3 class="title-w3-agileits title-black-wthree" style="text-align: center; margin-bottom: 50px; color: #ffce14; font-size: 24px; font-weight: 600;">
            FIND OUR FRIENDLY WELCOMING RECEPTION
        </h3>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12 feature-card" style="text-align: center; padding: 20px; border-right: 1px solid #dee2e6;">
                <div class="feature-icon" style="margin-bottom: 20px;">
                    <i class="fa fa-bed" style="font-size: 48px; color: #2c3e50;"></i>
                </div>
                <div class="feature-text" style="color: #2c3e50; font-size: 14px; font-weight: 600; text-transform: uppercase; margin-bottom: 15px;">
                    COMFORT
                </div>
                <div class="feature-accent" style="width: 60px; height: 3px; background-color: #ffce14; margin: 0 auto;"></div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 feature-card" style="text-align: center; padding: 20px; border-right: 1px solid #dee2e6;">
                <div class="feature-icon" style="margin-bottom: 20px;">
                    <i class="fa fa-picture-o" style="font-size: 48px; color: #2c3e50;"></i>
                </div>
                <div class="feature-text" style="color: #2c3e50; font-size: 14px; font-weight: 600; text-transform: uppercase; margin-bottom: 15px;">
                    MOUNTAIN VIEW
                </div>
                <div class="feature-accent" style="width: 60px; height: 3px; background-color: #ffce14; margin: 0 auto;"></div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12 feature-card" style="text-align: center; padding: 20px;">
                <div class="feature-icon" style="margin-bottom: 20px;">
                    <i class="fa fa-wifi" style="font-size: 48px; color: #2c3e50;"></i>
                </div>
                <div class="feature-text" style="color: #2c3e50; font-size: 14px; font-weight: 600; text-transform: uppercase; margin-bottom: 15px;">
                    WIFI COVERAGE
                </div>
                <div class="feature-accent" style="width: 60px; height: 3px; background-color: #ffce14; margin: 0 auto;"></div>
            </div>
        </div>
    </div>
</div>
<!-- //Feature Cards Section -->
<!--sevices-->
<!-- <div class="advantages">
	<div class="container">
		<div class="advantages-main">
				<h3 class="title-w3-agileits">Our Services</h3>
		   <div class="advantage-bottom">
			 <div class="col-md-6 advantage-grid left-w3ls wow bounceInLeft" data-wow-delay="0.3s">
			 	<div class="advantage-block ">
					<i class="fa fa-credit-card" aria-hidden="true"></i>
			 		<h4>Stay First, Pay After! </h4>
			 		<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates.</p>
					<p><i class="fa fa-check" aria-hidden="true"></i>Decorated room, proper air conditioned</p>
					<p><i class="fa fa-check" aria-hidden="true"></i>Private balcony</p>
			 		
			 	</div>
			 </div>
			 <div class="col-md-6 advantage-grid right-w3ls wow zoomIn" data-wow-delay="0.3s">
			 	<div class="advantage-block">
					<i class="fa fa-clock-o" aria-hidden="true"></i>
			 		<h4>24 Hour Restaurant</h4>
			 		<p>Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates.</p>
					<p><i class="fa fa-check" aria-hidden="true"></i>24 hours room service</p>
					<p><i class="fa fa-check" aria-hidden="true"></i>24-hour Concierge service</p>
			 	</div>
			 </div>
			<div class="clearfix"> </div>
			</div>
		</div>
	</div>
</div> -->
<!--//sevices-->
<!-- team removed -->
<!-- Gallery -->
<section class="portfolio-w3ls" id="gallery">
		 <h3 class="title-w3-agileits title-black-wthree">Our Gallery</h3>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-69.jpg" class="swipebox"><img src="images/rans/Rans Hotel-69.jpg" class="img-responsive" alt="Gallery Image" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-68.jpg" class="swipebox"><img src="images/rans/Rans Hotel-68.jpg" class="img-responsive" alt="Gallery Image" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-67.jpg" class="swipebox"><img src="images/rans/Rans Hotel-67.jpg" class="img-responsive" alt="Gallery Image" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-66.jpg" class="swipebox"><img src="images/rans/Rans Hotel-66.jpg" class="img-responsive" alt="Gallery Image" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-65.jpg" class="swipebox"><img src="images/rans/Rans Hotel-65.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
					</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-63.jpg" class="swipebox"><img src="images/rans/Rans Hotel-63.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
					   </div>
				   </a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-60.jpg" class="swipebox"><img src="images/rans/Rans Hotel-60.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
					   </div>
				   </a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-57.jpg" class="swipebox"><img src="images/rans/Rans Hotel-57.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
					   </div>
				   </a>
				</div>
					<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-56.jpg" class="swipebox"><img src="images/rans/Rans Hotel-56.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-47.jpg" class="swipebox"><img src="images/rans/Rans Hotel-47.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-9.jpg" class="swipebox"><img src="images/rans/Rans Hotel-9.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="col-md-3 col-sm-6 col-xs-12 gallery-grid gallery1">
						<a href="images/rans/Rans Hotel-8.jpg" class="swipebox"><img src="images/rans/Rans Hotel-8.jpg" class="img-responsive" alt="/" loading="lazy">
						<div class="textbox">
						<h4>Rans</h4>
							<p><i class="fa fa-picture-o" aria-hidden="true"></i></p>
						</div>
				</a>
				</div>
				<div class="clearfix"> </div>
</section>
<!-- //gallery -->
	 <!-- rooms & rates -->
      <div class="plans-section" id="rooms">
				 <div class="container">
				 <h3 class="title-w3-agileits title-black-wthree">Rooms And Rates</h3>
						<div class="priceing-table-main">
				 <?php
				 // Get room pricing from database and display rooms dynamically
				 $roomPricing = PricingHelper::getAllRoomPricing($con);
				 $roomTypes = [];
				 
				 // Group by room type and get the lowest price for display
				 foreach ($roomPricing as $price) {
					 if (!isset($roomTypes[$price['room_type']]) || $price['price_per_night'] < $roomTypes[$price['room_type']]) {
						 $roomTypes[$price['room_type']] = $price['price_per_night'];
					 }
				 }
				 
                 $roomImages = [
                     'Standard' => 'rans/Rans Hotel-58.jpg',
                     'Mini Executive' => 'rans/Rans Hotel-69.jpg', 
                     'Executive' => 'rans/Rans Hotel-63.jpg'
                 ];
				 
				 $roomStars = [
					 'Standard' => 3,
					 'Mini Executive' => 4,
					 'Executive' => 5
				 ];
				 
				 foreach ($roomTypes as $roomType => $minPrice) {
					 $stars = $roomStars[$roomType] ?? 3;
                     $image = $roomImages[$roomType] ?? 'rans/Rans Hotel-58.jpg';
					 
					 echo "<div class='col-md-3 col-sm-6 col-xs-12 price-grid'>";
					 echo "<div class='price-block agile'>";
					 echo "<div class='price-gd-top'>";
                     echo "<img src='images/$image' alt='$roomType' class='img-responsive' loading='lazy' />";
					 echo "<h4>$roomType</h4>";
					 echo "</div>";
					 echo "<div class='price-gd-bottom'>";
					 echo "<div class='price-list'>";
					 echo "<ul>";
					 
					 // Display stars
					 for ($i = 1; $i <= 5; $i++) {
						 if ($i <= $stars) {
							 echo "<li><i class='fa fa-star' aria-hidden='true'></i></li>";
						 } else {
							 echo "<li><i class='fa fa-star-o' aria-hidden='true'></i></li>";
						 }
					 }
					 
					 echo "</ul>";
					 echo "</div>";
					 echo "<div class='price-selet'>";
					 echo "<h3><span>₵</span>" . number_format($minPrice, 0) . "</h3>";
                     echo "<a href='admin/reservation_classic.php?embed=1'>Book Now</a>";
					 echo "</div>";
					 echo "</div>";
					 echo "</div>";
					 echo "</div>";
				 }
				 ?>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	 <!--// rooms & rates -->
  <!-- visitors -->
	<div class="w3l-visitors-agile" >
		<div class="container">
                 <h3 class="title-w3-agileits title-black-wthree">What other visitors experienced</h3> 
		</div>
		<div class="w3layouts_work_grids">
			<section class="slider">
				<div class="flexslider">
					<ul class="slides">
						<li>
							<div class="w3layouts_work_grid_left">
								<img src="images/rans/Rans Hotel-6.jpg" alt=" " class="img-responsive" />
								<div class="w3layouts_work_grid_left_pos">
									<img src="images/rans/Rans Hotel-4.jpg" alt=" " class="img-responsive" />
								</div>
							</div>
							<div class="w3layouts_work_grid_right">
								<h4>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								Exceptional Experience
								</h4>
								<p>Absolutely wonderful stay! The rooms are spacious and beautifully decorated with stunning mountain views. The staff went above and beyond to make our stay comfortable. The breakfast was delicious and the WiFi coverage throughout the hotel was excellent. We'll definitely be back!</p>
								<h5>Julia Rose</h5>
								<p>Germany</p>
							</div>
							<div class="clearfix"> </div>
						</li>
						<li>
							<div class="w3layouts_work_grid_left">
								<img src="images/rans/Rans Hotel-3.jpg" alt=" " class="img-responsive" />
								<div class="w3layouts_work_grid_left_pos">
									<img src="images/rans/Rans Hotel-1.jpg" alt=" " class="img-responsive" />
								</div>
							</div>
							<div class="w3layouts_work_grid_right">
								<h4>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								Perfect Getaway
								</h4>
								<p>This hotel exceeded all our expectations! The reception staff was incredibly welcoming and helpful. Our room was spotlessly clean and the bed was so comfortable. The mountain view from our window was breathtaking. Great value for money and we highly recommend it to anyone visiting the area.</p>
								<h5>Jonathan Smith</h5>
								<p>United States</p>
							</div>
							<div class="clearfix"> </div>
						</li>
						<li>
							<div class="w3layouts_work_grid_left">
								<img src="images/rans/Rans Hotel-15.jpg" alt=" " class="img-responsive" />
								<div class="w3layouts_work_grid_left_pos">
									<img src="images/rans/Rans Hotel-16.jpg" alt=" " class="img-responsive" />
								</div>
							</div>
							<div class="w3layouts_work_grid_right">
								<h4>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								Home Away From Home
								</h4>
								<p>We had an amazing time at Rans Hotel! The location is perfect, the facilities are top-notch, and the service is outstanding. The rooms are well-appointed with modern amenities. The staff was attentive and always ready to assist. A truly memorable stay that we'll cherish forever.</p>
								<h5>Rosalind Cloer</h5>
								<p>Italy</p>
							</div>
							<div class="clearfix"> </div>
						</li>
						<li>
							<div class="w3layouts_work_grid_left">
								<img src="images/rans/Rans Hotel-18.jpg" alt=" " class="img-responsive" />
								<div class="w3layouts_work_grid_left_pos">
									<img src="images/rans/Rans Hotel-17.jpg" alt=" " class="img-responsive" />
								</div>
							</div>
							<div class="w3layouts_work_grid_right">
								<h4>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								<i class="fa fa-star-o" aria-hidden="true"></i>
								Comfortable & Relaxing
								</h4>
								<p>Great hotel with excellent facilities! The rooms are comfortable and the mountain views are spectacular. The staff is friendly and professional. The hotel offers good amenities and the overall atmosphere is peaceful and relaxing. We enjoyed our stay and would recommend it to others.</p>
								<h5>Amie Bublitz</h5>
								<p>Switzerland</p>
							</div>
							<div class="clearfix"> </div>
						</li>
					</ul>
				</div>
			</section>
		</div>	
	</div>
  <!-- visitors -->
<!-- contact -->
<section class="contact-w3ls" id="contact">
	<div class="container">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 contact-w3-agile2" data-aos="flip-left">
			<div class="contact-agileits">
				<h4>Contact Us</h4>
				<p class="contact-agile2">Sign Up For Our News Letters</p>
				<form  method="post" name="sentMessage" id="contactForm" >
					<div class="control-group form-group">
                        
                            <label class="contact-p1">Full Name:</label>
                            <input type="text" class="form-control" name="name" id="name" required >
                            <p class="help-block"></p>
                       
                    </div>	
                    <div class="control-group form-group">
                        
                            <label class="contact-p1">Phone Number:</label>
                            <input type="tel" class="form-control" name="phone" id="phone" required >
							<p class="help-block"></p>
						
                    </div>
                    <div class="control-group form-group">
                        
                            <label class="contact-p1">Email Address:</label>
                            <input type="email" class="form-control" name="email" id="email" required >
							<p class="help-block"></p>
						
                    </div>
                    
                    
                    <input type="submit" name="sub" value="Send Now" class="btn btn-primary btn-block">	
				</form>
				<?php
				if(isset($_POST['sub']))
				{
					$name =$_POST['name'];
					$phone = $_POST['phone'];
					$email = $_POST['email'];
					$approval = "Not Allowed";
					$sql = "INSERT INTO `contact`(`fullname`, `phoneno`, `email`,`cdate`,`approval`) VALUES ('$name','$phone','$email',now(),'$approval')" ;
					
					
					if(mysqli_query($con,$sql))
					echo"OK";
					
				}
				?>
			</div>
		</div>
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 contact-w3-agile1" data-aos="flip-right">
			<h4>Connect With Us</h4>
                        <p class="contact-agile1"><strong>Phone :</strong>+233 (0)302 936 062</p>
                        <p class="contact-agile1"><strong>Email :</strong> <a href="mailto:info@ranshotel.com">INFO@RANSHOTEL.COM</a></p>
                        <p class="contact-agile1"><strong>Address :</strong> <a href="https://www.google.com/maps?q=6.5535639,0.2946795" target="_blank">Tsito, Volta Region, Ghana</a></p>
																
			<div class="social-bnr-agileits footer-icons-agileinfo">
				<ul class="social-icons3">
								<li><a href="#" class="fa fa-facebook icon-border facebook"> </a></li>
								<li><a href="#" class="fa fa-twitter icon-border twitter"> </a></li>
								<li><a href="#" class="fa fa-google-plus icon-border googleplus"> </a></li> 
								
							</ul>
			</div>
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.1234567890123!2d0.2946795!3d6.5535639!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2sRansHotel%2C%20Tsito%2C%20Ghana!5e0!3m2!1sen!2sgh!4v1703123456789" style="width: 100%; height: 200px; border: 0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
		</div>
		<div class="clearfix"></div>
	</div>
</section>
<!-- /contact -->
			<div class="copy">
		        <p>© 2023 RansHotel . All Rights Reserved | Design by <a href="index.php">RansHotel</a> </p>
		    </div>
<!--/footer -->
<!-- js -->
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script src="js/jquery.flexslider.js"></script>
<!-- ResponsiveSlides for carousel -->
<script src="js/responsiveslides.min.js"></script>
<script>
$(function() {
    $("#slider4").responsiveSlides({
        auto: true,
        pager: true,
        nav: true,
        speed: 500,
        namespace: "callbacks",
        before: function () {
            $('.events').append("<li>before event fired.</li>");
        },
        after: function () {
            $('.events').append("<li>after event fired.</li>");
        }
    });
    // Lazy-load carousel background images
    var bgObserver = ('IntersectionObserver' in window) ? new IntersectionObserver(function(entries){
        entries.forEach(function(entry){
            if(entry.isIntersecting){
                var el = entry.target;
                var bg = el.getAttribute('data-bg');
                if(bg){ el.style.backgroundImage = "url('" + bg + "')"; el.removeAttribute('data-bg'); }
                bgObserver.unobserve(el);
            }
        });
    }, { root: null, rootMargin: '200px', threshold: 0.01 }) : null;

    if(bgObserver){
        $(".w3layouts-banner-top").each(function(){ bgObserver.observe(this); });
    } else {
        // Fallback: set immediately
        $(".w3layouts-banner-top").each(function(){
            var bg = this.getAttribute('data-bg');
            if(bg){ this.style.backgroundImage = "url('" + bg + "')"; this.removeAttribute('data-bg'); }
        });
    }
    // Fix gallery URLs with spaces (encode to avoid empty items)
    $(".portfolio-w3ls .gallery-grid a").each(function(){
        var href = this.getAttribute('href');
        if(href && href.indexOf(' ') >= 0){ this.setAttribute('href', href.replace(/ /g, '%20')); }
        var img = this.querySelector('img');
        if(img){
            var src = img.getAttribute('src');
            if(src && src.indexOf(' ') >= 0){ img.setAttribute('src', src.replace(/ /g, '%20')); }
        }
    });
});
</script>
<!-- contact form -->
<script src="js/jqBootstrapValidation.js"></script>

<!-- /contact form -->	
<!-- Calendar -->
		<script src="js/jquery-ui.js"></script>
		<script>
				$(function() {
				$( "#datepicker,#datepicker1,#datepicker2,#datepicker3" ).datepicker();
				});
		</script>
<!-- //Calendar -->
<!-- gallery popup -->
<link rel="stylesheet" href="css/swipebox.css">
                <script src="js/jquery.swipebox.min.js"></script> 
                    <script type="text/javascript">
                        jQuery(function($) {
                            $(".swipebox").swipebox();
                        });
                    </script>
<!-- //gallery popup -->
<!-- start-smoth-scrolling -->
<script type="text/javascript" src="js/move-top.js"></script>
<script type="text/javascript" src="js/easing.js"></script>
<script type="text/javascript">
$(window).on('load', function () {
    if (typeof $.fn.flexslider === 'function') {
        $('.flexslider').flexslider({
            animation: 'slide',
            controlNav: true,
            directionNav: false,
            slideshowSpeed: 6000,
            animationSpeed: 600
        });
    } else {
        $('.flexslider .slides > li:first-child').show();
    }
});

	jQuery(document).ready(function(₵) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
<!-- Simplified slider - removed heavy libraries for better performance -->
		<!--search-bar-->
		<script src="js/main.js"></script>	
<!--//search-bar-->
<!--tabs-->
<script src="js/easy-responsive-tabs.js"></script>
<script>
$(document).ready(function () {
$('#horizontalTab').easyResponsiveTabs({
type: 'default', //Types: default, vertical, accordion           
width: 'auto', //auto or any width like 600px
fit: true,   // 100% fit in a container
closed: 'accordion', // Start closed if in accordion view
activate: function(event) { // Callback function if tab is switched
var $tab = $(this);
var $info = $('#tabInfo');
var $name = $('span', $info);
$name.text($tab.text());
$info.show();
}
});
$('#verticalTab').easyResponsiveTabs({
type: 'vertical',
width: 'auto',
fit: true
});
});
</script>
<!--//tabs-->
<!-- smooth scrolling -->
	<script type="text/javascript">
		$(document).ready(function() {
		/*
			var defaults = {
			containerID: 'toTop', // fading element id
			containerHoverID: 'toTopHover', // fading element hover id
			scrollSpeed: 1200,
			easingType: 'linear' 
			};
		*/								
		$().UItoTop({ easingType: 'easeOutQuart' });
		});
	</script>
	
	<div class="arr-w3ls">
	<a href="#home" id="toTop" style="display: block;"> <span id="toTopHover" style="opacity: 1;"> </span></a>
	</div>
<!-- //smooth scrolling -->
<script type="text/javascript" src="js/bootstrap-3.1.1.min.js"></script>
</body>
</html>


