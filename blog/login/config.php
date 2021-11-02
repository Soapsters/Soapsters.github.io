<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'demo');
 
/* Attempt to connect to MySQL database */
//$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
$db = new PDO("pgsql:dbname=blog_srank20 host=localhost user=srank20 password=1993648");
 
// Check connection
if($db === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>