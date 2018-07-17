<?php
spl_autoload_register(function ($class){
	$filename = "lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});


?>
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Sensors</title>
		<link rel="stylesheet" href="css\style.css">
	</head>
	<body>
		<script src="lib/jquery/jquery-3.2.1.min.js"></script>
		<script src="lib/date.js"></script>
		<script src="lib/highstock/highstock.js"></script>
		<script src="lib/highstock/themes/grid-light.js"></script>
		<script src="lib/highstock/modules/exporting.js"></script>
		<script src="lib/highstock/lang/ru.js"></script>
		<script src="js/legend.js"></script>
		


		<?php include 'views/summary.php';?>
		<?php include 'views/pzem004t.php';?>
		<?php include 'views/dht22.php';?>
		<?php include 'views/ds18b20.php';?>



	</body>
</html>
