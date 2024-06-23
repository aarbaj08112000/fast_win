<?php
ob_start();
session_start();
if($_SESSION['frontuserid']=="")
{header("location:login.php");exit();}


?>

<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php include'head.php' ?>
	<link rel="stylesheet" href="assets/css/style.css?v=2">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<meta name="description" content="Bitter Mobile Template">
	<meta name="keywords" content="bootstrap, mobile template, bootstrap 4, mobile, html, responsive" />
	<style>
	.pleft {
		padding-left:3px;
	}
	.pright {
		padding-right:3px;
	}
	.height{ height:40px; line-height:40px;}
	.height .pageTitle{ line-height:2em;}

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

	<!-- * Page loading -->
	<!--<a href="http://lottlucy.com/apk/lottlucy.apk">

	<div class="appHeader1 height">
	<div class="text-center">
	<div class="pageTitle"><i class="icon ion-md-download"></i> Download App</div>
</div>

</div>
</a>-->
<!-- App Header -->
<div class="appHeader">
	<div class="pageTitle"  > <h1 style="color:green; font-size: 26px; font-family: arial;"> Welcome Back</h1></div>
</div>

<!-- App Capsule -->
<div id="appCapsule">
	<!-- Card Overlay Carousel -->

	<!-- * Card Overlay Carousel -->

	<div class="appContent">
		<div class="sectionTitle mb-2">

			<!-- post list -->
			<div class="vcard">
				<div class="row">
					<!-- item -->

					<div class="col-6 pleft">
						<div class="vcard card"> <a href="gamedashboard.php" class="postItem">
							<div class="imageWrapper"> <img src="parity.png" alt="image" class="image"> </div>

							<footer>Parity</footer>
						</a> </div>
					</div>
					<div class="col-6 pright">
						<div class="vcard card"> <a href="gamedashboard-fast.php" class="postItem">
							<div class="imageWrapper"> <img src="parity.png" alt="image" class="image"> </div>

							<footer>Fast Parity</footer>
						</a> </div>
					</div>
					<div class="col-6 pleft">
						<div class="vcard card"> <a href="andar_bahar.php" class="postItem">
							<div class="imageWrapper"> <img src="andarbahar.png" alt="image" class="image"> </div>
							<footer>Andar Bahar</footer>
						</a> </div>
					</div>
					<div class="col-6 pright">
						<div class="vcard card"> <a href="comingsoon.php" class="postItem">
							<div class="imageWrapper"> <img src="mini.png" alt="image" class="image"> </div>

							<footer>Mini Games</footer>
						</a> </div>
					</div>

					<!-- * item -->
					<!-- item -->


				</div>
			</div>
			<!-- * post list -->

			<div class="p-3"></div>

			<!-- * listview -->


		</div>

		<!-- app Footer -->

		<!-- * app Footer -->

	</div>
</div>
<!-- * appCapsule -->

<?php include("include/footer.php");?>

<!-- ///////////// Js Files ////////////////////  -->
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
