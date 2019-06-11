</!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<?php

$var1 = 'hello world';
$var2 = 'mein name ist';
echo $var1.$var2;

$var3 = array("markus","valentin","rafi");
$var4 = array("andrei");
//insert at end
array_push($var3,"holweg");
//insert at beginning
array_unshift($var3,"pohn");
//insert at specific position
array_splice($var3,2,0,"lucian");
//echo for debugging for position
print_r($var3);


echo $var3[0]." ".$var3[1]." ".$var3[2];
$i = 0;
//count gives me array length
echo count($var3);
for($i = 0 ; $i <= count($var3)-1 ; $i++ )
{
	echo "What am I doing ";
	if($var3[$i] == "markus")
	{
		echo "<b>$var3[$i]</b><br>";
	}else
	{
		echo "$var3[$i]<br>";
	}

}
?>
</body>
</html>