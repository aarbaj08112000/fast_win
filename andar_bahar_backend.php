<?php
session_start();
// $servername = "localhost"; // Replace with your MySQL server name
// $username = "fastwin";
// $password = "FastWin@123!";
// $dbname = "fastwin";
$servername = "localhost"; // Replace with your MySQL server name
$username = "root";
$password = "Root@12345678";
$dbname = "fastwin";


/* bet type wise point */
$bet_point_arr["Andar"] = 2;
$bet_point_arr["Bahar"] = 2;
$bet_point_arr["Tie"] = 2;
$deck = [
  'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '10S', 'JS', 'QS', 'KS',
  'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '10D', 'JD', 'QD', 'KD',
  'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '10C', 'JC', 'QC', 'KC',
  'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '10H', 'JH', 'QH', 'KH'
];



if(isset($_POST['action'])){
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $gameid = $_POST['gameid'];
  $sql = "SELECT COUNT(*) as count FROM tbl_gameid WHERE gameid='".$_POST['gameid']."'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if($row['count'] == 0){

      if (isset($_POST['action']) && $_POST['action'] === 'addRecord') {
        // Prepare and execute SQL query to insert a new record
        $gameid = $_POST['gameid'];

        $sql = "INSERT INTO tbl_gameid (gameid) VALUES ('$gameid')";
        if ($conn->query($sql) === TRUE) {

          $random_num = rand(0,52);
          $game_card = $deck[$random_num];
          // print_r($game_card);
          // $lastInsertedId = $conn->insert_id;
          $sql = "INSERT INTO `tbl_ab_result` (`game_id`, `result`,`game_card`) VALUES ($gameid, '','$game_card');
          ";

          $sql = mysqli_query($conn, $sql) ;
          $game_data = json_encode(["PeriodId" => $gameid, "CurrentCard" => $game_card, "Timer" => "45"]);

          // File path
          $filePath = "data_json.json";

          // Store data in the file
          file_put_contents($filePath, $game_data);
          $data['message'] = "New record added successfully!";
          $data['game_card'] = $game_card;

        } else {
          $data['message'] ="Error: " . $sql . "<br>" . $conn->error;
        }


      }
    }else{
      $data['message'] = "record aleady successfully!";
    }


  } else {
    // echo "0";
  }


  // Check if the action is to add a record

  echo json_encode($data);
  $conn->close();
  exit();
}

if(isset($_GET['get'])){
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Fetch count of records from tbl_gameid table
  $sql = "SELECT COUNT(*) AS record_count FROM tbl_gameid";
  $result = $conn->query($sql);

  $recordCount = [];
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $recordCount['count'] = $row["record_count"];

  } else {
    // echo "0";
  }
  $sql = "SELECT  tar.game_id as gameid,tar.status as status,tar.result as result,tar.result_type as result_type FROM tbl_ab_result as tar ORDER BY id DESC LIMIT 1";

  $result = $conn->query($sql);
  $recordCount['pending_id'] = '';
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // print_r($row);
    if($row['status'] == "Active"){

      $filePath = "data_json.json";
      // Read data from the file (optional)
      $readData = file_get_contents($filePath);
      $readData = (array) json_decode($readData);
      // print_r($readData);
      // exit();
      $recordCount['winer'] = "no_any_result";
      $recordCount['enterval_value'] = 5;
      if(array_key_exists("winner", $readData)){
        $recordCount['winer'] = $readData['winner'];
        $recordCount['enterval_value'] = $readData['enterval_value'];
        $recordCount['lastMove'] = $readData['lastMove'];
      }
      $recordCount['pending_id']  = $row["gameid"];
      $recordCount['result']  = $row["result"];
      $recordCount['result_type']  = $row["result_type"];
      $recordCount['game_card']  = $readData["CurrentCard"];
      $recordCount['timer']  = $readData["Timer"];
    }

  } else {
    // echo "0";
  }
  echo json_encode($recordCount);
  $conn->close();
  exit();
}


if(isset($_POST['frontuserid'])){
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $walletAmount = mysqli_query($conn, "SELECT amount FROM tbl_wallet WHERE userid='".$_POST['frontuserid']."'");
  if($walletAmount){
    $walletAmount = mysqli_fetch_array($walletAmount);
    echo json_encode(array("msg"=>"Amount fetch successfully!","balance"=>$walletAmount['amount']));
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }

  $conn->close();
  exit();
}

if(isset($_POST['updateBal'])){
  unset($_POST['updateBal']);
  // print_r($_POST);
  $user_id = $_POST['userid'];
  $period_id = $_POST['period'];
  $betting_type = $_POST['select'];
  $amount = $_POST['amount'];
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }


  $checking_balance =  mysqli_query($conn,"SELECT * FROM tbl_wallet WHERE userid = '$user_id'");
  $checking_balance_data = array();

  while ($row = mysqli_fetch_array($checking_balance, MYSQLI_ASSOC)) {
    $checking_balance_data[] = $row;
  }

  if($checking_balance_data[0]['amount'] > 0){
    $sql = "INSERT INTO tbl_ab_orders (user_id, period_id, betting_type, amount, created_by, created_on) VALUES ('$user_id','$period_id', '$betting_type','$amount','$user_id',NOW())";

    if ($conn->query($sql) === TRUE) {
      $newBalanceSql = mysqli_query($conn,"UPDATE tbl_wallet SET amount = '".$_POST['newBalance']."' WHERE userid='".$_POST['userid']."'");
      if($newBalanceSql){
        echo json_encode(array("msg"=>"updatedBalance","balance"=>$_POST['newBalance']));
      }
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }else{
    echo json_encode(array("msg"=>"Low Balance"));
  }

  exit();
}

if(isset($_POST['period_id'])){
  $user_id = $_SESSION['frontuserid'];
  $period_id = $_POST['period_id'];
  $conn = new mysqli($servername, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  if(isset($user_id) && isset($period_id)){
    $sql = mysqli_query($conn, "SELECT * FROM tbl_ab_orders WHERE period_id = $period_id AND user_id = $user_id");
  }
  //  else if(isset($user_id)){
  //   $sql = mysqli_query($conn, "SELECT * FROM tbl_ab_orders WHERE user_id = $user_id");
  // }
  else if(isset($period_id)){
    $sql = mysqli_query($conn, "SELECT * FROM tbl_ab_orders WHERE period_id = $period_id");
  }

  if ($sql) {
    $data = array();
    while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
      $data[] = $row;
    }
    echo json_encode(array("msg"=>"Order Records Featch successfully.","data"=>$data));
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  exit();
}

/* get winner  */

if(isset($_POST['gameId'])){
  $gameId = $_POST['gameId'];
  $lastMove = $_POST['lastMove'];
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  $sql = mysqli_query($conn, "SELECT tr.result as result FROM tbl_ab_result as tr WHERE tr.game_id= $gameId");
  $winner_val = "";
  if ($sql) {
    $result_arr = [];
    while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
      $result_arr[] = $row;
    }
    if(count($result_arr) > 0){
      $winner_val = $result_arr[0]['result'];
    }

  }



  if($winner_val == ""){
    $sql = mysqli_query($conn, "SELECT tao.betting_type,SUM(tao.amount) as amount FROM tbl_ab_orders as tao WHERE tao.period_id= $gameId GROUP BY tao.betting_type") ;
    if ($sql) {
      $data = array();
      while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
        $data[] = $row;
      }
      $betting_type_arr = ['Andar','Bahar','Tie'];
      $betting_type = array_column($data, 'betting_type');
      $betting_type_wise_data = array_column($data,"amount","betting_type");
      foreach ($betting_type_wise_data as $key => $value) {
        $betting_type_wise_data[$key] = $value * $bet_point_arr[$key];
      }

      $result_arr = [];
      if(count($data) > 0){
        if(in_array("Andar",$betting_type) && in_array("Bahar",$betting_type) && in_array("Tie",$betting_type)){
          if(($betting_type_wise_data['Tie'] < $betting_type_wise_data['Andar'] && $betting_type_wise_data['Tie'] < $betting_type_wise_data['Bahar'])){
            $result_arr['winer'] = "Tie";
          }else if($betting_type_wise_data['Andar'] < $betting_type_wise_data['Tie'] && $betting_type_wise_data['Andar'] < $betting_type_wise_data['Bahar']){
            $result_arr['winer'] = "Andar";
          }else if($betting_type_wise_data['Bahar'] < $betting_type_wise_data['Tie'] && $betting_type_wise_data['Bahar'] < $betting_type_wise_data['Andar']){
            $result_arr['winer'] = "Bahar";
          }else if($betting_type_wise_data['Andar'] == $betting_type_wise_data['Bahar']){
            $winner_arr = ['Andar','Bahar'];
            $result_arr['winer'] = $winner_arr[array_rand($winner_arr)];
          }else if( $betting_type_wise_data['Andar'] == $betting_type_wise_data['Tie'] ){
            $winner_arr = ['Andar','Tie'];
            $result_arr['winer'] = $winner_arr[array_rand($winner_arr)];
          }else if($betting_type_wise_data['Bahar'] == $betting_type_wise_data['Tie']){
            $winner_arr = ['Bahar','Tie'];
            $result_arr['winer'] = $winner_arr[array_rand($winner_arr)];
          }

        }else{
          $result=array_values(array_diff($betting_type_arr,$betting_type));
          $winner_index =  rand(0,count($result)-1);
          $result_arr['winer'] = $result[$winner_index];
        }
      }else{
        $result_arr['winer'] = "Nothing";
      }


      $result_arr['user_result'] = "nothing";
      $result_arr['user_amount'] = "";
      $result_arr['user_wining_amount'] = "";
      $user_id = $_SESSION['frontuserid'];
      if($result_arr['winer'] != "Nothing"){
        $sql = mysqli_query($conn, "SELECT tao.betting_type,tao.amount as amount FROM tbl_ab_orders as tao WHERE tao.period_id= $gameId AND tao.user_id= $user_id") ;
        $bet_data = [];
        while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
          $bet_data[] = $row;
        }

        $query = mysqli_query($conn, "SELECT tao.betting_type,tao.amount as amount FROM tbl_ab_orders as tao WHERE tao.period_id= $gameId") ;
        $total_gaming_amount = [];
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
          $total_gaming_amount[] = $row;
        }


        $game_amount = 0;

        foreach ($total_gaming_amount as $item) {
          if (isset($item['amount'])) {
            $game_amount += $item['amount'];
          }
        }
        // print_r($game_amount);
        // exit;
        $result_arr['game_amount'] = $game_amount;
        if(count($bet_data) > 0){
          $beting_type_wise_amount = array_column($bet_data,"amount","betting_type");
          $beting_types = array_column($bet_data,"betting_type");
          // print_r(array_keys($beting_type_wise_amount));
          // exit;
          if(in_array($result_arr['winer'],$beting_types)){
            $result_arr['user_result'] = "winner";
            $result_arr['user_amount'] = $beting_type_wise_amount[$result_arr['winer']];
            $result_arr['user_wining_amount'] =  $beting_type_wise_amount[$result_arr['winer']]*$bet_point_arr[$result_arr['winer']];
          }else{
            $selected_value =array_keys($beting_type_wise_amount);
            $result_arr['user_result'] = "loss";
            $result_arr['user_amount'] =  array_sum($beting_type_wise_amount);
            $result_arr['selected_value'] =  $selected_value[0];
            $result_arr['user_wining_amount'] =  0;
          }
        }
      }

      // $result_arr['winer'] = "Tie";

    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }else{
    $result_arr['winer'] = $winner_val;
  }

  $result_arr['enterval_value'] = 5;
  $filePath = "data_json.json";
  $readData = file_get_contents($filePath);
  $readData = (array) json_decode($readData);
  if($readData['PeriodId'] == $gameId ){
    if(array_key_exists("winner", $readData)){
      $result_arr['winer'] = $readData['winner'] != "" ? $readData['winner'] : "Nothing";
      $result_arr['enterval_value'] = $readData['enterval_value'];
      $result_arr['lastMove'] = $readData['lastMove'];
    }else{
      $result_arr['lastMove'] = $lastMove;
      $readData['lastMove'] = $lastMove;
      $readData['winner'] = $result_arr['winer'] != "" ? $result_arr['winer'] : "Nothing";
      $readData['enterval_value'] = 5;
      $filePath = "data_json.json";
      $data = json_encode($readData);
      // Store data in the file
      file_put_contents($filePath, $data);
    }

  }



  echo json_encode($result_arr);
  $conn->close();


  if($winner_val == ""){
    update_winner($gameId,$result_arr['winer'],"Automatic",$servername,$username,$password,$dbname,$bet_point_arr);
  }
  exit();
}

/* update winner manually */

if(isset($_POST['winner_val'])){
  $gameId = $_POST['game_id'];
  $winner = $_POST['winner_val'];

  update_winner($gameId,$winner,"Manual",$servername,$username,$password,$dbname,$bet_point_arr);
}


/* update winner records */
function update_winner($game_id,$winner,$result_type,$servername,$username,$password,$dbname,$bet_point_arr ){
  if($game_id > 0 && $winner != ""){
    $gameId = $game_id;
    $winner = $winner;
    $ander_win = $bet_point_arr["Andar"];
    $baher_win = $bet_point_arr["Bahar"];
    $tie_win = $bet_point_arr["Tie"];
    $result_arr = [];

    $winig_price_index = ($winner == "Andar") ? $ander_win : ($winner == "Baher" ? $baher_win : ($winner == "Tie" ? $tie_win : 0));

    // $servername = "localhost"; // Replace with your MySQL server name
    // $username = "root";
    // $password = "root";
    // $dbname = "fastwin";
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
      die("Connection failedqweq: " . $conn->connect_error);
    }
    $sql = mysqli_query($conn, "SELECT tao.* FROM tbl_ab_orders as tao WHERE tao.period_id= $gameId ") ;

    if ($sql) {
      $data = array();
      while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
        $data[] = $row;
      }

      if(count($data) > 0){
        foreach($data as $key => $val){
          if($val['betting_type'] == $winner){
            $val['points'] = $val['amount']*$winig_price_index;
          }else{
            $val['points'] = 0;
          }
          $val['result'] = $winner;
          $sql = "UPDATE tbl_ab_orders SET ";
          foreach($val as $key_val=>$value) {
            if(is_numeric($value))
            $sql .= $key_val . " = " . $value . ", ";
            else
            $sql .= $key_val . " = " . "'" . $value . "'" . ", ";
          }
          $sql = trim($sql, ' '); // first trim last space
          $sql = trim($sql, ',');
          $sql .= " WHERE id = ".$val['id'];
          $sql = mysqli_query($conn, $sql) ;

        }
      }

      $sql = "UPDATE `tbl_ab_result` SET result = '".$winner."',result_type = '".$result_type."' WHERE game_id='".$gameId."'";

      $sql = mysqli_query($conn, $sql) ;

      // echo json_encode($result_arr);
    } else {
      // echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
    exit();
  }
}

/* update status */
if(isset($_POST['status'])){
  $gameId = $_POST['game_id'];
  $winner = $_POST['winner'];
  if($gameId > 0 && $winner != ""){
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "UPDATE `tbl_ab_result` SET status = 'Inactive' WHERE game_id='".$gameId."'";
    $sql = mysqli_query($conn, $sql) ;
  }
}

if(isset($_POST['seconds'])){

  $gameId = $_POST['game_id_val'];
  $seconds = $_POST['seconds'];
  $filePath = "data_json.json";
  // Read data from the file (optional)
  $readData = file_get_contents($filePath);
  $readData = (array) json_decode($readData);
  if($readData['PeriodId'] == $gameId ){
    $readData['Timer'] = $seconds;
    $filePath = "data_json.json";
    $data = json_encode($readData);
    // Store data in the file
    file_put_contents($filePath, $data);
  }

}
if(isset($_POST['enterval_value'])){

  $gameId = $_POST['game_id'];
  $lastMove = $_POST['lastMove'];
  $enterval_value = $_POST['enterval_value'];
  $filePath = "data_json.json";
  // Read data from the file (optional)
  $readData = file_get_contents($filePath);
  $readData = (array) json_decode($readData);
  if($readData['PeriodId'] == $gameId ){
    $readData['enterval_value'] = $enterval_value;
    $readData['lastMove'] = $lastMove;
    $filePath = "data_json.json";
    $data = json_encode($readData);
    // Store data in the file
    file_put_contents($filePath, $data);
  }

}
?>
