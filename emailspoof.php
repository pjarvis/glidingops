<?php
function SendMail($to,$subject,$message)
{
  $headers = 
   'From: V J n <veronica@myjarvis.com>' . "\r\n" .
   'X-Mailer: PHP/' . phpversion();   
  return mail($to, $subject, $message, $headers);
}
SendMail("peter@myjarvis.com","Can you please....","Sort out the leaves for Sunday's Open Home");
echo "Sent";
?>
