<?php $urlpage= basename($_SERVER['PHP_SELF']);
$active='';

?>
<div class="appBottomMenu">
  <div class="item <?php if($urlpage=='index.php'){echo'active';}?>"> <a href="home.php">
    <p> <i class="icon ion-md-home"></i> <span>Home</span> </p>
    </a> </div>
 
   <?php if(isset($_SESSION['frontuserid'])){?>
    <div class="item <?php if($urlpage=='gamedashboard.php'){echo'active';}?>"> <a href="gamedashboard.php">
    <p> <i class="icon ion-md-trophy"></i> <span>Parity</span> </p>
    </a> </div>
   <div class="item <?php if($urlpage=='gamedashboard-fast.php'){echo'active';}?>"> <a href="gamedashboard-fast.php">
    <p> <i class="icon ion-md-trophy"></i> <span>Fast Parity</span> </p>
    </a> </div>
    
 <?php

          $sql=mysqli_query($con,"select * from `tbl_user` where `id` ='".$userid."' and `is_agent`=1");
$rows=mysqli_num_rows($sql);
if($rows!=''){
          
          ?>
          
           <div class="item <?php if($urlpage=='agent.php'){echo'active';}?>"> <a href="agent.php">
    <p> <i class="icon ion-md-trophy"></i> <span>Agent Panel</span> </p>
    </a> </div>
          <?php }?>
    
    <div class="item <?php if($urlpage=='login.php' || $urlpage=='signup.php' || $urlpage=='forgot-password.php' || $urlpage=='myaccount.php' || $urlpage=='recharge.php' || $urlpage=='transactions.php'){echo'active';}?>"> <a href="myaccount.php" class="icon toggleSidebar">
    <p> <i class="icon ion-md-person"></i> <span>My Account </span> </p>
    </a> </div>
    <?php }else{?>
  <div class="item <?php if($urlpage=='login.php' || $urlpage=='signup.php' || $urlpage=='forgot-password.php'){echo'active';}?>"> <a href="login.php" class="icon toggleSidebar">
    <p> <i class="icon ion-md-person"></i> <span>My Account </span> </p>
    </a> </div>
    <?php }?>
</div>