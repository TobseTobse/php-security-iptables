<?php $ip = $_SERVER["REMOTE_ADDR"]; ?>
<html>
<head>
  <title>Open Sesame</title>
  <style type="text/css">
    body { font-family: Arial; font-size: 16px }
    #remaining { font-size: 24px; font-weight: bold }
  </style>
</head>
<body>
<center>
<h1>Sesame</h1>
<h3><a href="onlyme.php">Open sesame for myself (<?= $ip ?>) and close for everyone else</a></h3>
<h3><a href="addme.php">Open sesame additionally for myself (<?= $ip ?>)</a></h3>
<h3><a href="closeme.php">Close sesame only for myself (<?= $ip ?>)</a></h3>
<h3><a href="closeworld.php">Close sesame for everyone including myself</a></h3>

<?php
$script = substr(strrchr($_SERVER["REQUEST_URI"], "/"), 1);
if ($script == "index.php" || strlen($script) < 2) {
  include("show.php");
}