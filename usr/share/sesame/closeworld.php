<?php
$iptablesFile = "iptables.up.rules";
copy ($iptablesFile, "/etc/" . $iptablesFile);
include ("index.php");
echo "Clean up was successful.<br /><br />";
include("show.php");