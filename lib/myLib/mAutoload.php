<?php
//autoload my lib's
spl_autoload_register(function ($class){
	$filename = $_SERVER['DOCUMENT_ROOT']."/lib/myLib/$class.php";
	if (file_exists($filename))
		include_once ($filename); 
});
echo $_SERVER['PATH_INFO'].PHP_EOL;
?>