<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Go 2 wat</title>

<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/wat.css" rel="stylesheet" />
<style type="text/css">
input {
	margin: 1px;
}
</style>
</head>
<body>

<!--banner-->
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-12"> <img src="img/index/960-100.png" width="100%"  class="img-responsive" /> </div>
  </div>
</div>
<!--banner--> 

<!--menu-->
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-12">
      <?php include ("menu.php"); ?>
  </div>
</div>
</div>
<!--end menu -->

<div class="container">
  <div class="row">
    <div class="col-xs-12">
      <h4 align="center"> สมัครสมาชิก <br>
      </h4>
      <form action="" method="post" enctype="multipart/form-data" name="register" id="register"><br>
        <input name="Username" type="text" id="Username" class="form-control"  placeholder="username" required><br>
		<input name="Password" type="password" id="Password" class="form-control"  placeholder="password"  required> <br>
		<input name="Password2" type="password" id="Password2" class="form-control"  placeholder="Comfirm Password "  required> <br>
		<input name="Fname" type="text" id="Fname" class="form-control"  placeholder="Name" required><br>
		<input name="Lname" type="text" id="Lname" class="form-control" placeholder="Lastname" required> <br>
		<input name="Email" type="email" id="Email" class="form-control" placeholder="E-mail " required> <br>
		<input name="Phone" type="text" id="Phone" class="form-control"  placeholder="Phone" required> <br>
		<p align="center"> <a href="index.php" class="btn btn-warning btn-sm">ยกเลิก</a> &nbsp;&nbsp; &nbsp;
              <input type="submit" name="regis" id="regis" class="btn btn-info btn-sm" value="สมัครสมาชิก"> </p>
      </form>
      <h4 align="center">&nbsp; </h4>
  </div>
</div>
</div>

<!-- Footer -->
<div class="container">
  <div class="row">
    <div class="col-xs-12 col-md-12">
      <?php include ("footer.php"); ?>
      </div>
    </div>
  </div>
</div>
<!-- end Footer --> 

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) --> 
<script src="js/jquery-1.11.2.min.js"></script> 
<!-- Include all compiled plugins (below), or include individual files as needed --> 
<script src="js/bootstrap.min.js"></script>
</body>
</html>