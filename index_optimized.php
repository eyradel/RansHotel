<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>RANS HOTEL</title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="RANS HOTEL - Located in Tsito, Ghana, Volta region. Comfortable accommodation with modern amenities in a serene environment. Book your stay today." />
<meta name="author" content="RANS HOTEL" />

<!-- Optimized CSS - Only essential styles -->
<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />

<!-- Preload critical resources -->
<link rel="preload" href="js/jquery-2.1.4.min.js" as="script">
<link rel="preload" href="css/style.css" as="style">

<!-- Remove heavy external fonts - use system fonts instead -->
<style>
body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
</style>
</head>
<body>
<!-- header -->
<div class="banner-top">
    <div class="social-bnr-agileits">
        <ul class="social-icons3">
            <li><a href="#" class="fa fa-facebook icon-border facebook"> </a></li>
            <li><a href="#" class="fa fa-twitter icon-border twitter"> </a></li>
            <li><a href="#" class="fa fa-google-plus icon-border googleplus"> </a></li>
            <li><a href="#" class="fa fa-pinterest icon-border pinterest"> </a></li>
        </ul>
    </div>
    <div class="contact-bnr-w3-agileits">
        <ul>
            <li><i class="fa fa-envelope" aria-hidden="true"></i><a href="mailto:info@ranshotel.com">info@ranshotel.com</a></li>
            <li><i class="fa fa-phone" aria-hidden="true"></i>+233 (0)302 936 062</li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>
<div class="banner">
    <div class="container">
        <div class="header-nav">
            <nav class="navbar navbar-default">
                <div class="navbar-header logo">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <h1><a class="navbar-brand" href="index.php">RansHotel</a></h1>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
                    <nav class="cl-effect-13" id="cl-effect-13">
                        <ul class="nav navbar-nav">
                            <li><a href="index.php" class="active">Home</a></li>
                            <li><a href="about.html">About</a></li>
                            <li><a href="typography.html">Services</a></li>
                            <li><a href="gallery.html">Gallery</a></li>
                            <li><a href="contact.html">Contact</a></li>
                        </ul>
                    </nav>
                </div>
            </nav>
        </div>
    </div>
</div>

<!-- banner-bottom -->
<div class="banner-bottom">
    <div class="container">
        <div class="col-md-7 banner-bottom-left">
            <h3>Welcome to <span>RansHotel</span></h3>
            <p>RANS Hotel is located in the Volta region of Ghana, just 22km (13 miles) away from the city of Ho. Our hotel is situated in a beautiful and serene environment, surrounded by lush greenery and stunning views. We offer our guests a peaceful and relaxing getaway from the hustle and bustle of city life.</p>
            <div class="view">
                <a href="single.html" class="hvr-shutter-in-horizontal">Read More</a>
            </div>
        </div>
        <div class="col-md-5 banner-bottom-right">
            <div class="banner-bottom-right1">
                <div class="banner-bottom-right1pos">
                    <img src="images/1.jpg" alt=" " class="img-responsive" />
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- //banner-bottom -->

<!-- services -->
<div class="services">
    <div class="container">
        <h3 class="title">Our Services</h3>
        <div class="services-grids">
            <div class="col-md-4 services-grid">
                <div class="services-grid1">
                    <div class="col-md-2 services-grid-left">
                        <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-10 services-grid-right">
                        <h4>Luxury Rooms</h4>
                        <p>Comfortable and well-appointed rooms with modern amenities.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4 services-grid">
                <div class="services-grid1">
                    <div class="col-md-2 services-grid-left">
                        <span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-10 services-grid-right">
                        <h4>Restaurant</h4>
                        <p>Delicious local and international cuisine served in our restaurant.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-md-4 services-grid">
                <div class="services-grid1">
                    <div class="col-md-2 services-grid-left">
                        <span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                    </div>
                    <div class="col-md-10 services-grid-right">
                        <h4>Relaxation</h4>
                        <p>Peaceful environment perfect for relaxation and rejuvenation.</p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- //services -->

<!-- rooms -->
<div class="rooms">
    <div class="container">
        <h3 class="title">Our Rooms</h3>
        <div class="room-grids">
            <div class="col-md-6 room-grid">
                <div class="room-grid1">
                    <img src="images/r1.jpg" alt=" " class="img-responsive" />
                    <div class="room-grid1-pos">
                        <h4>Deluxe Room</h4>
                        <p>₵550 <span>per night</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 room-grid">
                <div class="room-grid1">
                    <img src="images/r2.jpg" alt=" " class="img-responsive" />
                    <div class="room-grid1-pos">
                        <h4>Superior Room</h4>
                        <p>₵450 <span>per night</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 room-grid">
                <div class="room-grid1">
                    <img src="images/r3.jpg" alt=" " class="img-responsive" />
                    <div class="room-grid1-pos">
                        <h4>Guest House</h4>
                        <p>₵350 <span>per night</span></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 room-grid">
                <div class="room-grid1">
                    <img src="images/r4.jpg" alt=" " class="img-responsive" />
                    <div class="room-grid1-pos">
                        <h4>Single Room</h4>
                        <p>₵250 <span>per night</span></p>
                    </div>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- //rooms -->

<!-- contact -->
<div class="contact" id="contact">
    <div class="container">
        <h3 class="title">Contact Us</h3>
        <div class="contact-grids">
            <div class="col-md-8 contact-left">
                <form action="admin/reservation.php" method="post">
                    <input type="text" name="fname" placeholder="First Name" required="">
                    <input type="text" name="lname" placeholder="Last Name" required="">
                    <input type="email" name="email" placeholder="Email" required="">
                    <input type="text" name="phone" placeholder="Phone" required="">
                    <textarea name="message" placeholder="Message" required=""></textarea>
                    <input type="submit" value="Send">
                </form>
            </div>
            <div class="col-md-4 contact-right">
                <div class="contact-right1">
                    <h4>Address :</h4>
                    <p><a href="https://www.google.com/maps?q=6.5535639,0.2946795" target="_blank">Tsito, Volta Region, Ghana</a></p>
                    <h4>Phone :</h4>
                    <p>+233 (0)302 936 062</p>
                    <h4>Email :</h4>
                    <p><a href="mailto:info@ranshotel.com">info@ranshotel.com</a></p>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<!-- //contact -->

<!-- footer -->
<div class="footer">
    <div class="container">
        <div class="footer-grids">
            <div class="col-md-3 footer-grid">
                <h3>About RansHotel</h3>
                <p>Located in Tsito, Ghana, we provide comfortable accommodation with modern amenities in a serene environment.</p>
            </div>
            <div class="col-md-3 footer-grid">
                <h3>Services</h3>
                <ul>
                    <li><a href="#">Luxury Rooms</a></li>
                    <li><a href="#">Restaurant</a></li>
                    <li><a href="#">Conference Hall</a></li>
                    <li><a href="#">Free WiFi</a></li>
                </ul>
            </div>
            <div class="col-md-3 footer-grid">
                <h3>Contact Info</h3>
                <ul>
                    <li>Tsito, Volta Region, Ghana</li>
                    <li>+233 (0)302 936 062</li>
                    <li><a href="mailto:info@ranshotel.com">info@ranshotel.com</a></li>
                </ul>
            </div>
            <div class="col-md-3 footer-grid">
                <h3>Follow Us</h3>
                <div class="social">
                    <ul>
                        <li><a href="#" class="facebook"> </a></li>
                        <li><a href="#" class="twitter"> </a></li>
                        <li><a href="#" class="google"> </a></li>
                    </ul>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<div class="footer-bottom">
    <div class="container">
        <p>© 2023 RansHotel. All rights reserved | Design by <a href="http://w3layouts.com/">W3layouts</a></p>
    </div>
</div>
<!-- //footer -->

<!-- Load only essential JavaScript -->
<script src="js/jquery-2.1.4.min.js"></script>
<script src="js/bootstrap-3.1.1.min.js"></script>

<!-- Minimal JavaScript for basic functionality -->
<script>
$(document).ready(function() {
    // Smooth scrolling for anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top
            }, 1000);
        }
    });
    
    // Basic form validation
    $('form').on('submit', function(e) {
        var isValid = true;
        $(this).find('input[required], textarea[required]').each(function() {
            if ($(this).val() === '') {
                isValid = false;
                $(this).addClass('error');
            } else {
                $(this).removeClass('error');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });
});
</script>

<style>
.error { border: 2px solid #ff0000 !important; }
</style>

</body>
</html>
