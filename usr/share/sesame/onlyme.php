<?php
include ("index.php");

$iptablesFile = "iptables.up.rules";
copy ($iptablesFile, "/etc/" . $iptablesFile);
$iptablesFile = "/etc/" . $iptablesFile;
$rules = file_get_contents ($iptablesFile);
$ports = file ("ports");

$allowLines = array();
foreach ($ports as $key => $port) {
  $ports[$key] = trim ($port);
  if ((int)$port == 0) continue;
  $allowLine = "-I INPUT -p tcp -s " . $_SERVER["REMOTE_ADDR"] . " --dport " . (int)$ports[$key] . " -j ACCEPT";
  $allowLines[] = $allowLine;
  $closeLine = "-A INPUT -p tcp -m tcp --dport " . (int)$ports[$key] . " -j DROP";
  $closeLines[] = $closeLine;
}

$closePart = implode("\n", $closeLines);
$rules = preg_replace ("%# OpenSesame allow start.*?# OpenSesame allow end%sim",
                       "# OpenSesame allow start\n" . implode("\n", $allowLines) . "\n# OpenSesame allow end", $rules);
$rules = preg_replace ("%# OpenSesame block start.*?# OpenSesame block end%sim",
                       "# OpenSesame block start\n" . $closePart . "\n# OpenSesame block end", $rules);

$fh = fopen ($iptablesFile, "w");
fwrite ($fh, $rules);
fclose ($fh);

$fh = fopen ("closetime.stamp", "w");
fwrite ($fh, time() + 60*60*12);
fclose ($fh);

include("show.php");
