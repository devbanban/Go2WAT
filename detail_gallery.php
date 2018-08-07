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

$colname_detail_gall = "-1";
if (isset($_GET['id_gall'])) {
  $colname_detail_gall = $_GET['id_gall'];
}
mysql_select_db($database_connection, $connection);
$query_detail_gall = sprintf("SELECT * FROM tb_gallery WHERE id_gall = %s", GetSQLValueString($colname_detail_gall, "int"));
$detail_gall = mysql_query($query_detail_gall, $connection) or die(mysql_error());
$row_detail_gall = mysql_fetch_assoc($detail_gall);
$totalRows_detail_gall = mysql_num_rows($detail_gall);

$colname_img_gall = "-1";
if (isset($_GET['id_gall'])) {
  $colname_img_gall = $_GET['id_gall'];
}
mysql_select_db($database_connection, $connection);
$query_img_gall = sprintf("SELECT * FROM tb_gallery_img WHERE id_gall = %s ORDER BY id_gall_img ASC", GetSQLValueString($colname_img_gall, "int"));
$img_gall = mysql_query($query_img_gall, $connection) or die(mysql_error());
$row_img_gall = mysql_fetch_assoc($img_gall);
$totalRows_img_gall = mysql_num_rows($img_gall);


//count view 
$id_gall = $_REQUEST['id_gall'];
$count = mysql_query("SELECT view FROM tb_gallery WHERE id_gall=$id_gall");
$getview=mysql_result($count,0);
$getview=$getview+1;
$sql=mysql_query("UPDATE tb_gallery SET view=$getview WHERE id_gall=$id_gall");




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

<!-- Add jQuery library -->
	<script type="text/javascript" src="./fancybox/lib/jquery-1.10.1.min.js"></script>

	<!-- Add mousewheel plugin (this is optional) -->
	<script type="text/javascript" src="./fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>

	<!-- Add fancyBox main JS and CSS files -->
	<script type="text/javascript" src="./fancybox/source/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="./fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />

	<!-- Add Button helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="./fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
	<script type="text/javascript" src="./fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>

	<!-- Add Thumbnail helper (this is optional) -->
	<link rel="stylesheet" type="text/css" href="./fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
	<script type="text/javascript" src="./fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

	<!-- Add Media helper (this is optional) -->
	<script type="text/javascript" src="./fancyboxsource/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			 *  Simple image gallery. Uses default settings
			 */

			$('.fancybox').fancybox();

			/*
			 *  Different effects
			 */

			// Change title type, overlay closing speed
			$(".fancybox-effects-a").fancybox({
				helpers: {
					title : {
						type : 'outside'
					},
					overlay : {
						speedOut : 0
					}
				}
			});

			// Disable opening and closing animations, change title type
			$(".fancybox-effects-b").fancybox({
				openEffect  : 'none',
				closeEffect	: 'none',

				helpers : {
					title : {
						type : 'over'
					}
				}
			});

			// Set custom style, close if clicked, change title type and overlay color
			$(".fancybox-effects-c").fancybox({
				wrapCSS    : 'fancybox-custom',
				closeClick : true,

				openEffect : 'none',

				helpers : {
					title : {
						type : 'inside'
					},
					overlay : {
						css : {
							'background' : 'rgba(238,238,238,0.85)'
						}
					}
				}
			});

			// Remove padding, set opening and closing animations, close if clicked and disable overlay
			$(".fancybox-effects-d").fancybox({
				padding: 0,

				openEffect : 'elastic',
				openSpeed  : 150,

				closeEffect : 'elastic',
				closeSpeed  : 150,

				closeClick : true,

				helpers : {
					overlay : null
				}
			});

			/*
			 *  Button helper. Disable animations, hide close button, change title type and content
			 */

			$('.fancybox-buttons').fancybox({
				openEffect  : 'none',
				closeEffect : 'none',

				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,

				helpers : {
					title : {
						type : 'inside'
					},
					buttons	: {}
				},

				afterLoad : function() {
					this.title = 'Image ' + (this.index + 1) + ' of ' + this.group.length + (this.title ? ' - ' + this.title : '');
				}
			});


			/*
			 *  Thumbnail helper. Disable animations, hide close button, arrows and slide to next gallery item if clicked
			 */

			$('.fancybox-thumbs').fancybox({
				prevEffect : 'none',
				nextEffect : 'none',

				closeBtn  : false,
				arrows    : false,
				nextClick : true,

				helpers : {
					thumbs : {
						width  : 50,
						height : 50
					}
				}
			});

			/*
			 *  Media helper. Group items, disable animations, hide arrows, enable media and button helpers.
			*/
			$('.fancybox-media')
				.attr('rel', 'media-gallery')
				.fancybox({
					openEffect : 'none',
					closeEffect : 'none',
					prevEffect : 'none',
					nextEffect : 'none',

					arrows : false,
					helpers : {
						media : {},
						buttons : {}
					}
				});

			/*
			 *  Open manually
			 */

			$("#fancybox-manual-a").click(function() {
				$.fancybox.open('1_b.jpg');
			});

			$("#fancybox-manual-b").click(function() {
				$.fancybox.open({
					href : 'iframe.html',
					type : 'iframe',
					padding : 5
				});
			});

			$("#fancybox-manual-c").click(function() {
				$.fancybox.open([
					{
						href : '1_b.jpg',
						title : 'My title'
					}, {
						href : '2_b.jpg',
						title : '2nd title'
					}, {
						href : '3_b.jpg'
					}
				], {
					helpers : {
						thumbs : {
							width: 75,
							height: 50
						}
					}
				});
			});


		});
	</script>





















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
<section id="gall">
  <div class="container">
    <div class="row  wow fadeInDown"> <br />
      <br />
      <div class="section-title ">
        <h3 align="center" style="color:#0CF"> แกลเลอรี่ 
          <br>
          <img src="img/line10.jpg" width="455" height="50">      </h3>
      </div>
      
      <div class="col-md-12"> 
          <p align="center"> 
           		<?php echo $row_detail_gall['Title']; ?>
          <hr />
        </p>
       	<p align="center">
        <?php echo $row_detail_gall['Detail']; ?><br />
        สถานที่ : <?php echo $row_detail_gall['Location']; ?>
        เจ้าของกระทู้ : <?php echo $row_detail_gall['Addby']; ?>, 
		Post by : <?php echo $row_detail_gall['DateAdd']; ?>, 
		View : <?php echo $row_detail_gall['view']; ?> 
        </p>    
      </div>
    </div>
  </div>
</section>
<!--End #post--> 

<!-- img post -->
<section>
	<div class="container">
    	<div class="row">
        
        
        
        	<?php do { ?>
        	  <div class="col-md-3 wow fadeInUp">
              <p align="center">


 <a class="fancybox"  href="admin/gallery/<?php echo $row_img_gall['Img']; ?>" data-fancybox-group="gallery"> <img src="admin/gallery/<?php echo $row_img_gall['Img']; ?>" width="300" height="200" /> </a>
              
               		
           
                </p>
            </div>
        	  <?php } while ($row_img_gall = mysql_fetch_assoc($img_gall)); ?>
        </div>
  </div>
</section>         
<!-- img post -->


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
<script src="fm-/js/vendor/jquery-1.11.1.min.js"></script> 
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
mysql_free_result($detail_gall);

mysql_free_result($img_gall);
?>
