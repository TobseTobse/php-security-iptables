<?php

$iptablesFile = "/etc/iptables.up.rules";

include ("index.php");
$ports = file ("ports");
foreach ($ports as $key => $port) {
  $ports[$key] = trim ($port);
  if ((int)$port == 0) continue;
  $closeLines[] = "-A INPUT -p tcp -m tcp --dport " . (int)$ports[$key] . " -j DROP";
}

$lines = file($iptablesFile);
$rules = "";
foreach ($lines as $line) {
  if (strpos($line, $_SERVER["REMOTE_ADDR"]) === false) {
    $rules.= $line;
  }
}
$closePart = implode("\n", $closeLines);
$rules = preg_replace ("%# OpenSesame block start.*?# OpenSesame block end%sim",
                       "# OpenSesame block start\n" . $closePart . "\n# OpenSesame block end", $rules);
$fh = fopen ($iptablesFile, "w");
fwrite ($fh, $rules);
fclose ($fh);

include("show.php");
