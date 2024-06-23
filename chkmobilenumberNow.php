<?php
include("include/connection.php");
if(isset($_POST['type']))
{
if($_POST['type']=='chkmobile'){
	
$mobile=$_POST['fmobile'];
$otp=generateOTP();

$checkmobile=mysqli_query($con,"select * from `tbl_user` where `mobile` ='".$mobile."' and `status`='1'");
$row=mysqli_num_rows($checkmobile);
if($row==''){echo"0";}
else{
  $result=mysqli_fetch_array($checkmobile);
  
	session_start();
	unset($_SESSION["forgot_mobile"]);
	unset($_SESSION["forgot_otp"]);
  $_SESSION["forgot_mobile"] = $result['mobile'];
  $_SESSION["forgot_otp"] = $otp;
    
  	


$number='91'.$mobile;


$postData = "http://sms.mjsolutions.in/api/sendotp.php?authkey=382800AbJjX1gwF63299d06P1&mobiles=$number&message=%0AYou%20OTP%20is%20$otp%0ARegards%2C%0AShubhent&sender=SHUBWN&otp=$otp&DLT_TE_ID=1507166479362471412";


$result = file_get_contents($postData);

if ($result) {
  echo '1~'.encryptor('encrypt', $result['id']);
} else {
echo '0';
}
  
}
}
if($_POST['type']=='otpval'){
session_start();
@$otp=$_POST['otp'];
$mobile= $_SESSION["forgot_mobile"];
$sessionotp=$_SESSION["forgot_otp"];

if(strlen($sessionotp!==$otp))  
{
	echo"0";}else{
		

unset($_SESSION["forgot_mobile"]);
unset($_SESSION["forgot_otp"]);
$_SESSION["matched"] = "matched";
		echo"1";}
}

if($_POST['type']=='passwordreset'){
session_start();
@$userid=$_POST['userid'];
$fnpassword= $_POST["fnpassword"];
$fcpassword=$_POST["fcpassword"];

if(strlen($fnpassword!==$fcpassword))  
{
	echo"2";}else{
		
$sql2= mysqli_query($con,"UPDATE `tbl_user` SET `password`='".md5($fcpassword)."' where `id`='$userid'");
unset($_SESSION["matched"]);
		echo"1";}
}

}
?>