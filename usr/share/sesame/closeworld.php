<?php
$iptablesFile = "/etc/iptables.up.rules";
copy ("etc.iptables.up.rules", $iptablesFile);
include ("index.php");
echo "Clean up was successful.<br /><br />";
include("show.php");