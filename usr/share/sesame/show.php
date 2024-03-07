<h1>Current configuration</h1>
<pre>
<?php
$script = file_get_contents("/etc/iptables.up.rules");
preg_match("%(# OpenSesame allow start.*?"
         . "# OpenSesame block end)%sim", $script, $hits);
$rules = preg_replace("%end\s+?#%sim", "end\n\n#", $hits[1]);
$rules = preg_replace("%^\:.*?$%sm", "", $rules);
$rules = preg_replace("%((?:\d+\.){3}\d+)%sm",
                      '<b style="background:yellow">$0</b>', $rules);
echo $rules;
echo "</pre>";

echo "This setup will become effective in "
   . '<span id="remaining">' . (60 - date ("s")) . '</span>'
   . " seconds.";

?>
<script type="text/javascript">
  var restSeconds = <?php echo (60 - date ("s")) ?>;
  function timeUpdate () {
    restSeconds--;
    document.getElementById ("remaining").innerHTML = restSeconds;
    if (restSeconds > 0) window.setTimeout ("timeUpdate()", 1000);
  }
  timeUpdate ();
</script>

<h1>Denied Hosts</h1>
<div style="width: 100px"><pre>
<?php
$lines = file("/etc/hosts.deny");
$hosts = array();
foreach ($lines as $line) {
  $line = trim($line);
  if (trim($line) != "" && substr($line, 0, 1) != "#") {
    $hosts[] = $line;
  }
}
if (count($hosts) == 0) {
  echo "No hosts denied yet.";
}
if (count($hosts) <= 10) {
  echo implode("\n", $hosts);
} else {
  for ($i=0; $i<=9; $i++) {
    echo $hosts[$i] . "\n";
  }
  echo "... and " . (count($hosts) - 10) . " more";
}
echo "</pre></div>";