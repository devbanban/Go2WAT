
<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<head>
<!-- Mobile Specific Meta -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Always force latest IE rendering engine -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<!-- Meta Keyword -->
<meta name="keywords" content="ท่องเที่ยววัด">
<!-- meta character set -->
<meta charset="utf-8">

<!-- Site Title -->
<title>ท่องเที่ยววัด</title>

<link rel="shortcut icon" href="fm/img/logo.gif">
<!--
        Google Fonts
        ============================================= -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700" rel="stylesheet" type="text/css">

<!--
        CSS
        ============================================= -->
<!-- Fontawesome -->
<link rel="stylesheet" href="fm/css/font-awesome.min.css">
<!-- Bootstrap -->
<link rel="stylesheet" href="fm/css/bootstrap.min.css">
<!-- Fancybox 
        <link rel="stylesheet" href="css/jquery.fancybox.css">-->
<!-- owl carousel -->
<link rel="stylesheet" href="fm/css/owl.carousel.css">
<!-- Animate -->
<link rel="stylesheet" href="fm/css/animate.css">
<!-- Main Stylesheet -->
<link rel="stylesheet" href="fm/css/main.css">
<!-- Main Responsive -->
<link rel="stylesheet" href="fm/css/responsive.css">

<!-- Modernizer Script for old Browsers -->
<script src="fm/js/vendor/modernizr-2.6.2.min.js"></script>











</head>

<body>
<!--menu -->
<?php include ("menutop.php"); ?>
<!--end menu --> 

<!--slider-->
<section id="home" class="hidden-xs">
  <div id="home-carousel" class="carousel slide" data-interval="false">
    <ol class="carousel-indicators">
      <li data-target="#home-carousel" data-slide-to="0" class="active"></li>
      <li data-target="#home-carousel" data-slide-to="1"></li>
    </ol>
    <!--/.carousel-indicators-->
    
    <div class="carousel-inner">
      <div class="item active"  style="background-image: url('fm/img/slider/bg1.jpg')">
        <div class="carousel-caption">
          <div class="animated bounceInRight">
            <?php  include("index_news.php"); ?>
          </div>
        </div>
      </div>
    </div>
    <!--/.carousel-inner
                <nav id="nav-arrows" class="nav-arrows hidden-xs hidden-sm visible-md visible-lg">
                    <a class="sl-prev hidden-xs" href="#home-carousel" data-slide="prev">
                       <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    </a>
                    <a class="sl-next" href="#home-carousel" data-slide="next">
                       <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    </a>
                </nav>--> 
    
  </div>
</section>

<!--End Slider --> 

<!--#gall-->
<section id="allgall">
  <div class="container">
    <div class="row  wow fadeInDown"> <br />
      <br />
      <div class="section-title ">
        <h3 align="center" style="color:#0CF"> แกลเลอรี่ 
          <br>
          <img src="img/line10.jpg" width="455" height="50">      </h3>
      </div>
    

<!--all  gall -->
<section>
	<div class="container">
    	<div class="row">
        	<div class="col-md-12" align="center">

            	<?php  include("all_gallery.php"); ?>
            </div>
       </div>     
	</div>
</section>


<!--end all gall-->


<!--#footer-->
<footer id="footer">
  <div class="container">
    <div class="row"> 
      <?php include("footer.php"); ?>
    </div>
  </div>
</footer>
<!--End #footer--> 

<!--avaScripts--> 

<!-- main jQuery --> 
<script src="fm/js/vendor/jquery-1.11.1.min.js"></script> 
<!-- Bootstrap --> 
<script src="fm/js/bootstrap.min.js"></script> 
<!-- jquery.nav --> 
<script src="fm/js/jquery.nav.js"></script> 
<!-- Portfolio Filtering --> 
<script src="fm/js/jquery.mixitup.min.js"></script> 
<!-- Fancybox
        <script src="js/jquery.fancybox.pack.js"></script> --> 
<!-- Parallax sections --> 
<script src="fm/js/jquery.parallax-1.1.3.js"></script> 
<!-- jQuery Appear --> 
<script src="fm/js/jquery.appear.js"></script> 
<!-- countTo --> 
<script src="fm/js/jquery-countTo.js"></script> 
<!-- owl carousel --> 
<script src="fm/js/owl.carousel.min.js"></script> 
<!-- WOW script --> 
<script src="fm/js/wow.min.js"></script> 
<!-- theme custom scripts --> 
<script src="fm/js/main.js"></script>
</body>
</html>

