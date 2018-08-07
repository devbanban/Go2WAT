<?php require_once('Connections/connection.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$colname_detail_news = "-1";
if (isset($_GET['News_id'])) {
  $colname_detail_news = $_GET['News_id'];
}
mysql_select_db($database_connection, $connection);
$query_detail_news = sprintf("SELECT * FROM tb_news WHERE News_id = %s", GetSQLValueString($colname_detail_news, "int"));
$detail_news = mysql_query($query_detail_news, $connection) or die(mysql_error());
$row_detail_news = mysql_fetch_assoc($detail_news);
$totalRows_detail_news = mysql_num_rows($detail_news);
?>
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
<link rel="shortcut icon" href="fm/img/logo.gif" />
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
<style type="text/css">
#textc {
	line-height: 16pt;
	height: 32pt;
	overflow: hidden;/*white-space: nowrap;
	width: 70em;
	overflow: hidden;
	text-overflow: ellipsis;
	border: 0px solid #000000;*/
}
.fa {
	color: #69F;
}
.font1 {
	color: #093;
	font-size: 24px;
}
#Form {
	background-color: #f5f5f5;
}
 @media screen and (max-width:400px) {
.h3f {
	font-size: 15px;
}
#h2f {
	font-size: 17px;
}
}
body, td, th {
	color: #666;
}
</style>
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

<!--#news-->
<section id="detailnews">
  <div class="container">
    <div class="row  wow fadeInDown"> <br />
      <br />
      <div class="section-title ">
        <h3 align="center" style="color:#0CF"> บอกบุญ <br>
        <img src="img/line10.jpg" width="455" height="50">        </h4>
      </div>
      <div class="col-md-6"> <img src="admin/file_news/<?php echo $row_detail_news['File_news']; ?>" width="500"><br>
      </div>
      <div class="col-md-6"> <br />
        <br />
        <?php echo $row_detail_news['Title']; ?><br>
        <?php echo $row_detail_news['Detail']; ?><br>
        <?php echo $row_detail_news['Location']; ?><br>
      <?php echo $row_detail_news['Addby']; ?>, <?php echo $row_detail_news['DateAdd']; ?>, <?php echo $row_detail_news['view']; ?> </div>
    </div>
  </div>
</section>
<!--End #news--> 



<!--fb  -->
<section>
	<div class="container">
    	<div class="row">
        	<div class="col-md-12" align="center">
            <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>


<div class="fb-comments" data-href="https://www.facebook.com/go2watbkk" data-width="800" data-numposts="20" data-colorscheme="light"></div>
            
            </div>
       </div>     
</div>
</section>


<!--fb  -->



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
<?php
mysql_free_result($detail_news);
?>
