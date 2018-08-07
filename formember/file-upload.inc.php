<?php
function Uploads($FILES,$path="mimg"){
	if(@copy($FILES['tmp_name'],$path.$FILES['name'])){
		@chmod($path.$FILES,0777);
		return $FILES['name'];
	}else{
		return false;
	}
}
?>