<?php 
ob_start();
session_start();
if($_SESSION['frontuserid']=="")
{header("location:login.php");exit();}?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php include'head.php' ?>
<link rel="stylesheet" href="assets/css/style.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
.appHeader1 {
	background-color: #fff !important;
	border-color: #fff !important;
}
.appContent3 {
	background-color: #0080FF !important;
	border-color: #FF0000 !important;
	padding:12px;
	border-radius:10px;
	font-size:16px;
}
.user-block img {
	width: 40px;
	height: 40px;
	float: left;
	margin-right:10px;
	background:#FFFFFF;
}
.img-circle {
	border-radius: 50%;
}
.accordion .btn-link {
	box-shadow:none;
	padding:8px !important;
	margin:0px 0px;
	color: #333 !important;
	font-size: 17px;
	font-weight: normal;
	border-top:solid 1px #ccc;
}
.accordion .collapsed {
	border:none;
}
.accordion .show {
	border-bottom:solid 1px #ccc;
}
.accordion .sub-link {
	box-shadow:none;
	padding:8px !important;
	color: #333 !important;
	font-size: 14px;
	font-weight: normal;
	display:block;
}
.accordion .sub-link:hover {
color:#00F !important;
}
.accordion .btn-link:hover {
	background:#F5F5F5;
}
.accordion .btn-link {
	position: relative;
}
 .accordion .btn-link::after {
 content: "\f107";
 color: #333;
 top: 8px;
 right: 9px;
 position: absolute;
 font-family: "FontAwesome";
 font-size:24px;
}
 .accordion .btn-link[aria-expanded="true"]::after {
 content: "\f106";
}
.light{
    height: 24px;
    padding: 0px 0px;
	margin: 5px 2px;
	border-radius: 20px;
width: 24px;}
.light1{
    height: 26px;
    padding: 0px 0px;
	margin: 5px 2px;
	border-radius: 20px;
width: 26px;}

</style>
</head>

<body>
<?php
include("include/connection.php");
$userid=$_SESSION['frontuserid'];
$selectruser=mysqli_query($con,"select * from `tbl_user` where `id`='".$userid."'");
$userresult=mysqli_fetch_array($selectruser);
$selectwallet=mysqli_query($con,"select * from `tbl_wallet` where `userid`='".$userid."'");
$walletResult=mysqli_fetch_array($selectwallet);
?>
<!-- Page loading -->
<div class="loading" id="loading">
  <div class="spinner-grow"></div>
</div>
<style>
/* The switch - the box around the slider */
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
      margin-top: 12px;
    margin-left: 36%;
}

/* Hide default HTML checkbox */
.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

/* The slider */
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ff0b0b;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #07f51a;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<!-- * Page loading --> 

<!-- App Header -->
<div class="vcard">
  <div class="appContent3 text-white">
    <div class="row">
      <div class="col-12 mb-1">
        <div class="user-block"> <img class="img-circle img-bordered-lg" src="assets/img/avatar.svg"> </div>
        User: Member <?php echo user($con,'id',$userid);?><br>
        ID: <?php echo sprintf("%06d",user($con,'id',$userid));?>
        </div>
        <div class="col-12 mb-1">Mobile: +91 <?php echo user($con,'mobile',$userid);?></div>
      <div class="col-12 mb-1">Available balance: ₹ <?php echo number_format(wallet($con,'amount',$userid), 2);?></div>
     <div class="col-12">
     <a href="recharge.php" class="btn btn-sm btn-success pull-left m-0">Recharge</a> 
      
      
      </div>
    </div>
  </div>
</div>
</div>
<?php
$selectruser1=mysqli_query($con,"select status from `agent_online` where `agent_id`='".$userid."'");
$userresult1=mysqli_fetch_assoc($selectruser1);
$status=$userresult1['status'];
?>

<label class="switch">
    <input type="hidden" id="userid" value=<?php echo $userid; ?>>
    <?php if($status==1) { ?>
  <input type="checkbox" id="myCheck" onclick="online()" checked>
  <span class="slider round"></span>
   <?php } else{ ?>
   <input type="checkbox" id="myCheck" onclick="online()">
  <span class="slider round"></span>
   <?php }  ?>
</label>
<script>

function online(){
    var checkBox = document.getElementById("myCheck");
  // Get the output text
 // var text = document.getElementById("text");
var userid=$('#userid').val();
  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    	$.ajax({
    type: "Post",
    data:"userid=" + userid,
    url: "agentonline.php",
    success: function (html) {
     //alert(html);
	 var arr = html.split('~');
	 //alert(arr[1]);
	
      return false;
      },
      error: function (e) {}
      });
  } else {
   	$.ajax({
    type: "Post",
    data:"userid=" + userid,
    url: "agentoffline.php",
    success: function (html) {
     //alert(html);
	 var arr = html.split('~');
	 //alert(arr[1]);
	 document.getElementById("gameid").innerHTML=arr[0];
	 document.getElementById("inputgameid").value=arr[0];
	 document.getElementById("futureid").value=arr[0];
      return false;
      },
      error: function (e) {}
      });
  }
  
    
}
</script>
<!-- searchBox --> 

<!-- * searchBox --> 
<!-- * App Header --> 

<!-- App Capsule -->
<div class="appContent1 mb-5">
  <div class="contentBox long mb-3">
    <div class="contentBox-body card-body"> 
      
      <!-- listview -->
      
      <div class="accordion" id="accordionExample">
          
          <?php
          $sql=mysqli_query($con,"select * from `tbl_user` where `id` ='".$userid."' and `is_agent`=1");
$rows=mysqli_num_rows($sql);
if($rows!=''){
          
          ?>
          
           <div class="card-header">
            <h2 class="mb-0"> <a href="history.php" class="btn btn-link collapsed">History </a> </h2>
          </div>
          <?php }
          
          
          
          ?>
      
      <h2>Requests</h2>
      
      <?php 
           $sql=mysqli_query($con,"select * from `recharge_request` where `agent_id` ='".$userid."' and `status`=0");

          ?>
     
                      <?php

  $i=0; 
  while($row=mysqli_fetch_array($sql)){$i++;?>  
                 <div style="border: 1px solid;">
           <p style="float:left">  <?php //echo @$row["user_id"]; 
           
           $sql12=mysqli_query($con,"select mobile from `tbl_user` where `id` ='".@$row["user_id"]."'");
           $row12=mysqli_fetch_assoc($sql12);
           echo $row12['mobile'];
           
           ?></p>
             <p style="float:right"> <?php echo '₹'.$row['amount'];?></p>
              <br>
              <button><a href="<?php echo $row['ss'];?>" target="_blank">Screen Shot</a></button><br>
           <a href="confirm.php?id=<?php echo $row['id'];  ?>&user=<?php echo $row["user_id"];?>&agent=<?php echo $row["agent_id"];?>&amount=<?php echo $row["amount"];?> "  class="update-person" style="color:#090; font-size:16px;" data-toggle="tooltip" title="Make Agent"><i class="fa fa-check-square-o"></i>Confirm Recharge</a><br>
             <a href="reject.php?id=<?php echo $row['id'];  ?>&user=<?php echo $row["user_id"];?>&agent=<?php echo $row["agent_id"];?>&amount=<?php echo $row["amount"];?> "  class="update-person confirmation" style="color:#ff210a; font-size:16px;" data-toggle="tooltip" title="Reject"><i class="fa fa-check-square-o"></i>Reject Recharge</a>
              <br>
              </div>
              <?php
}?>
      <!-- * listview --> 
      
    </div>
  </div>
  
  <!-- app Footer -->
  <div class="text-center mt-4"> <a href="logout.php" class="btn btn-sm btn-light" style="width:200px; background-image: linear-gradient(
#FF0000, 
#29B6F6);">Log out</a> </div>
  <!-- * app Footer --> 
  
</div>
<!-- appCapsule -->
<?php include("include/footer.php");?>
<!-- Jquery --> 
<script src="assets/js/lib/jquery-3.4.1.min.js"></script> 
<!-- Bootstrap--> 
<script src="assets/js/lib/popper.min.js"></script> 
<script src="assets/js/lib/bootstrap.min.js"></script> 
<!-- Owl Carousel --> 
<script src="assets/js/plugins/owl.carousel.min.js"></script> 
<!-- Main Js File --> 
<script src="assets/js/app.js"></script>
</body>
</html>