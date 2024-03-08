<?php

include("config.php");
chdir (dirname(__FILE__));
$iptablesFile = "iptables.up.rules";
$filesize = @filesize("/etc/" . $iptablesFile);
if (!file_exists("/etc/" . $iptablesFile) || $filesize < 2048) {
  copy ($iptablesFile, "/etc/" . $iptablesFile);
  $body = "IPtables rules only had " . $filesize
        . " bytes. No further action necessary.";
  $header = "From: " . $fromName . "<" . $fromMail . ">\r\n";
  mail($toMail, "System integrity check failed",
       $body, $header);
}

$closestamp = (int)file_get_contents ("closetime.stamp");
if ($closestamp > 0 && time () > $closestamp) {
  copy ($iptablesFile, "/etc/" . $iptablesFile);
  unlink("closetime.stamp");
}