<?php
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '4024M'); // or you could use 1G
error_reporting(E_ALL);
$command = '/var/www/html/wscvuy/getDimensionsDataTest.sh';
exec($command);

 ?>
