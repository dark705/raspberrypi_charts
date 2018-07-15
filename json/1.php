<?php
date_default_timezone_set( 'UTC' );
//echo date('Y-m-d H:i:s');
$mysqli = new mysqli('localhost', 'chart', 'chart', 'chart');
 
$query = "SELECT `datetime`, `voltage`, `current`,`active` FROM `t_power`;";
$result = $mysqli->query($query);
 
while ($record = $result->fetch_row()){
    $all[] =  array(strtotime($record[0]), (float)$record[1], (float)$record[2], (float)$record[3]);
}
echo json_encode($all);
 
$mysqli->close();
?>