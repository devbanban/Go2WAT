
<!-- Bootstrap -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/my.css" rel="stylesheet" />

<script src="js/jquery-2.1.1.min.js"></script>
<script type="text/javascript"><!--
function checkPasswordMatch() {
    var password = $("#Password").val();
    var confirmPassword = $("#Password2").val();

    if (password != confirmPassword)
        $("#divCheckPasswordMatch").html(" * Password ไม่ตรงกันกรุณากรอกใหม่!");
    else
        $("#divCheckPasswordMatch").html("");
}
//--></script>




<script src="js/jquery.form.min.js"> </script>
<script>
$(function() {
	$(document).on('change', '#img', function() {
		if(this.files[0].size > 204800) {
			alert('ไฟล์ภาพมีขนาดใหญ่เกินกำหนด (200 KB) กรุณา Resize รูปภาพก่อนทำการ Upload ขอบคุณครับ');
			//$(this).replaceWith($(this).clone());
			$('input:file').clearInputs(); 
		}
	});
});
</script>


</head>




<body onLoad="test()">

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <form action="register_db.php" method="POST" enctype="multipart/form-data" name="register"  id="register">
        <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="18%" align="right"> Username &nbsp;</td>
            <td width="31%"><input name="Username" type="text" id="Username" class="form-control" placeholder="ภาษาอังกฤษหรือตัวเลข" required> <span id="msg2"></span> </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> Password &nbsp;</td>
            <td><input name="Password" type="password" id="Password" class="form-control" placeholder="อย่างน้อย 8 ตัว"  required></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> Comfirm&nbsp; Password&nbsp;</td>
            <td><input name="Password2" type="password" id="Password2" placeholder="คอนเฟิร์ม Password " class="form-control"  required   onkeyup="checkPasswordMatch();">
<div class="registrationFormAlert" id="divCheckPasswordMatch">            </td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> ชื่อ &nbsp;</td>
            <td colspan="2"><input name="Fname" type="text" id="Fname"  class="form-control" placeholder="ภาษาไทยหรืออังกฤษ" required></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> นามสกุล &nbsp;</td>
            <td colspan="2"><input name="Lname" type="text" id="Lname" size="50" class="form-control" placeholder="ภาษาไทยหรืออังกฤษ" required></td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right"> E-mail &nbsp; </td>
            <td><input name="Email" type="email" id="Email" class="form-control" placeholder="เช่น abc@gmail.com " required></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">เบอร์โทรศัพท์ &nbsp; </td>
            <td><input name="Phone" type="text" id="Phone" class="form-control" placeholder="เช่น 091 999 9999" required></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td align="right">ภาพโปรไฟล์&nbsp; </td>
            <td align="left">
            <input type="file" name="img" id="img" accept="image/jpeg" required> * ไม่เกิน 200KB</td>
            <td align="left">&nbsp;</td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="center"><input name="AccessLevel" type="hidden" id="AccessLevel" value="M"></td>
          </tr>
          <tr>
            <td align="center">&nbsp;</td>
            <td colspan="3" align="left">
            
            <input type="reset" name="reset" id="reset" class="btn btn-warning btn-sm" value="Reset">
             &nbsp;&nbsp; &nbsp;
              <input type="submit" name="regis" id="regis" class="btn btn-info btn-sm" value="สมัครสมาชิก" >
            
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
