<?php 
ob_start();

session_start();


?>



<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>REal Wins</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <meta name="description" content="Bitter Mobile Template">
  <meta name="keywords" content="bootstrap, mobile template, bootstrap 4, mobile, html, responsive" />
  <style>
.appHeader1 {
	background-color: #f44336 !important;
	border-color: #f44336 !important;
}
.card {
	border-radius:0px;
	padding:10px !important;
}
h3 {
	font-weight:normal;
	font-size:18px;
}
.razorpay-payment-button {
	padding: 10px 50px;
	color: #fff;
	background: #ff2e17;
	font-weight: 600;
	font-size: 14px;
	border: 1px solid #ff2e17;
	text-transform:uppercase;
}
.razorpay-payment-button:hover {
	color: #fff;
	background-color: #f33076;
	border-color: #f2246e;
	cursor:pointer;
}

.btn{ background-color: blue;
}
</style>
  
  </head>

  <body>
  <?php include("include/connection.php");?>
  

  <!-- App Header -->
  <div class="appHeader1">
    <div class="left"> <a href="#" onClick="goBack()" class="icon goBack"> <i class="icon ion-md-arrow-back"></i> </a>
      <div class="pageTitle">Pay Now</div>
    </div>
  </div>

    <?php include'head.php' ?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

    <br>
    <div class="conntainer-fluid col-md-12 d-flex justify-content-center">
	<div class="card col-md-9 shadow">
	<div id="appCapsule">
    <div class="appContent">
      <div class="sectionTitle3"> 
        
        <!-- post list -->
        <div class="">
          <div class="row"> 
            <!-- item -->
            <div class="col-12 pright">
              <div class="vcard card mt-5">
               
                  <tbody>
                      
                   
                  
                  <?php
                  $query = mysqli_query($con,"SELECT agent_id FROM agent_online where status=1 ORDER BY Rand() LIMIT 1 "
);

$row = mysqli_fetch_assoc($query);

$id12= $row['agent_id'];

if($id12 =='')
{
 echo "No agent Found!!";   
}
else{
$query1 = mysqli_query($con,"SELECT * FROM tbl_bankdetail where userid=$id12 and status=1");


while($row1 = mysqli_fetch_array($query1))
{


                  ?> 
                  <table class="table table-borderless">
                  <thead>
                   
                  </thead>
                  
                  	<form action="payment.php" class="form-control"  id="payment_form" method="post" enctype="multipart/form-data">
                   <table class="table table-borderless">
                  <thead>
                   
                  </thead>
                  
                  	<form action="payment.php" class="form-control"  id="payment_form" method="post">
                    <tr>
                      <td>Name </td>
                      <td><?php echo $row1['name'];?></td>
                    </tr>
                    <tr>
                      <td>Account </td>
                      <td><?php echo $row1['account'];?></td>
                    </tr>
                    <tr>
                      <td>IFSC</td>
                      <td><?php echo $row1['ifsc'];?></td>
                    </tr>
                    <tr>
                      <td>UPI</td>
                      <td><?php echo $row1['upid'];?></td>
                    </tr>
                                        <tr>
                      <td>Whatsapp</td>
                      <td><a href="https://wa.me/<?php echo $row1['whatsapp'];?>">Click here to send screenshot</a></td>
                    </tr>
                    <tr>
                      <td>Payable Amount </td>
                      <td>₹ <?php echo number_format($_SESSION['finalamount'],2);?></td>
                    </tr>
                    <tr>
                      <td>Upload Screenshot </td>
                      <td> <input type="file" name="ss" id="ss"></td>
                    </tr>
                  </tbody>
                  <input type="hidden" class="form-control"  id="amount" name="amount" placeholder="Transaction ID" value="<?php echo $_SESSION['finalamount'];?>" />
			
				<input type="hidden" class="form-control"  id="agent" name="agent" placeholder="Amount" value="<?php echo $id12;?>" readonly />
				
				<input type="hidden" class="form-control"  id="userid" name="userid" placeholder="Product Info" value="<?php echo $_SESSION['frontuserid']; ?>" />
				
			<input type="submit" class="form-control"  id="submit" name="submit"  value="Make Payment" style="background: #3dd915;" /> 
		
                  </form>
                </table> <table class="table table-borderless">
                  <thead>
                   
                  </thead>
                  
                  	<form action="payment.php" class="form-control"  id="payment_form" method="post">
                <?php } }?>
              </div>
            </div>
          </div>
        </div>
	
		<div>
			
		</div>
			
		  <div class="form-group">

			<form action="" class="form-control"  id="payment_form" method="post">
			
		
			<input type="hidden" class="form-control"  id="udf5" name="udf5" value="PayUBiz_PHP7_Kit" />					
    
		
				<input type="hidden" class="form-control"  id="ORDER_ID" name="txnid" placeholder="Transaction ID" value="<?php echo  "Txn" . rand(10000,99999999)?>" />
			
				<input type="hidden" class="form-control"  id="amount" name="amount" placeholder="Amount" value="<?php echo number_format($_SESSION['finalamount'],2, '.', '');?>" readonly />
				
				<input type="hidden" class="form-control"  id="productinfo" name="productinfo" placeholder="Product Info" value="<?php echo $_SESSION['frontuserid']; ?>" />
				
				<input type="hidden" class="form-control"  id="firstname" name="firstname" placeholder="First Name" value="" />
				<input type="hidden" class="form-control"  id="Lastname" name="Lastname" placeholder="Last Name" value="" />
    
				<input type="hidden" class="form-control"  id="Zipcode" name="Zipcode" placeholder="Zip Code" value="" />
		
    
			
			    <input type="hidden" name="surl" value="https://meeshoclub.com/payment-success.php" />
				
				<input type="hidden" class="form-control"  id="email" name="email" placeholder="Email ID" value="<?php echo $_SESSION['email'];?>" readonly />
				
				
				<input type="hidden" class="form-control"  id="phone" name="phone" placeholder="Mobile/Cell Number" value="<?php echo $_SESSION['mobile'];?>" readonly />	
				
		<input type="hidden" class="form-control"  id="address1" name="address1" placeholder="Address1" value="" /> 
							
				<input class="form-control"  type="hidden" id="address2" name="address2" placeholder="Address2" value="" />
				<input class="form-control"  type="hidden" id="city" name="city" placeholder="City" value="" />
				<input type="hidden" class="form-control"  id="state" name="state" placeholder="State" value="" />
				 <input type="hidden" class="form-control"  id="country" name="country" placeholder="Country" value="" />
				<input type="hidden" class="form-control"  id="Pg" name="Pg" placeholder="PG" value="upi" /> 
			
    
			 
			</form>
		
		
		
		<?php if($html) echo $html; //submit request to PayUBiz  ?>
			</div> 

		
	</div> 
	<script type="text/javascript">		
		<!--
		function frmsubmit()
		{
			document.getElementById("payment_form").submit();	
			return true;
		}
		
	</script>

 
</body>
</html>