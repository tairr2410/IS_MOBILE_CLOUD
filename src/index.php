<?php
require_once (__DIR__."/functions.php");

defined ( 'APPLICATION_ENV' ) || define ( 'APPLICATION_ENV', (getenv ( 'APPLICATION_ENV' ) ? getenv ( 'APPLICATION_ENV' ) : 'development') );
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "App v2.2" . PHP_EOL . PHP_EOL;

echo "Hello, Tair! :)" . PHP_EOL . PHP_EOL;
echo "Trying to connect to sql server in azure..." . PHP_EOL . PHP_EOL;


$iconfigAll = parse_ini_file_extended("/var/www/config/database.ini");
$iconfig = $iconfigAll[APPLICATION_ENV];

$conn = "mobiletst";
$host = $iconfig["database.{$conn}.params.host"];
$port = $iconfig["database.{$conn}.params.port"];
$username = $iconfig["database.{$conn}.params.username"];
$password = $iconfig["database.{$conn}.params.password"];
$sid = $iconfig["database.{$conn}.params.dbname"];

$connectionInfo = array("UID" => $username, "pwd" => $password, "Database" => $sid, "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
$serverName = "tcp:$host,$port";
$conn = sqlsrv_connect($serverName, $connectionInfo);
if ($conn == FALSE){
    var_dump(sqlsrv_errors());
    die;
}
$sql = "select top 100 tblAuthors.* from tblAuthors";
$getResults = sqlsrv_query($conn, $sql);
echo "Reading data from table..." . PHP_EOL . PHP_EOL;
if ($getResults == false){
    var_dump(sqlsrv_errors());
    die;
}
while ($row = sqlsrv_fetch_array($getResults, SQLSRV_FETCH_ASSOC)) {
    echo $row['Id'] . " " . $row['Author_name'] . " " . $row['country'] . PHP_EOL;
}