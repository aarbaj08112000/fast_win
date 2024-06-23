<?php

include ("include/connection.php");
$id=$_GET['id'];
 $Query=mysqli_query($con,"update `tbl_user` set is_agent=1 where id=$id");
 $Query2=mysqli_query($con,"insert into `agent_online`(agent_id) value($id)");
 
 header('Location:manage_user.php');



?>