<?php session_start(); ?>
<?php
$org=6;
if(isset($_SESSION['org'])) $org=$_SESSION['org'];
if(isset($_SESSION['security'])){
 if (!($_SESSION['security'] & 64)){die("Secruity level too low for this page");}
}else{
 header('Location: Login.php');
 die("Please logon");
}
?>
<!DOCTYPE HTML>
<html>
<meta name="viewport" content="width=device-width">
<meta name="viewport" content="initial-scale=1.0">
<head>
<link rel="stylesheet" type="text/css" href="styleform1.css">
</head>
<body>
<script>function goBack() {window.history.back()}</script>
<?php
$DEBUG=0;
$pageid=27;
$errtext="";
$sqltext="";
$error=0;
$trantype="Create";
$recid=-1;
$id_f="";
$id_err="";
$name_f="";
$name_err="";
$bill_pic_f="";
$bill_pic_err="";
$bill_p2_f="";
$bill_p2_err="";
$bill_other_f="";
$bill_other_err="";
function InputChecker($data)
{
 $data = trim($data);
 $data = stripslashes($data);
 $data = htmlspecialchars($data);
 return $data;
}
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
 if($_GET['id'] != "" && $_GET['id'] != null)
 {
  $recid = $_GET['id'];
  if ($recid >= 0)
  {
$con_params = require('./config/database.php'); $con_params = $con_params['gliding']; 
$con=mysqli_connect($con_params['hostname'],$con_params['username'],$con_params['password'],$con_params['dbname']);
   if (mysqli_connect_errno())
   {
    $errtext= "Failed to connect to Database: " . mysqli_connect_error();
   }
   else
   {
    $q = "SELECT * FROM billingoptions WHERE id = " . $recid ;
    $r = mysqli_query($con,$q);
    $row = mysqli_fetch_array($r);
    $id_f = $row['id'];
    $name_f = htmlspecialchars($row['name'],ENT_QUOTES);
    $bill_pic_f = $row['bill_pic'];
    $bill_p2_f = $row['bill_p2'];
    $bill_other_f = $row['bill_other'];
    $trantype="Update";
    mysqli_close($con);
   }
  }
 }
}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
 $error=0;
 $id_err = "";
 $name_err = "";
 $bill_pic_err = "";
 $bill_p2_err = "";
 $bill_other_err = "";
 $name_f = InputChecker($_POST["name_i"]);
if(in_array("1",$_POST['bill_pic_i']))
 $bill_pic_f = 1;
else
 $bill_pic_f = 0;
 if (!empty($bill_pic_f ) ) {if (!is_numeric($bill_pic_f ) ) {$bill_pic_err = "Charge PIC is not numeric";$error = 1;}}
if(in_array("1",$_POST['bill_p2_i']))
 $bill_p2_f = 1;
else
 $bill_p2_f = 0;
 if (!empty($bill_p2_f ) ) {if (!is_numeric($bill_p2_f ) ) {$bill_p2_err = "Charge P2 is not numeric";$error = 1;}}
if(in_array("1",$_POST['bill_other_i']))
 $bill_other_f = 1;
else
 $bill_other_f = 0;
 if (!empty($bill_other_f ) ) {if (!is_numeric($bill_other_f ) ) {$bill_other_err = "Charge Other is not numeric";$error = 1;}}
 if ($error != 1)
 {
$con_params = require('./config/database.php'); $con_params = $con_params['gliding']; 
$con=mysqli_connect($con_params['hostname'],$con_params['username'],$con_params['password'],$con_params['dbname']);
   if (mysqli_connect_errno())
   {
    $errtext= "Failed to connect to Database: " . mysqli_connect_error();
   }
   else
   {
     $Q = "";
     if (isset($_POST["del"]) ) {if ($_POST["del"] == "Delete"){
       $Q="DELETE FROM billingoptions WHERE id = " . $_POST['updateid'] ;}}
     if (isset($_POST["tran"]) ) {if ($_POST["tran"] == "Update"){
      $Q = "UPDATE billingoptions SET ";
      $Q .= "name=";
      $Q .= "'" . mysqli_real_escape_string($con, $name_f)  . "'";
      $Q .= ",bill_pic=";
      $Q .= "'" . $bill_pic_f . "'";
      $Q .= ",bill_p2=";
      $Q .= "'" . $bill_p2_f . "'";
      $Q .= ",bill_other=";
      $Q .= "'" . $bill_other_f . "'";
$Q .= " WHERE ";$Q .= "id ";$Q .= "= ";
$Q .= $_POST['updateid'];}
     else
     if ($_POST["tran"] == "Create"){
       $Q = "INSERT INTO billingoptions (";$Q .= "name";$Q .= ", bill_pic";$Q .= ", bill_p2";$Q .= ", bill_other";$Q .= " ) VALUES (";
       $Q .= "'" . mysqli_real_escape_string($con, $name_f) . "'";
       $Q.= ",";
       $Q .= "'" . $bill_pic_f . "'";
       $Q.= ",";
       $Q .= "'" . $bill_p2_f . "'";
       $Q.= ",";
       $Q .= "'" . $bill_other_f . "'";
      $Q.= ")";
    }}
    $sqltext = $Q;
    if(!mysqli_query($con,$Q) )
    {
       $errtext = "Database entry: " . mysqli_error($con) . "<br>" . $Q;
    }
    mysqli_close($con);
  }
 }
$name_f=htmlspecialchars($name_f,ENT_QUOTES);
}
?>
<div id='divform'>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
<?php if (true)
{
echo "<tr><td class='desc'>ID</td><td></td>";
echo "<td>";
echo $id_f; echo "</td>";echo "<td>";
echo $id_err; echo "</td></tr>";
}
?>
<?php if (true)
{
echo "<tr><td class='desc'>Billing Scheme Name</td><td></td>";
echo "<td>";
echo "<input ";
if (strlen($name_err) > 0) echo "class='err' ";echo "type='text' ";echo "name='name_i' ";echo "Value='";echo $name_f;echo "' ";echo " autofocus ";echo ">";echo "</td>";echo "<td>";
echo $name_err; echo "</td></tr>";
}
?>
<?php if (true)
{
echo "<tr><td class='desc'>Charge PIC</td><td></td>";
echo "<td><input type='checkbox' name='bill_pic_i[]' Value='1' ";if ($bill_pic_f ==1) echo "checked";echo "></td>";echo "<td>";
echo $bill_pic_err; echo "</td></tr>";
}
?>
<?php if (true)
{
echo "<tr><td class='desc'>Charge P2</td><td></td>";
echo "<td><input type='checkbox' name='bill_p2_i[]' Value='1' ";if ($bill_p2_f ==1) echo "checked";echo "></td>";echo "<td>";
echo $bill_p2_err; echo "</td></tr>";
}
?>
<?php if (true)
{
echo "<tr><td class='desc'>Charge Other</td><td></td>";
echo "<td><input type='checkbox' name='bill_other_i[]' Value='1' ";if ($bill_other_f ==1) echo "checked";echo "></td>";echo "<td>";
echo $bill_other_err; echo "</td></tr>";
}
?>
</table>
<table>
<tr><td><input type="submit" name = 'tran' value = '<?php echo $trantype; ?>'></td><td><?php if ($trantype == "Update") echo "<input type='submit' name = 'del' value = 'Delete'>";?></td><td></td><td></td></tr>
</table>
<input type="hidden" name = 'updateid' value = '<?php echo $recid; ?>'>
</form>
</div>
<div>
<p><?php echo $errtext; ?></p>
<?php if ($DEBUG>0) echo "<p>".$sqltext."</p>"; ?>
</div>
</body>
</html>
