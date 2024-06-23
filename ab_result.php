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
$period_ids = array();
$sql = mysqli_query($conn, "SELECT
    r.game_id as gameid,
    r.result,
    COALESCE(SUM(tao.amount), 0) as total_amount
FROM
    tbl_ab_result as r
LEFT JOIN
    tbl_ab_orders as tao ON tao.period_id = r.game_id
GROUP BY
    r.game_id, r.result
ORDER BY
    r.game_id DESC");

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
  // $period_ids = $uniqueArray;
  // echo "<pre>";
  // print_r($period_ids);
  // exit;
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
                <div class="col-8 tfw-5 tf-18 text-center">Result</div>
                <div class="col-2 tfw-5 tf-18 text-end rule" ></div>
              </div>
              <table class="table table-borderless">
                <thead>
                  <tr>
                    <th scope="col">Period</th>
                    <th scope="col">Price</th>
                    <th scope="col" class="text-end">Result</th>

                  </tr>
                </thead>
                <tbody >
                  <?php foreach ($period_ids as $index => $period){ ?>
                    <tr>
                      <td><?php echo $period['gameid']; ?></td>
                      <td><?php echo "₹" . number_format($period['total_amount'], 2, '.', ','); ?></td>
                      <td class="text-end me-5 pe-3">
                        <div class="sc <?php echo strtolower(!empty($period['result'][0]) ? $period['result'][0] : 'n') ?>"><div class=""><?php echo !empty($period['result']) ? strtoupper($period['result'][0]) : 'N'; ?></div></div>
                      </td>
                    </tr>
                  <?php } ?>


                </tbody>
              </table>

            </div>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

  </body>
  </html>
