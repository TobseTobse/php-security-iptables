<?php

# config start
$servername = "Your Server Name";
$fromMail = "info@yourdomain.com";
$toMail = "your@mailaddress.com";
# config end

chdir (dirname(__FILE__));
$ipTablesFile = "/etc/iptables.up.rules";
$filesize = @filesize($ipTablesFile);
if (!file_exists($ipTablesFile) || $filesize < 2048) {
  copy ("/usr/share/sesame/etc.iptables.up.rules",
        $ipTablesFile);
  $body = "IPtables rules only had " . $filesize
        . " bytes. No further action necessary.";
  $header = "From: " . $servername . " "
          . "<" . $fromMail . ">\r\n";
  mail($toMail, "System integrity check failed",
       $body, $header);
}

$closestamp = (int)file_get_contents ("closetime.stamp");
if ($closestamp > 0 && time () > $closestamp) {
  $rules = file_get_contents($ipTablesFile);
  $rules = preg_replace ("%# OpenSesame allow start.*?# OpenSesame allow end%sim",
                         "# OpenSesame allow start\n# OpenSesame allow end", $rules);
  $fh = file_put_contents ($ipTablesFile, $rules);
  unlink("closetime.stamp");
}