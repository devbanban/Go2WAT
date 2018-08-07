<?php include ('Connections/connection.php'); ?>
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/my.css" rel="stylesheet" />





</head>




<body>
<div class="container">
  <div class="row">
    <div class="col-md-12">
      <form action="" method="POST" enctype="multipart/form-data" name="contact"  id="contact">
        <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td align="right">&nbsp;</td>
            <td colspan="2">&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td width="18%" align="right">รายละเอียด&nbsp;</td>
            <td colspan="2"><textarea name="detail" rows="3" required="required" class="form-control" id="detail" placeholder="กรุณากรอกข้อมูล"></textarea></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> ชื่อ &nbsp;</td>
            <td colspan="2"><input name="qname" type="text" id="qname"  class="form-control" placeholder="กรุณากรอกข้อมูล" required></td>
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
      </form>
      <h4 align="center">&nbsp; </h4>
    </div>
  </div>
</div>
</div>
