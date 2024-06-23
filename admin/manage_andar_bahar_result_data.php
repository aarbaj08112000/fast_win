<?php
ob_start();
session_start();
require("include/connection.php");
$post_data = $_POST;
$period_id = "";
if(gettype($post_data['search']) == "array"){
	$period_id = $post_data['search']['period_id'] ;
}
// $condition_arr['order_by'] = $order_by;
$start = $post_data['start'];
$length = $post_data['length'];
$betting_type_arr = ["Andar","Bahar","Tie"];
$total_records = 0;
if($period_id > 0){
	/* query for total records without pagination */

	$sql = "SELECT tao.betting_type as result,SUM(tao.amount) AS amount,SUM(tao.points) AS points,COUNT(tao.id) as number_count,COUNT(tao.user_id) as user_count
	FROM tbl_ab_orders AS tao WHERE tao.period_id = $period_id GROUP BY tao.betting_type ";
	$result = $con->query($sql);
	$total_records = 0;
	if ($result) {
	    while ($row = $result->fetch_assoc()) {
	    		$total_records++;
	    }
	}

	/* query for total records with pagination */

	$sql = "SELECT tao.betting_type as result,SUM(tao.amount) AS amount,SUM(tao.points) AS points,COUNT(tao.id) as number_count,COUNT(tao.user_id) as user_count FROM tbl_ab_orders AS tao WHERE tao.period_id = $period_id GROUP BY tao.betting_type";
	$result = $con->query($sql);
	if ($result) {
	    // Fetch data
	    $data = array();
	    while ($row = $result->fetch_assoc()) {
	        	$data[] = $row;	        
	    }
	}


	$sql = "SELECT tao.user_id AS user_id, tao.betting_type AS result FROM tbl_ab_orders AS tao WHERE tao.period_id = $period_id GROUP BY tao.user_id,tao.betting_type";
	$result = $con->query($sql);
	 $user_count_data = [];
	 $user_count_data["Andar"] = 0;
	 $user_count_data["Bahar"] = 0;
	 $user_count_data["Tie"] = 0;
	if ($result) {
	    // Fetch data
	    while ($row = $result->fetch_assoc()) {
	        	$user_count_data[$row['result']] += 1;	        
	    }
	}
	$betting_type_wise_data = array_column($data, "result");
	$final_status_arr = array_diff($betting_type_arr, $betting_type_wise_data);

	if(count($final_status_arr)> 0){
		$last_key = count($data);
		foreach ($final_status_arr as $key => $value) {
			$data[$last_key]['result'] = $value;
			$data[$last_key]['amount'] = 0;
			$data[$last_key]['points'] = 0;
			$data[$last_key]['number_count'] = 0;
			$data[$last_key]['user_count'] = 0;
			$last_key++;

		}	
	} 

	if(count($data) > 0){
		foreach ($data as $key => $value) {
			$data[$key]['user_count'] = $user_count_data[$value['result']];
			$data[$key]['action'] = "<button type='button' class='win_btn' data-type='".$value['result']."' disabled>Winner</button>";
		}
	}
	
	$total_records = count($data);
	// print_r($data);
	asort($data);
	$data_arr = [];
	foreach ($data as $key => $value) {
		array_push($data_arr, $value);
	}
	
	$data = $data_arr;

}else{

	$data = [];
}

$data['data'] = $data;
$data["recordsTotal"] = $total_records;
$data["recordsFiltered"] = $total_records;

echo json_encode($data);
exit();

?>