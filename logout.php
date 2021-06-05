<?php
session_start();
require 'configuration.php';
unset($_SESSION['loggedin']);
session_destroy();
header('Location: login.php');
?>