<?php

$iptablesFile = "/etc/iptables.up.rules";
$rules = file_get_contents ($iptablesFile);
include ("index.php");
$ports = file ("ports");

$allowLines = array();
$closeLines = array();
foreach ($ports as $key => $port) {
  $ports[$key] = trim ($port);
  if ((int)$port == 0) continue;
  $allowLine = "-I INPUT -p tcp -s " . $_SERVER["REMOTE_ADDR"] . " --dport " . (int)$ports[$key] . " -j ACCEPT";
  if (strpos($rules, $allowLine) === false) {
    $allowLines[] = $allowLine;
  }
  $closeLine = "-A INPUT -p tcp -m tcp --dport " . (int)$ports[$key] . " -j DROP";
  $closeLines[] = $closeLine;
}

preg_match ("%# OpenSesame allow start(.*?)# OpenSesame allow end%sim", $rules, $allowPart);
$remainingAllows = trim(trim($allowPart[1]) . "\n" . implode("\n", $allowLines));
$closePart = implode("\n", $closeLines);
$rules = preg_replace ("%# OpenSesame allow start.*?# OpenSesame allow end%sim",
                       "# OpenSesame allow start\n" . $remainingAllows . "\n# OpenSesame allow end", $rules);
$rules = preg_replace ("%# OpenSesame block start.*?# OpenSesame block end%sim",
                       "# OpenSesame block start\n" . $closePart . "\n# OpenSesame block end", $rules);
$fh = fopen ($iptablesFile, "w");
fwrite ($fh, $rules);
fclose ($fh);

$fh = fopen ("closetime.stamp", "w");
fwrite ($fh, time() + 60*60*12);
fclose ($fh);

include("show.php");