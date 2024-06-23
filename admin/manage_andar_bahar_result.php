<?php
ob_start();
session_start();
if($_SESSION['userid']=="")
{
	header("location:index.php?msg1=notauthorized");
	exit();
}

$column [] = ['data' => "result", 'title' => "Result", "width" => "8%", 'className' => 'dt-center'];
$column [] = ['data' => "number_count", 'title' => "Number", "width" => "7%", 'className' => 'dt-left'];
$column [] = ['data' => "user_count", 'title' => "No. of User	", "width" => "7%", 'className' => 'dt-left'];
$column [] = ['data' => "amount", 'title' => "Amount", "width" => "7%", 'className' => 'dt-left'];
$column [] = ['data' => "action", 'title' => "Action", "width" => "8%", 'className' => 'dt-center'];
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Adminsuit | Manage Winning Result</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="css/ionicons.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins
	folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
	<!-- Morris chart -->
	<link rel="stylesheet" href="plugins/morris/morris.css">
	<!-- jvectormap -->
	<link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
	<!-- Date Picker -->
	<link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
	<!-- bootstrap wysihtml5 - text editor -->
	<link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
	<link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="plugins/select2/select2.min.css">
	<link rel="stylesheet" href="plugins/iCheck/all.css">
	<link href="css/custom.css" rel="stylesheet" type="text/css">
	<!--  data table js and css -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
	<!-- swal alert js and css -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.2.0/sweetalert2.min.css">
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

	<link rel="stylesheet" href="css/app.css" id="maincss">
	<link rel="stylesheet" href="css/style.css" id="maincss">
	<style>
	#overlay {
		position:absolute;
		display: none;
		width: 100%;
		height: 70px;
		top: 0;
		left: 0;
		right: 0;
		bottom: 0;
		background-color: rgba(0,0,0,0.5);
		z-index: 9;
		cursor: pointer;
	}
	.timer_block {
		color: #f00;
	}
	.paginate_button{
		cursor: pointer;
		margin-left: 0;
		background: #fafafa;
		color: #666;
		position: relative;
		/* float: left; */
		padding: 6px 12px;
		margin-left: -1px;
		line-height: 1.42857143;
		color: #337ab7;
		text-decoration: none;
		background-color: #fff;
		border: 1px solid #ddd;
	}
	table.dataTable th.dt-left,
	table.dataTable td.dt-left {
		text-align: left;
	}
	table.dataTable th.dt-center,
	table.dataTable td.dt-center,
	table.dataTable td.dataTables_empty {
		text-align: center;
	}
	table.dataTable th.dt-right,
	table.dataTable td.dt-right {
		text-align: right;
	}
	.win_btn {
		display: inline-block;
		color: #fff;
		background-color: #28a745;
		border-color: #28a745;
		font-weight: 400;
		text-align: center;
		white-space: nowrap;
		cursor: pointer;
		vertical-align: middle;
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		border: 1px solid transparent;
		padding: 0.375rem 0.75rem;
		font-size: 1.4rem;
		line-height: 1.5;
		border-radius: 0.25rem;
		transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
	}
	.win_btn:hover{
		color: #fff !important;
		background-color: #218838 !important;
		border-color: #1e7e34 !important;
	}
	.game_result {
		font-size: 18px;
	}
	.game_result.andar{
		color: #6298e8;
	}
	.game_result.bahar{
		color: #da393f;;
	}
	.game_result.tie{
		color: #ffa33b;;
	}
	button[disabled] {
		background-color: grey !important;
		color: darkgrey !important;
		pointer-events: none;
	}
	a.paginate_button{
		text-decoration: none;
	}
</style>
</head>
<body class="hold-transition skin-red sidebar-mini">
	<div class="wrapper">
		<?php include ("include/connection.php");?>
		<?php include ("include/header.inc.php");?>
		<!-- Left side column. contains the logo and sidebar -->
		<?php include ("include/navigation.inc.php");?>
		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper" style="min-height: 517px;">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>Manage Andar Bahar Winning Result</h1>
				<ol class="breadcrumb">
					<li>
						<a href="desktop.php"><i class="fa fa-dashboard"></i> Home</a>
					</li>
					<li class="active">Manage Andar Bahar Winning Result</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">
						<div class="box">
							<div class="box-header box-header2">
								<div id="overlay" style="display: none;"></div>
								<div class="col-xs-2">
									<div class="gameidtimer">
										<h4>
											Count Down:<br />
											<spam id="demo" class="timer_block">wait..</spam>
										</h4>
									</div>
								</div>
								<div class="col-xs-3 text-center">
									<h4>
										Active Period Id:<br />
										<spam id="activeperiodid" class="text-success">wait..</spam>
									</h4>
									<input type="hidden" name="periodid" id="periodid" value="202402021841" />
								</div>
								<div class="col-xs-4 text-center">
									<h5 class="text-maroon">
										<label>
											<p style="margin-bottom: 4px">Game Result</p>
											<spam id="game_result" class="game_result">-</spam>
										</label>
										&nbsp;&nbsp;&nbsp;&nbsp;

										<!-- <input type="hidden" name="tabtype" id="tabtype" value="" /> -->
									</h5>
								</div>
								<div class="col-xs-3">
									<div class="main-input pull-right">
										<label>Do you want manual result ?</label>
										<div class="mt-0">
											<div class="btn-group btn-group-toggle padd-l-15 result_type_conformation" data-toggle="buttons">
												<label class="btn btn-secondary switchbuttonbox yes_btn">
													<input
													class="switchbutton"
													type="radio"
													name="switch"
													id="switchyes"
													value="yes"
													checked=""
													field_signature="15936774"
													form_signature="15382312737309733803"
													alternative_form_signature="9593700199178867512"
													visibility_annotation="true"
													/>
													Yes
												</label>
												<label class="btn btn-secondary switchbuttonbox no_btn active">
													<input
													type="radio"
													class="switchbutton"
													name="switch"
													id="switchno"
													value="no"
													field_signature="15936774"
													form_signature="15382312737309733803"
													alternative_form_signature="9593700199178867512"
													visibility_annotation="true"
													/>
													No
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<form id="formID" name="formID" method="post" action="#" enctype="multipart/form-data">
									<!--<div class="table-responsive"> -->
									<div id="example1_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
										<div class="row">
											<div class="col-sm-6"></div>
											<div class="col-sm-6"></div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<table id="manage_result" class="table table-bordered table-striped dataTable no-footer" role="grid" aria-describedby="example1_info">
													<thead>
														<tr>
															<?php foreach($column as $key => $val){ ?>
																<th><b>Search <?php echo $val; ?></b></th>
															<?php } ?>
														</tr>
													</thead>
													<tbody id="betdetail">

													</tbody>
												</table>
											</div>
										</div>

										<!--</div>-->
										<div class="box-header box-header2" style="margin-bottom: 10px;">&nbsp;</div>
										<div class="row">
											<div class="col-sm-10"></div>
											<div class="col-sm-2">&nbsp;</div>
										</div>
									</form>
								</div>
								<!-- /.box-body -->
							</div>
							<!-- /.box -->
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</section>
				<!-- /.content -->
			</div>
			<?php include("include/footer.inc.php");?>
			<?php
			$column = json_encode($column);
			?>
		</div>
		<script>
		$(document).ready(function () {

			var table = '';
			var start_date = "";
			var end_date = "";
			var timerInterval = '';
			var elapsedTime = 0;
			var currentGameId = '';
			var column_details =  <?php echo $column;?>;
			var swal_message = "Are you sure want to declar Andar as winner?";
			$(document).on("click",".win_btn",function(){
				var bet_type = $(this).attr("data-type");
				swal_message = "Are you sure want to declar "+bet_type+" as winner?";
				swal({
					title: swal_message,
					icon: 'success',
					buttons: {
						success: "Yes",
						fail: "No",

					},
				}).then((value) => {
					if(value == "success"){
						$(".game_result").html(bet_type).addClass(bet_type.toLowerCase())
						$('.win_btn').prop("disabled", true);
						if(bet_type != "" && currentGameId > 0){
							setWiner(currentGameId,bet_type);
						}
					}

				});
			})
			$(document).on("click",".switchbuttonbox",function(){
				var select_val = $(this).find(".switchbutton").val();

				if(select_val == "yes"){
					$('.win_btn').removeAttr('disabled');
				}else{
					$('.win_btn').prop("disabled", true);
				}

			})
			// makeTable('');
			generateGameID();

			function makeTable(data) {
				var data = data;
				table = new DataTable("#manage_result", {
					orderCellsTop: true,
					fixedHeader: true,
					lengthMenu: [[10, 50, 25], [10, 50, 25]],
					bLengthChange: false, /* enable for chagening length dropdown*/
					columns: column_details,
					processing: true,
					serverSide: true,
					sordering: true,
					searching: false,
					ordering: false,
					bSort: true,
					orderMulti: false,
					pagingType: "full_numbers",
					scrollCollapse: true,
					scrollX: false,
					scrollY: true,
					paging: true,
					fixedHeader: false,
					info: true,
					autoWidth: true,
					ajax: {
						data: {'search':data},
						url: "../admin/manage_andar_bahar_result_data.php",
						type: "POST",
					},

					scroller: {
						loadingIndicator: true,
					},

					language: {
						loadingRecords: "&nbsp;",
						processing: '<div class="spinner"></div>',
						emptyTable: "No data found.!",
						searchPanes: {
							emptyPanes: 'There are no panes to display. :/'
						},
						paginate: {
							first: "First",
							last: "Last",
							next: "Next",
							previous: "Previous",
						},
					},
				});
			}
			function statr_count_down(elapsedTime){

				timerInterval = setInterval(() => {
					if(elapsedTime == 0){
						clearInterval(timerInterval);
						// $("#overlay").hide();
						$(".timer_block").html("00:00");
						// $("#activeperiodid").html("Wait");
						setTimeout(function(){
							// generateGameID();
							window.location.reload()
						},20000); // wating timer for declair result
					}else{
						elapsedTime -= 1;
						if(elapsedTime < 15){ // disable change result block timer
							$("#overlay").show();
							$('.win_btn').prop("disabled", true);
						}
						formattedTimer(elapsedTime)
					}
				}, 1000);
			}
			function formattedTimer(elapsedTimeVal){
				if (elapsedTimeVal < 0) {
					elapsedTimeVal = 0;
				}
				const minutes = Math.floor(elapsedTimeVal / 60);
				const seconds = elapsedTimeVal % 60;
				formattedTime = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
				$(".timer_block").html(formattedTime)

			}

			function generateGameID() {
				var currentDate = new Date();
				var year = currentDate.getFullYear();
				var month = String(currentDate.getMonth() + 1).padStart(2, "0"); // Adding leading zero if needed
				var day = String(currentDate.getDate()).padStart(2, "0"); // Adding leading zero if needed

				// Constructing the game ID in yyyymmdd format
				var gameId = year + month + day;


				var newGameId = "";
				$.ajax({
					type: "POST",
					url: "../andar_bahar_backend.php?get", // Replace with your PHP script to fetch record count
					success: function (response) {
						response  = JSON.parse(response)
						var flag = 0;
						if(parseInt(response.pending_id) > 0){
							game_id = newGameId= response.pending_id;
							$("#activeperiodid").html(newGameId);
							$(".switchbuttonbox").removeClass('active')
							if(response.result != "" && response.result != null && response.result !=  undefined){
								$(".game_result").html(response.result).addClass((response.result).toLowerCase())
								$(".switchbuttonbox.yes_btn").addClass('active');
								$("#overlay").show();
							}else{
								$("#overlay").hide();
								$(".switchbuttonbox.no_btn").addClass('active')
							}

							currentGameId = newGameId;
							var params = {period_id:currentGameId}
							// table.destroy();
							makeTable(params)

							// You can use newGameId as needed here wait a sec
							//    const d = new Date();
							// let elapsedTime = 60 - d.getSeconds();
							//  				elapsedTime = elapsedTime - 15;
							// console.log(elapsedTime)
							let elapsedTime = response.timer;
							if(elapsedTime == 0){
								$("#overlay").show();
								$('.win_btn').prop("disabled", true);
							}
							statr_count_down(elapsedTime)
						}else{
							// table.destroy();
							makeTable('');
						}

						// if (totalSeconds <= 10) {
						//   $(".join").addClass("disabled");
						// } else {
						//   $(".join").removeClass("disabled");
						// }

					},
					error: function (xhr, status, error) {
						console.error("Error fetching record count:", error);
					},
				});
				return newGameId;
			}
			function setWiner(currentGameId,winner) {
				var params = {
					game_id: currentGameId,
					winner_val:winner
				};
				$.ajax({
					type: "POST",
					url: "../andar_bahar_backend.php",
					data: params,
					success: function (response) {
						$("#overlay").show();
						// response = JSON.parse(response);
						// winner = response.winer;
						// startGame(winner);
					},
					error: function (error) {
						console.error("Error:", error);
					},
				});
			}



		});



		</script>


	</body>
	</html>
