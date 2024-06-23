<?php

include ("include/connection.php");
$id=$_GET['id'];
$user=$_GET['userid'];

 $Query=mysqli_query($con,"update `tbl_bankdetail` set status=1 where id=$id");
 $Query2=mysqli_query($con,"update `tbl_bankdetail` set status=0 where id !=$id and userid=$user");
 
 header('Location:manage_bankcard.php');



?>