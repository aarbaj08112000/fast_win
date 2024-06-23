<?php
include ("include/connection.php");
if($_POST['userid'])
{
 $userid=$_POST['userid'];
 $type=$_POST['type'];
 echo '<option selected="selected" value="">Select Bank Detail</option>';

$sql=mysqli_query($con,"select * from `tbl_bankdetail` where `userid` ='".$userid."' ");
$rows=mysqli_num_rows($sql);
if($rows!=''){

while($row=mysqli_fetch_array($sql))
{
$id=$row['id'];
$data=$row['bankname'];
$account=$row['account'];
$upi=$row['upid'];
if($type=='bank'){$a=$account;}else{$a=$upi;}
?>
<option style="font-size:14px;" value="<?php echo $id;?>"><?php echo $a;?></option>
<?php
}}else{
$id='';
$data='Not found';


echo '<option value="'.$id.'" style="color:#f00;">'.$data.'</option>';
}
}
?>