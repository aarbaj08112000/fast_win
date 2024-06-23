<?php
ob_start();
session_start();
if($_SESSION['frontuserid']=="")
{
  header("location:login.php");exit();
}
$userid = $_SESSION['frontuserid'];
$default_show_records = 15;
$deck = array(
  'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', '10S', 'JS', 'QS', 'KS',
  'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', '10D', 'JD', 'QD', 'KD',
  'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', '10C', 'JC', 'QC', 'KC',
  'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', '10H', 'JH', 'QH', 'KH'
);

$randomCard = $deck[array_rand($deck)];
$suit = substr($randomCard, -1); // Get the last character (suit)
$number = substr($randomCard, 0, -1); // Get the card number
if ($suit === 'H') {
  $suitImage = 'heart.png';
} elseif ($suit === 'D') {
  $suitImage = 'diamond.png';
} elseif ($suit === 'C') {
  $suitImage = 'club.png';
} elseif ($suit === 'S') {
  $suitImage = 'spade.png';
}
include ("db_common_connection.php");
// $conn = mysqli_connect('localhost','root','','fastwin');
//
// if (!$conn) {
//   die("Connection failed: " . mysqli_connect_error());
//   echo "string";
// }
$period_ids = array();
$sql = mysqli_query($conn, "SELECT g.gameid,r.result,r.status FROM tbl_gameid as g LEFT JOIN tbl_ab_result as r ON r.game_id = g.gameid ORDER BY g.id DESC LIMIT ".$default_show_records);

if ($sql) {

  while ($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)) {
    $period_ids[] = $row;
  }
}
$uniqueArray = array();
// print_r($period_ids);
foreach ($period_ids as $item) {
  if (!in_array($item['gameid'], array_column($uniqueArray, 'gameid'))) {
    $uniqueArray[] = $item;
  }
}
$period_ids = $uniqueArray;


// get everyone orders
$user_id = $_SESSION['frontuserid'];
$myOrders = [];
if (isset($user_id)) {
  $query = mysqli_query($conn, "SELECT * FROM tbl_ab_orders WHERE user_id = $user_id ORDER BY created_on DESC LIMIT 10");

  if ($query) {

    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
      $myOrders[] = $row;
    }
  }
}

// echo "<pre>";
// print_r($myOrders);
// exit;

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Andar Bahar</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="andar_bahar.css">
</head>
<body>
  <div class="rule-modal">
    <span class="float-end closeRuleModal" data-userid="<?php echo $_SESSION['frontuserid']; ?>">X</span>
    <br>
    <h3 class="bg_rule mx-auto">Andar Bahar Rule</h3>
    <p>
      Andar Bahar is a guessing game, you can choose Andar, Bahar, or Tie.<br>
      50 seconds to order, after the order time. Show a hole card first. Then, in the order of Andar Bahar, the cards are dealt in turn, and the card with the same number of points as the hole card is dealt first, and which side wins.<br>
      If the hole card shows a 2. then Andar and Bahar, whoever shows the 2 first, wins. If no 2 is shown in 8 consecutive cards, it is a tie. If you spend ₹100 to trade, after deducting 2 rupees service fee, your contract amount is 98 rupees:<br>
      • Buy Andar: Andar will first show 2 and you will get (98*2) 196 rupees;<br>
      • Buy Bahar: Bahar will first show 2 and you will get (98*2) 196 rupees;<br>
      • Buy a Tie: If both Andar and Bahar do not show 2, you will get (98*2) 196 rupees.
    </p>
    <div class="col-sm-12 text-center mb-3">
      <button type="submit" name="button" class="btn btn-primary" id="rule_confirm">I Got it.</button>
    </div>
  </div>
  <div class="bidding-modal">
    <span class="float-end closeModal" data-userid="<?php echo $_SESSION['frontuserid']; ?>">X</span>
    <br>
    <h3 class="text-center" id="bidding-modal-title"></h3>
    <div class="input-group mb-3 mt-4">
      <input type="text" class="form-control" placeholder="₹ 0.00" disabled>
      <button class="btn btn-outline-primary" type="button" id="button-addon2">Recharge</button>
    </div>
    <hr>
    <h5 class="mb-3">Your wallet balance: <span id="balance"></span> </h5>
    <div class="amount-btn-box">
      <?php $amountArray = range(10, 100, 10);
      foreach ($amountArray as $amount) {
        ?>
        <button type="button" class="btn btn-outline-primary btn-sm me-2 mb-2 amount_btn" name="button" data-amount="<?php echo $amount; ?>"><?php echo $amount; ?></button>
        <?php
      }
      ?>
    </div>
    <div class="row">
      <p>Number</p>

      <div class="col-4">
        <button type="button" class="btn btn-outline-secondary btn-sm mb-2 amount_point_btn" name="button" data-amount="-5">-5</button>
        <button type="button" class="btn btn-outline-secondary btn-sm mb-2 amount_point_btn" name="button" data-amount="-1">-1</button>
      </div>
      <div class="col-4 multiple-count">
        <p class="text-danger text-center fs-20 total-point-box" data-total-point="1">1</p>
      </div>
      <div class="col-4 text-end">
        <button type="button" class="btn btn-outline-secondary btn-sm mb-2 amount_point_btn" name="button" data-amount="5">+5</button>
        <button type="button" class="btn btn-outline-secondary btn-sm mb-2 amount_point_btn" name="button" data-amount="1">+1</button>
      </div>
    </div>
    <!-- <hr> -->

  </div>
  <div id="payment" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header paymentheader" id="paymenttitle">
          <h4 class="modal-title" id="chn">JOIN</h4>
        </div>
        <form class="" action="" id="bidForm" method="post">
          <input type="hidden" name="period" class="periodField" value="">
          <input type="hidden" name="select" value="" class="selectField">
          <input type="hidden" name="userid" value="" class="userId">
          <input type="hidden" name="updateBal" value="" class="updateBal">
          <div class="modal-body mt-1" id="loadform">
            <div class="row">
              <div class="col-12">
                <p class="mb-3">Contract Money</p>
                <div class="btn-group btn-group-toggle mb-4" data-toggle="buttons">
                  <?php $amountArray = array(10, 100, 1000,10000);
                  foreach ($amountArray as $key=>$amount) {
                    ?>
                    <label class="btn btn-secondary amount-btn-box" >
                      <input class="contract amount_price" type="radio"  value="<?php echo $amount; ?>" checked />
                      <?php echo $amount; ?>
                    </label>
                    <?php
                  }
                  ?>

                </div>

                <input type="hidden" id="contractmoney" name="contractmoney" value="10" />

                <p class="mb-3">Contract Count</p>
                <div class="def-number-input number-input safari_only">
                  <button type="button" class="minus minus-total-point"></button>
                  <input class="quantity" min="1" name="total-point" id="total-point" value="1" type="number" />
                  <button type="button"  class="plus plus-total-point"></button>
                </div>
                <div class="mt-4 mb-1">Total contract money is
                  <span id="showamount" class="total_box_value">10</span>
                  <input type="hidden" class="form-control " name="amount" id="join_amount" placeholder="Enter amount of your choice" required>
                </div>
                <div class="custom-control custom-checkbox mt-2">
                  <input type="checkbox" checked="" class="custom-control-input" id="presalerule" name="presalerule">
                  <label class="custom-control-label text-muted" for="presalerule">I agree <a data-toggle="modal" href="#privacy" data-backdrop="static" data-keyboard="false">PRESALE RULE</a></label>
                </div>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <a type="button" class="pull-left btn btn-sm closebtn" data-dismiss="modal"><b>CANCEL</b></a>
            <button type="submit" class="pull-left btn btn-sm btn-white"><b>CONFIRM</b></button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div id="winnr_declear" class="modal fade " aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header paymentheader pt-0" id="andar">
          <img src="./crown1.png" class="crown_image">
          <img src="https://cdn-icons-png.flaticon.com/128/6081/6081766.png" class="star-img">
          <h4 class="modal-title" id="winner_result">WIN</h4>
        </div>
        <div class="modal-conatin-box "   >
          <div class="winner-box text-center p-3 pb-0">
            <div class="win-latter">
              <span class="sc">A</span>
            </div>

          </div>
        </div>
        <div class="modal-conatin-box p-3 pb-0"  >
          <div class="row">
            <div class="col-6">
              <span >Period</span>
            </div>
            <div class="col-6 ">
              <div class="text-right">
                <span class="game_id_box">202020586</span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <span>Price</span>
            </div>
            <div class="col-6 text-right">
              <div class="text-right">
                <span class="total_amount">1000</span>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-conatin-box p-3 pt-1"  >
          <div class="user-deratil-box p-3">
            <div class="row slected-bet-box pb-3">
              <div class="col-6">
                <span>Selected</span>
              </div>
              <div class="col-6 ">
                <div class="text-right selcted_winner_class" >
                  <span class="p-2 selcted_winner_value">Andar</span>
                </div>
              </div>
            </div>
            <div class="row pb-3">
              <div class="col-6">
                <span>Points</span>
              </div>
              <div class="col-6 text-right">
                <div class="text-right">
                  <span class="user_amount">10</span>
                </div>
              </div>
            </div>
            <div class="row amount-box">
              <div class="col-6">
                <span>Amount</span>
              </div>
              <div class="col-6 text-right">
                <div class="text-right price-box">
                  <span class="user_wining_amount">+₹10</span>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-conatin-box p-3 pt-1"  >
          <button class="w-100 btn btn-primary close-btn">Close</button>
        </div>

      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row main">
      <div class="col-md-6 col-lg-4">
        <div class="row rows">
          <div class="container">
            <div class="toast align-items-center text-light border-0" role="alert" aria-live="assertive" aria-atomic="true">
              <div class="d-flex">
                <div class="toast-body">
                  New Game Started.
                </div>
                <!-- <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button> -->
              </div>
            </div>
            <div class="row d-flex header">
              <div class="col-2 tfw-5 tf-24">
                <a href="home.php" class="text-white">
                  <i class="fa fa-angle-left"></i>
                </a>
              </div>
              <div class="col-8 tfw-5 tf-18 text-center">Andar Bahar</div>
              <div class="col-2 tfw-5 tf-18 text-end rule" id="rule" data-user="<?php echo $_SESSION['mobile']; ?>">Rule</div>
            </div>
            <div class="top-data">
              <div class="float-start w-50">
                <p> Period<br>
                  <span class="tf-24 gameid" id="currentGameId"></span>
                </p>
              </div>
              <div class="float-end w-50">
                <p class="text-end"> Count Down</p>
                <div id="counter" class="text-end">
                  <span class="digit" id="minutes-ten">0</span>
                  <span class="digit" id="minutes-one">0</span>
                  <span>:</span>
                  <span class="digit" id="seconds-ten">0</span>
                  <span class="digit" id="seconds-one">0</span>
                </div>
              </div>
              <div class="row mt-2 mb-5 ">
                <div class="col-12 d-flex justify-content-center ps-3">
                  <div id="updiv" class="updiv flip-in-hor-bottom mb-3">
                    <img src="image/<?php echo $randomCard ?>.png" height="150" data-cardtomatch = "<?php echo $randomCard; ?>" class="upImage" width="100" alt="">
                  </div>
                </div>
                <div class="col-4 d-flex justify-content-center">
                  <div id="andar" class="andar_bahar mt-4">Andar</div>
                </div>
                <div class="col-4 d-flex justify-content-center">
                  <div id="middle" class="middle-card">
                    <img class="card" id="cards_deck" src="image/cards.png" />
                    <img src="image/AS.png" class="toggleCard" alt="">
                  </div>
                </div>
                <div class="col-4 d-flex justify-content-center">
                  <div id="bahar" class="andar_bahar mt-4">Bahar</div>
                </div>
              </div>
              <div class="row mr-0 mt-4 pb-3 tf-18">
                <div class="col-4 pa-0 text-center">
                  <div class="join andar" data-id="Andar">
                    <div class="tflh-56 tfw-5">Andar</div>
                  </div>
                  <div class="tflh-10 tf-14 tfcdg pt-1">1:2</div>
                </div>
                <div class="col-4 pa-0 text-center">
                  <div class="join tie" data-id="Tie">
                    <div class="tflh-56 tfw-5">Tie</div>
                  </div>
                  <div class="tflh-10 tf-14 tfcdg pt-1">1:9</div>
                </div>
                <div class="col-4 pa-0 text-center">
                  <div class="join bahar" data-id="Bahar">
                    <div class="tflh-56 tfw-5">Bahar</div>
                  </div>
                  <div class="tflh-10 tf-14 tfcdg pt-1">1:2</div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 tb active text-center">
            <b>
              Record
            </b>
          </div>
          <div class="col-sm-12">
            <div class="row mt-2">
              <div class="col-8 col-sm-8">
                <p>Andar Bahar Record(s)</p>
              </div>
              <div class="col-4 col-sm-4 text-end">
                <a href="ab_result.php" class="text-secondary">more ></a>
              </div>
              <div class="col-12 col-sm-12">
                <div class="row record all_records ps-1 pe-3">
                  <?php for ($i= count($period_ids) - 1; $i >=0 ; $i--) { ?>
                    <div class="rcd" id="order_box_<?php echo $period_ids[$i]['gameid']; ?>">
                      <?php $result = $period_ids[$i]['result'] != "" && $period_ids[$i]['status'] != 'Active' ? $period_ids[$i]['result'][0] : "?";

                      $result_index = strtolower($result);
                      if($result == "?"){
                        $result_index = "q";
                      }

                      ?>
                      <div class="sc <?php echo $result_index; ?>">
                        <div class="result-box">
                          <?php echo $result ?>
                        </div>
                        <div class="text-dark">
                          <?php

                          echo substr($period_ids[$i]['gameid'],strlen($period_ids[$i]['gameid'])-3);

                          ?>
                        </div>
                      </div>
                    </div>
                  <?php } ?>
                </div>
              </div>

            </div>
          </div>
        </div>
        <div class="row order_tabs">
          <div class="col-6 col-sm-6 col6 blue_border shadow-lg" id="everyoneOrder">
            <button class="btn-white w-100 my-2" type="button" id="everyoneOrder-btn"><b>Everyone's Order</b></button>
          </div>
          <div class="col-6 col-sm-6 col7" id="myOrder">
            <button class="btn-white w-100 my-2 text-muted" type="button" id="myOrder-btn"><b>My Order</b></button>
          </div>
          <div class="col-12 details active shadow-lg" id="everyoneOrderDetails">
            <div class="row text-center">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">Period</th>
                    <th scope="col">User</th>
                    <th scope="col">Select</th>
                    <th scope="col">Point</th>
                  </tr>
                </thead>
                <tbody class="everyoneOrderList">
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-12 details" id="myOrderDetails">
            <div class="row text-center">
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">Period</th>
                    <th scope="col">Select</th>
                    <th scope="col">Point</th>
                    <th scope="col">Result</th>
                    <th scope="col">Amount</th>
                  </tr>
                </thead>
                <tbody class="myOrderlist">
                  <?php
                  if (count($myOrders) > 0) {
                    foreach ($myOrders as $key => $item) {
                      // print_r($item['id']);
                      // echo var_dump($everyoneOrder);
                      // echo var_dump($val);
                      ?>
                      <tr class="default_data">
                        <td><?php echo $item["period_id"]; ?></td>
                        <td>
                          <?php if ($item["betting_type"] === "Andar"): ?>
                            <div class="sc a"><div class="">A</div></div>
                          <?php elseif ($item["betting_type"] === "Bahar"): ?>
                            <div class="sc b"><div class="">B</div></div>
                          <?php else: ?>
                            <div class="sc t"><div class="">T</div></div>
                          <?php endif; ?>
                        </td>
                        <td>₹ <?php echo ($item["amount"] === "" ? 0 : $item["amount"]); ?></td>
                        <td>
                          <?php if ($item["result"] === "Andar"): ?>
                            <div class="sc a"><div class="">A</div></div>
                          <?php elseif ($item["result"] === "Bahar"): ?>
                            <div class="sc b"><div class="">B</div></div>
                          <?php elseif ($item["result"] === "Tie"): ?>
                            <div class="sc t"><div class="">T</div></div>
                          <?php else: ?>
                            <div class="sc q"><div class="">?</div></div>
                          <?php endif; ?>
                        </td>
                        <?php if ($item["result"] == $item["betting_type"]): ?>
                          <td class="text-success"> <?php
                          $winning_amount = $item["amount"]*2;
                          $amount = $winning_amount * 0.02;
                          echo "+₹".number_format($winning_amount - $amount, 2, '.', ',');
                          ?></td>
                        <?php else: ?>
                          <td class="text-danger"><?php $amount = $item["amount"] * 0.02;
                          echo "-₹".number_format($item["amount"] - $amount, 2, '.', ',');
                          ?></td>

                        <?php endif; ?>

                      </tr>

                    <?php  }
                  }
                  ?>

                </tbody>

              </table>
              <a href="ab_orders.php" class="btn btn-light">More ></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
  var userid = <?php echo $userid?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<!-- Your Custom JavaScript -->
<script src="andar_bahar2.js"></script>

</body>
</html>
