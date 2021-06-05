<?php
$server = 'sql4.freemysqlhosting.net';
$username = 'sql4415624';
$password = 'uN4MbIRMc6';
$schema = 'sql4415624';
$pdo = new PDO('mysql:dbname=' . $schema . ';host=' . $server, $username, $password,
[ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
?>