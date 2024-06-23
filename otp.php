<?php
$otp="1234";
$number='917304649420';

$fields = array(
    "authkey" => "382800AbJjX1gwF63299d06P1",
    "mobiles" => "917304649420",
    "message" => "%0AYou%20OTP%20is%201234%0ARegards%2C%0AShubhent",
    "sender" => "SHUBWN",
    "otp" => "1234",
    "DLT_TE_ID" => "1507166479362471412"
);
//$curl = curl_init();
//http://sms.mjsolutions.in/api/sendotp.php?
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://sms.mjsolutions.in/api/sendotp.php",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode($fields),
  CURLOPT_HTTPHEADER => array(
    
    "accept: */*",
    "cache-control: no-cache",
    "content-type: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
echo '1';
}



?>