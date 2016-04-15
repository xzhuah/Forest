<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="css/read.css" type="text/css" rel="stylesheet"/>
<title>Read</title>

</head>
<!--http://10.89.116.121:8080/hackUST/Forest/Front_End/read.php-->
<body>
<?php
$id="5710c6d771cfe4005b0c2473";
$handle = fopen("http://10.89.116.121:3000/node/".$id,"rb");
$content = "";
while (!feof($handle)) {
    $content .= fread($handle, 10000);
}
fclose($handle);
$json = $content;
$obj = json_decode($json,true);
?>




</body>
</html>
