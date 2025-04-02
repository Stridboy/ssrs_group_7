<?php
$host = 'sql205.infinityfree.com';
$user = 'if0_38657767';
$password = 'lmid7YiwN3s';
$database = 'if0_38657767_ssrs';

$db = mysqli_connect($host, $user, $password, $database);
if ($db) {
} else {
  echo "Connection failed";
}
