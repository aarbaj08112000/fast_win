<?php
ob_start();
session_start();
if($_SESSION['userid']=="")
{
	header("location:index.php?msg1=notauthorized");
	exit();
}
	$column [] = ['data' => "periodId", 'title' => "PeriodId", "width" => "11%", 'className' => 'dt-left'];
	$column [] = ['data' => "date", 'title' => "Date", "width" => "8%", 'className' => 'dt-center '];
    $column [] = ['data' => "result", 'title' => "Result", "width" => "7%", 'className' => 'dt-center'];
    $column [] = ['data' => "andar", 'title' => "Andar", "width" => "7%", 'className' => 'dt-left'];
    $column [] = ['data' => "bahar", 'title' => "Bahar", "width" => "7%", 'className' => 'dt-left'];
    $column [] = ['data' => "tie", 'title' => "Tie", "width" => "7%", 'className' => 'dt-left'];
    $column [] = ['data' => "trade_amount", 'title' => "Trade Amount", "width" => "12%", 'className' => 'dt-left'];
    $column [] = ['data' => "winning_amount", 'title' => "Winning Amount", "width" => "14%", 'className' => 'dt-left'];
    $column [] = ['data' => "net_profit", 'title' => "Net Profit", "width" => "10%", 'className' => 'dt-left'];
    $column [] = ['data' => "profit", 'title' => "Profit %", "width" => "8%", 'className' => 'dt-left'];

    $column [] = ['data' => "loss", 'title' => "Loss %", "width" => "14%", 'className' => 'dt-left'];

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Adminsuit | History For Parity</title>
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
	<!-- datatable js and css -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
	<!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css"> -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">

	<!-- date picker js and css -->
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap/3/css/bootstrap.css" />
	<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="css/app.css" id="maincss">
	<style type="text/css">
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
		a.paginate_button.current {
		    background: #e5baec;
		}
		a.paginate_button{
			text-decoration: none;
		}
		#reportrange {
			/*position: absolute;
    		right: 300px;
    		top: 59px ;
			*/
			float: right !important;
		}
		/*.dataTables_length {
			position: relative;
    		top: 28px;
    		width: 25%;
		}*/
		.daterangepicker.opensright.show-calendar:after {
		    /*left: 10px;
		    left: 172px;
			*/
			left: 636px;
		}
		.daterangepicker.opensright.show-calendar:before {
		    /*left: 171px;*/
		    left: 635px;
		}
		.daterangepicker_start_input,.daterangepicker_end_input {
			display: none !important;
		}
		.daterangepicker.dropdown-menu.opensright.show-calendar {
			width: 37% !important;
			margin-right: 22px;
		}
		.daterangepicker.dropdown-menu.opensright{
			    margin-top: 5px;
		}
		div.dataTables_filter label{
			    margin-right: 26px !important;
		}
		div.dataTables_filter input {
		    margin-left: 0.5em;
		    display: inline-block;
		    width: 85%;
		}
		.dataTables_info{
			float: left;
		    width: 50% !important;
		    margin-top: 8px;
		}
		#example_paginate {
			float: left;
		    width: 50% !important;
		    margin-top: 19px;
		}
		#example_length {
			margin-top: 16px;
    		width: 50% !important;
		}
		.box-header.box-header2 {
		    display: none;
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
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<section class="content-header">
				<h1>Today's History For Andar Bahar</h1>
				<ol class="breadcrumb">
					<li><a href="desktop.php"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Today's History For Andar Bahar</li>
				</ol>
			</section>

			<!-- Main content -->
			<section class="content">
				<div class="row">
					<div class="col-xs-12">


						<div class="box">
							<div class="box-header box-header2">
								<div class="col-xs-6 text-right">
									<h3 class="box-title"><?php
									if(isset($_GET['msg'])=="updt")
									{ ?>
										<font size="+1" color="#FF0000">Update Successfully...</font>
									<?php  } ?></h3>
								</div>
								<div class="col-sm-6">
									<div class="pull-right">&nbsp;</div>
								</div>

							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<form id="formID" name="formID" method="post" action="#" enctype="multipart/form-data">
									<div id="reportrange"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
									    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
									    <span></span> <b class="caret"></b>
									</div>
									<!--<div class="table-responsive"> -->
									<table style="width: 100%;" id="example" class=" display nowrap table table-bordered table-striped dataTable no-footer">
									      <thead>
									        <tr>
									          <?php foreach($column as $key => $val){ ?>
									          <th><b>Search <?php echo $val; ?></b></th>
									          <?php } ?>
									        </tr>
									      </thead>
									      <tbody></tbody>
									    </table>									<!--</div>-->
									<div class="box-header box-header2" style="margin-bottom: 10px;">&nbsp; </div>
									<div class="row">
										<div class="col-sm-10"></div>
										<div class="col-sm-2">
											&nbsp;
										</div>
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
		<!-- /.content-wrapper -->

		<?php
		include("include/footer.inc.php");

		?></div>
		<?php
		$column = json_encode($column);
		?>
		<!-- ./wrapper -->

		<script>
		var table = '';
		var start_date = "";
		var end_date = "";
		var column_details =  <?php echo $column;?>
		// console.log(column_details)
		makeTable('')
		function makeTable(data) {
		    var data = data;
		    table = new DataTable("#example", {
		        orderCellsTop: true,
		        fixedHeader: true,
		        lengthMenu: [[10, 50, 25], [10, 50, 25]],
		        // "sDom":is_top_searching_enable,
		        columns: column_details,
		        processing: true,
		        serverSide: true,
		        sordering: true,
		        searching: false,
		        ordering: false,
		        bSort: true,
		        orderMulti: true,
		        pagingType: "full_numbers",
		        scrollCollapse: true,
		        scrollX: true,
		        scrollY: true,
		        paging: true,
		        fixedHeader: false,
		        info: true,
		        autoWidth: true,

		        // "dom": '<"top"f<"clear">>rt<"bottom"ilp<"clear">>',
		        ajax: {
		            data: {'search':data},
		            url: "./history_andar_bahar_listing_data.php",
		            type: "POST",
		        },

		        scroller: {
		            loadingIndicator: true,
		        },

		        language: {
		            loadingRecords: "&nbsp;",
		            processing: '<div class="spinner"></div>',
		            emptyTable: "No history data found.!",
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

		var start = moment().subtract(29, 'days');
	    var end = moment();

	    function cb(start, end) {
	        $('#reportrange span').html(start.format('D/M/Y') + ' - ' + end.format('D/M/Y'));
	        start_date = start.format('M/D/Y');
	        end_date = end.format('M/D/Y');
	        table.destroy();
	        var filter_data = {start_date:start_date,end_date:end_date}
	        makeTable(filter_data);

	    }

	    $('#reportrange').daterangepicker({
	        startDate: start,
	        endDate: end,
	        ranges: {
	           'Today': [moment(), moment()],
	           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
	           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
	           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
	           'This Month': [moment().startOf('month'), moment().endOf('month')],
	           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
	        }
	    }, cb);


	    cb(start, end);

	</script>

</body>
</html>
