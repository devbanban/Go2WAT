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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "contact")) {
  $insertSQL = sprintf("INSERT INTO tb_contact (detail, q_name, email) VALUES (%s, %s, %s)",
                       GetSQLValueString($_POST['detail'], "text"),
                       GetSQLValueString($_POST['q_name'], "text"),
                       GetSQLValueString($_POST['email'], "text"));

  mysql_select_db($database_connection, $connection);
  $Result1 = mysql_query($insertSQL, $connection) or die(mysql_error());

  $insertGoTo = "contact_ok.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $insertGoTo .= (strpos($insertGoTo, '?')) ? "&" : "?";
    $insertGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $insertGoTo));
}
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html lang="en" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html lang="en" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
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
        <link rel="shortcut icon" href="fm/img/logo.png" />
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




 
              

				
					
                    <div class="item" style="background-image: url('fm/img/slider/bg1.jpg')">                 
                         <div class="carousel-caption">
                            <div class="animated bounceInUp">
                                <h4>พระปรางค์วัดอรุณฯ ยามค่ำคืน</h4>
                                <p></p>
                            </div>
                        </div>
                    </div>
               
                    
                </div>
                

            </div>
        </section>
		
		
<!--End Slider -->

		
	




       
        


   
       
<!--#register-->
        <section id="contact">
            <div class="container">
				<div class="row">
                <br /><br>  <h1>  </h1><br><br>

                	<div class="col-md-12">

                    <div class="section-title text-center wow fadeInDown">
                        <h2>แจ้งปัญหาการใช้งาน</h2>
					</div>
                    
                    
<div class="container">
  <div class="row">
    <div class="col-md-12">
    
      <form action="<?php echo $editFormAction; ?>" method="POST" enctype="multipart/form-data" name="contact"  id="contact">
        <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="18%" align="right" valign="top">รายละเอียด&nbsp;</td>
            <td colspan="2"><textarea name="detail" rows="3" required class="form-control" id="detail" placeholder="กรุณากรอกข้อมูล"></textarea></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> ชื่อ &nbsp;</td>
            <td colspan="2"><input name="q_name" type="text" id="q_name"  class="form-control" placeholder="กรุณากรอกข้อมูล" required></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> E-mail &nbsp; </td>
            <td width="31%"><input name="email" type="email" id="Email" class="form-control" placeholder="เช่น abc@gmail.com " required></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="left">
            
            <input type="reset" name="reset" id="reset" class="btn btn-warning btn-sm" value="Reset">
             &nbsp;&nbsp; &nbsp;
              <input type="submit" name="regis" id="regis" class="btn btn-info btn-sm" value="แจ้งปัญหา" >
            
            </td>
          </tr>
          <tr>
            <td colspan="4" align="center"></td>
          </tr>
          <tr>
            <td align="right"><br /></td>
            <td>&nbsp;</td>
            <td width="10%">&nbsp;</td>
            <td width="41%">&nbsp;</td>
          </tr>
        </table>
        <input type="hidden" name="MM_insert" value="contact">
      </form>                         
                    </div>
 
				</div>
         
        </section>
<!--End #register-->





        
        
        
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
