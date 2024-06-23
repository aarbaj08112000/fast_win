<?php
include("include/connection.php");

if(isset($_POST['type']))
{
if($_POST['type']=='mobile'){
@$mobile=$_POST['mobile'];
$otp=generateOTP();
$chkuser=mysqli_query($con,"select * from `tbl_user` where `mobile`='".$mobile."'");
$userRow=mysqli_num_rows($chkuser);
if($userRow==''){

	session_start();
	unset($_SESSION["signup_mobile"]);
	unset($_SESSION["signup_otp"]);
  $_SESSION["signup_mobile"] = $mobile;
  $_SESSION["signup_otp"] = $otp;
	
	
	
$number='91'.$mobile;

$postData = "http://sms.mjsolutions.in/api/sendotp.php?authkey=382800AbJjX1gwF63299d06P1&mobiles=$number&message=%0AYou%20OTP%20is%20$otp%0ARegards%2C%0AShubhent&sender=SHUBWN&otp=$otp&DLT_TE_ID=1507166479362471412";


$result = file_get_contents($postData);





if ($err) {
  echo "cURL Error #:" . $err;
} else {
echo '1';
}


}else{echo"2";}

}
if($_POST['type']=='otpval'){
session_start();
@$otp=$_POST['otp'];
$mobile= $_SESSION["signup_mobile"];
$sessionotp=$_SESSION["signup_otp"];

if(strlen($sessionotp!==$otp))  
{
	echo"0";}else{
		
$_SESSION["signup_mobilematched"] = $_SESSION["signup_mobile"];
unset($_SESSION["signup_mobile"]);
unset($_SESSION["signup_otp"]);

		echo"1";}
}
}
?>
