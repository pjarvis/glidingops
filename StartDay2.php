<?php
include 'timehelpers.php';
include 'helpers.php';
$org=6;
$strdtnow='';
$con_params = require('./config/database.php'); $con_params = $con_params['gliding']; 
$con=mysqli_connect($con_params['hostname'],$con_params['username'],$con_params['password'],$con_params['dbname']);
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
 if (isset($_GET['org']) )
 {
   $org=$_GET['org'];
   if ($org < 1)
   {
     die("Error You must supply and organisation number");
   }
 }
 $dateTime = new DateTime("now", new DateTimeZone(orgTimezone($con,$org)));
 $strdtnow=$dateTime->format('Y-m-d');
}
?>
<!DOCTYPE HTML>
<html>
<head>
<title>Enter Location</title>
<style><?php $inc = "./orgs/" . $org . "/heading1.css"; include $inc; ?></style>
<style>
body {margin: 0px;font-family: Arial, Helvetica, sans-serif;background-color:#f0f0ff;}
#container {background-color:#f0f0ff;}
#entry {background-color:#f0f0ff;margin-left:20px;}
table {border-collapse: collapse;}
.bigger {font-size: 20px;}
p.fg {font-size:11px;}
.right {text-align: right;}
a {text-decoration: none;}
a.fg {font-size:11px;}
a:link{color: #0000c0;}
a:visited {color: #0000C0;}
a:hover {color: #0000FF;}
.nodisp {display:none;}
</style>
<script>
function CheckChange(id)
{
   if (id.checked)
   {
      document.getElementById("dt1").disabled = false;
      document.getElementById("dt1").style.display="inline";
   }
   else
   {
      document.getElementById("dt1").disabled = true;
      document.getElementById("dt1").style.display="none";
   }
   
}
</script>
</head>
<?php
$errtext='';
$defval='';
if ($_SERVER["REQUEST_METHOD"] == "GET")
{
    $q = "SELECT default_location FROM organisations WHERE id = " . $org;
    $r = mysqli_query($con,$q);
    $row_cnt = $r->num_rows;
    if ($row_cnt > 0)
    {    
        $row = mysqli_fetch_array($r);
        $defval=$row[0];
    }
    else
    {
        echo "Invalid organistaion number<br>";
        exit();
    }



}
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $org=$_POST['org'];
   if ($org <=0)
      die("No organistain specified");
   $loc = $_POST['location'];
   if (strlen($loc) > 0)
   {
        //Check to see if we have a different date specifed.   	
        $v = $_POST['specdate'];
        if ($v == 'on')
        {
             $v = "&ds=" . $_POST['date'];
        }
        else
             $v = '';
        $l= "Location: dailysheet.php?org=" .$org. "&location=" .$loc;
        if (strlen($v) > 0)
           $l .= $v;
        header($l);
   }
   else
      $errtext = "You must enter a location";
}
?>
<body>
<?php $inc = "./orgs/" . $org . "/heading1.txt"; include $inc; ?>
<div id='container'>
<div id='entry'>
<p>Start Days Timesheet</p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<table>
<tr><td>Enter Location:</td><td><input class ='bigger' type='text' value = '<?php echo $defval; ?>' name='location' size='25' title='Enter the location for this timesheet' autofocus></td><td></td></tr>
<tr><td class ='bigger'></td><td></td></tr>
<tr><td>Start or edit for a different date:</td><td><input type='checkbox' name='specdate' onchange='CheckChange(this);'><input id='dt1' class='nodisp' type='date' name='date' value = <?php echo $strdtnow;?> disabled></td></tr>
<tr><td></td><td class='right'><input type="submit" name"Submit" value="Create"></td><td></td></tr>
<tr><td><?php echo $errtext;?></td><td></td></tr>
</table>
<input type='hidden' name='org' value='<?php echo $org;?>'>
</form>
</div>
</div>
</body>
</html>
