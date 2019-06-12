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
<style><?php $inc = "./orgs/" . $org . "/heading2.css"; include $inc; ?></style>
<style>
<?php $inc = "./orgs/" . $org . "/menu1.css"; include $inc; ?></style>
<link rel="stylesheet" type="text/css" href="styleform1.css">
</head>
<body>
<?php $inc = "./orgs/" . $org . "/heading2.txt"; include $inc; ?>
<?php $inc = "./orgs/" . $org . "/menu1.txt"; include $inc; ?>
<script>function goBack() {window.history.back()}</script>
<?php
$DEBUG=0;
$pageid=33;
$errtext="";
$sqltext="";
$error=0;
$trantype="Create";
$recid=-1;
$id_f="";
$id_err="";
$typename_f="";
$typename_err="";
$colour_f="";
$colour_err="";
$description_f="";
$description_err="";
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
    $q = "SELECT * FROM bookingtypes WHERE id = " . $recid ;
    $r = mysqli_query($con,$q);
    $row = mysqli_fetch_array($r);
    if ($_SESSION['org'] > 0 && $row['org'] != $_SESSION['org'])
       die("Record does not exist");
    $id_f = $row['id'];
    $typename_f = htmlspecialchars($row['typename'],ENT_QUOTES);
    $colour_f = htmlspecialchars($row['colour'],ENT_QUOTES);
    $description_f = htmlspecialchars($row['description'],ENT_QUOTES);
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
 $org_err = "";
 $typename_err = "";
 $colour_err = "";
 $description_err = "";
 $typename_f = InputChecker($_POST["typename_i"]);
 if (empty($typename_f) )
 {
  $typename_err = "NAME is required";
  $error = 1;
 }
 $colour_f = InputChecker($_POST["colour_i"]);
 $description_f = InputChecker($_POST["description_i"]);
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
       $Q="DELETE FROM bookingtypes WHERE id = " . $_POST['updateid'] ;}}
     if (isset($_POST["tran"]) ) {if ($_POST["tran"] == "Update"){
      $Q = "UPDATE bookingtypes SET ";
      $Q .= "typename=";
      $Q .= "'" . mysqli_real_escape_string($con, $typename_f)  . "'";
      $Q .= ",colour=";
      $Q .= "'" . mysqli_real_escape_string($con, $colour_f)  . "'";
      $Q .= ",description=";
      $Q .= "'" . mysqli_real_escape_string($con, $description_f)  . "'";
$Q .= " WHERE ";$Q .= "id ";$Q .= "= ";
$Q .= $_POST['updateid'];}
     else
     if ($_POST["tran"] == "Create"){
       $Q = "INSERT INTO bookingtypes (";$Q .= "org";$Q .= ", typename";$Q .= ", colour";$Q .= ", description";$Q .= " ) VALUES (";
$Q.=$_SESSION['org'];       $Q.= ",";
       $Q .= "'" . mysqli_real_escape_string($con, $typename_f) . "'";
       $Q.= ",";
       $Q .= "'" . mysqli_real_escape_string($con, $colour_f) . "'";
       $Q.= ",";
       $Q .= "'" . mysqli_real_escape_string($con, $description_f) . "'";
      $Q.= ")";
    }}
    $sqltext = $Q;
    if(!mysqli_query($con,$Q) )
    {
       $errtext = "Database entry: " . mysqli_error($con) . "<br>" . $Q;
    }
    mysqli_close($con);
    if ($_POST["tran"] == "Delete")
     header('Location: BookingTypes');
    if ($_POST["tran"] == "Update")
     header('Location: BookingTypes');
  }
 }
$typename_f=htmlspecialchars($typename_f,ENT_QUOTES);
$colour_f=htmlspecialchars($colour_f,ENT_QUOTES);
$description_f=htmlspecialchars($description_f,ENT_QUOTES);
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
echo "<tr><td class='desc'>NAME</td><td>*</td>";
echo "<td>";
echo "<input ";
if (strlen($typename_err) > 0) echo "class='err' ";echo "type='text' ";echo "name='typename_i' ";echo "Value='";echo $typename_f;echo "' ";echo " autofocus ";echo ">";echo "</td>";echo "<td>";
echo $typename_err; echo "</td></tr>";
}
?>
<?php if (true)
{
echo "<tr><td class='desc'>COLOUR</td><td></td>";
echo "<td>";
echo "<input ";
if (strlen($colour_err) > 0) echo "class='err' ";echo "type='text' ";echo "name='colour_i' ";echo "Value='";echo $colour_f;echo "' ";echo ">";echo "</td>";echo "<td>";
echo $colour_err; echo "</td></tr>";
}
?>
<?php if (true)
{
echo "<tr><td class='desc'>DESCRIPTION</td><td></td>";
echo "<td>";
echo "<input ";
if (strlen($description_err) > 0) echo "class='err' ";echo "type='text' ";echo "name='description_i' ";echo "Value='";echo $description_f;echo "' ";echo ">";echo "</td>";echo "<td>";
echo $description_err; echo "</td></tr>";
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
