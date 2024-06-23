<?php
ob_start();
session_start();
require("include/connection.php");
$post_data = $_POST;
$result_point_arr['Andar'] = 2;
$result_point_arr['Bahar'] = 2;
$result_point_arr['Tie'] = 2;
// $column_index = array_column($post_data['columns'], 'data');
//     // pr($column_index);
// $order_by = "";
// foreach ($post_data['order'] as $key => $val) {
// 	if ($key == 0) {
// 		$order_by .= $column_index[$val['column']] . " " . $val['dir'];
// 	} else {
// 	 	$order_by .= "," . $column_index[$val['column']] . " " . $val['dir'];
// 	}
// }
// $condition_arr['order_by'] = $order_by;
$start = $post_data['start'];
$length = $post_data['length'];
$where_con = "";


if(is_array($post_data['search'])){

	if(count($post_data['search']) > 0){

		$start_date = date("Y-m-d H:i:s", strtotime($post_data['search']['start_date']));
		$end_date =  date("Y-m-d H:i:s", strtotime($post_data['search']['end_date']."24:59:59"));
		$where_con = "WHERE tg.createdate >='".$start_date."' AND  tg.createdate <='".$end_date."' AND tr.result != ''";

	}else{
		$where_con = "WHERE tr.result != ''";
	}
}else{
	$where_con = "WHERE tr.result != ''";
}

/* get total count for pagination */
$sql = "SELECT tg.createdate as date,tg.gameid as periodId,tr.result as result FROM tbl_ab_result as tr LEFT JOIN tbl_gameid as tg ON tg.gameid = tr.game_id ".$where_con;

$result = $con->query($sql);
$total_records = 0;
if ($result) {
	while ($row = $result->fetch_assoc()) {
		$total_records++;
	}
}

/* get record with pagination */
$sql = "SELECT tg.createdate as date,tg.gameid as periodId,tr.result as result FROM tbl_ab_result as tr LEFT JOIN tbl_gameid as tg ON tg.gameid = tr.game_id $where_con ORDER BY date desc LIMIT $length OFFSET $start";

$result = $con->query($sql);


// Check if the query was successful
$period_ids = [];
$period_id_wise_data = [];
$data = array();
if ($result) {
	// Fetch data

	while ($row = $result->fetch_assoc()) {

		if($row['periodId'] > 0){
			$data[] = $row;
			$period_ids[] = $row['periodId'];
			$period_id_wise_data[$row['periodId']]['points'] = 0;
			$period_id_wise_data[$row['periodId']]['Nothing'] = 0;
		}

	}
}
if(count($data) > 0){
	$period_ids = implode(",", $period_ids);
	$sql = "SELECT tao.period_id, tao.betting_type, SUM(tao.amount) AS amount, SUM(tao.points) AS points,tao.result as game_result FROM tbl_ab_orders AS tao WHERE tao.period_id IN (".$period_ids.") GROUP BY tao.betting_type,tao.period_id";

	$result = $con->query($sql);


	// Check if the query was successful
	$result_wise_data = [];
	if ($result) {
		// Fetch data
		while ($row = $result->fetch_assoc()) {
			$result_wise_data[] = $row;

		}
	}
	$game_result_arr = [];
	if(count($result_wise_data) > 0){
		foreach ($result_wise_data as $key => $value) {
			$period_id_wise_data[$value['period_id']][$value['betting_type']] = $value['amount'];
			$period_id_wise_data[$value['period_id']]['points'] += $value['points'];

		}
	}

	foreach ($data as $key => $value) {
		$andar_bet = array_key_exists("Andar",$period_id_wise_data[$value['periodId']])  > 0 ? $period_id_wise_data[$value['periodId']]['Andar']: 0;
		$bahar_bet = array_key_exists("Bahar",$period_id_wise_data[$value['periodId']]) > 0 ? $period_id_wise_data[$value['periodId']]['Bahar']: 0;
		$tie_bet = array_key_exists("Tie",$period_id_wise_data[$value['periodId']]) > 0 ? $period_id_wise_data[$value['periodId']]['Tie'] : 0;
		$game_result = $value['result'];

		if($game_result == "Andar"){
			$winning_amount = $andar_bet * $result_point_arr['Andar'];
		}else if($game_result == "Bahar"){
			$winning_amount = $bahar_bet * $result_point_arr['Bahar'];
		}else if($game_result == "Tie"){
			$winning_amount = $tie_bet * $result_point_arr['Tie'];
		}else{
			$winning_amount = 0;
		}
		$date = date_create($value['date']);
		$total_trade = $andar_bet+$bahar_bet+$tie_bet;
		$data[$key]['date'] = date_format($date,"Y/m/d");
		$data[$key]['andar'] = number_format($andar_bet);
		$data[$key]['bahar'] = number_format($bahar_bet);
		$data[$key]['tie'] = number_format($tie_bet);
		$data[$key]['trade_amount'] = number_format($total_trade);
		$data[$key]['winning_amount'] = number_format($winning_amount);
		$data[$key]['net_profit'] = $winning_amount < $total_trade ? number_format($total_trade - $winning_amount) : number_format($total_trade - $winning_amount);
		$data[$key]['loss'] = '';
		$loss  = 0;
		if($data[$key]['winning_amount'] > 0 && $data[$key]['trade_amount'] > 0 && $winning_amount < $total_trade){
			$percentage_val = number_format((1- ($winning_amount / $total_trade)) * 100, 2, '.', '');
		}else if ($total_trade == 0 || $winning_amount > $total_trade){
			$percentage_val = '0';
			if($winning_amount > 0){
				$loss  = number_format((1 - intval($total_trade) / $winning_amount) * 100, 2, '.', '');
			}else{
				$loss  = 0;
			}

		}else{
			$percentage_val = '100';
		}
		$data[$key]['loss'] = $loss."%";

		$data[$key]['profit'] = $percentage_val."%";
	}
}
// print_r($data);
// exit();
// Close connection

$data['data'] = $data;
$data["recordsTotal"] = $total_records;
$data["recordsFiltered"] = $total_records;
echo json_encode($data);
exit();
$conn->close();

?>
