<?php
$to = "uttam.kumar@cloudprism.in";
$subject = "My subject";
$txt = "Hello world!";
$headers = "From: webmaster@example.com" . "\r\n" .
"CC: somebodyelse@example.com";

$issent = mail($to,$subject,$txt,$headers);
echo $issent;
?>