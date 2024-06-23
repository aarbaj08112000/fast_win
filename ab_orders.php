<?php
ob_start();
session_start();
if($_SESSION['frontuserid']=="")
{
  header("location:login.php");exit();
}
include ("db_common_connection.php");
// $conn = mysqli_connect('localhost','root','','fastwin');
//
// if (!$conn) {
//   die("Connection failed: " . mysqli_connect_error());
//   echo "string";
// }
$user_id = $_SESSION['frontuserid'];
$myOrders = [];
if (isset($user_id)) {
  $query = mysqli_query($conn, "SELECT * FROM tbl_ab_orders WHERE user_id = $user_id ORDER BY created_on DESC");

  if ($query) {

    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
      $myOrders[] = $row;
    }
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Andar Bahar Orders</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <!-- Font Awesome CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="andar_bahar.css">
</head>
<body>
  <div class="container-fluid">
    <div class="row main">
      <div class="col-md-6 col-lg-4">
        <div class="row rows">
          <div class="container">
            <div class="row d-flex header">
              <div class="col-2 tfw-5 tf-24">
                <a href="andar_bahar.php" class="text-white">
                  <i class="fa fa-angle-left"></i>
                </a>
              </div>
              <div class="col-8 tfw-5 tf-18 text-center">Order</div>
              <div class="col-2 tfw-5 tf-18 text-end rule" ></div>
            </div>
            <div class="tabs">
              <ul class="nav nav-tabs ab_nav_tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="parity-tab" data-bs-toggle="tab" data-bs-target="#parity" type="button" role="tab" aria-controls="parity" aria-selected="true">Parity</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="fast-parity-tab" data-bs-toggle="tab" data-bs-target="#fast-parity" type="button" role="tab" aria-controls="fast-parity" aria-selected="false">Fast Parity</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="andar-bahar-tab" data-bs-toggle="tab" data-bs-target="#andar-bahar" type="button" role="tab" aria-controls="andar-bahar" aria-selected="false">Andar Bahar</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="mini-game-tab" data-bs-toggle="tab" data-bs-target="#mini-game" type="button" role="tab" aria-controls="mini-game" aria-selected="false">Mini Game</button>
                </li>
              </ul>
              <div class="tab-content ab_tab_content" id="myTabContent">
                <div class="tab-pane fade show active h-100" id="parity" role="tabpanel" aria-labelledby="parity-tab">
                <h5 class="text-center p-5">  No Record Found </h5>
                </div>
                <div class="tab-pane fade h-100" id="fast-parity" role="tabpanel" aria-labelledby="fast-parity-tab">

                  <h5 class="text-center p-5">  No Record Found </h5>
                </div>
                <!-- Andar Bahar Orders -->
                <div class="tab-pane fade p-2 " id="andar-bahar" role="tabpanel" aria-labelledby="andar-bahar-tab">



                  <?php
                  if (count($myOrders) > 0) {
                    foreach ($myOrders as $key => $item) {
                      ?>
                      <div class="ab_orders mb-2">
                        <table class="table borderless">
                          <tbody>
                            <tr class="mt-2">
                              <td class="p-1" colspan="2"><h6><?php echo $item["period_id"]; ?></h6></td>
                              <td colspan="2" class="text-end">
                                <?php
                                $date = new DateTime($item["created_on"]);
                                $formattedDate = $date->format('d/m H:i:s');
                                echo $formattedDate;
                                ?>
                              </td>
                            </tr>
                            <tr>
                              <td class="ps-2">Select</td>
                              <td>Point</td>
                              <td>Result</td>
                              <td class="text-end pe-2">Amount</td>
                            </tr>
                            <tr>
                              <td class="ps-2">
                                <span class="mb-2 <?php if ($item["betting_type"] == "Andar"){echo "ab_order_andar";}else if ($item["betting_type"] == "Bahar"){echo "ab_order_bahar";}else if ($item["betting_type"] == "Tie"){echo "ab_order_tie";} ?> selcted_value">
                                  <?php echo $item["betting_type"]; ?>
                                </span>
                              </td>
                              <td class="points"><?php echo number_format($item["amount"], 2, '.', ',') ; ?></td>
                              <td class="result">
                                <span class="<?php if ($item["result"] == "Andar"){echo "ab_order_andar";}else if ($item["result"] == "Bahar"){echo "ab_order_bahar";}else if ($item["result"] == "Tie"){echo "ab_order_tie";} ?>">
                                  <?php echo $item["result"]; ?>
                                </span>
                              </td>

                              <?php if ($item["result"] == $item["betting_type"]): ?>
                                <td class="text-success text-end pe-2">
                                  <?php
                                  $winning_amount = $item["amount"] *2;
                                  $amount = $winning_amount * 0.02;
                                  echo "+₹" . number_format($winning_amount - $amount, 2, '.', ',');
                                  ?>
                                </td>
                              <?php else: ?>
                                <td class="text-danger text-end pe-2">
                                  <?php
                                  $amount = $item["amount"] * 0.02;
                                  echo "-₹" . number_format($item["amount"] - $amount, 2, '.', ',');
                                  ?>
                                </td>
                              <?php endif; ?>
                            </tr>
                            <tr style="border-top: 2px solid #e5e8eb">
                              <td class="ps-2" colspan="2">Delivery: <?php
                              $winning_amount = $item["amount"] *2;
                              $amount = $winning_amount * 0.02;
                              echo "+₹" . number_format($winning_amount - $amount, 2, '.', ',');
                              ?></td>
                              <td colspan="2" class="text-end pe-2">Fee: ₹0.20</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <?php
                    }
                  }
                  ?>

                </div>
                <div class="tab-pane fade h-100" id="mini-game" role="tabpanel" aria-labelledby="mini-game-tab">
                  <h5 class="text-center p-5">  No Record Found </h5>
                  </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
</body>
</html>
