<?php
require_once('connect.php');
if(isset($_REQUEST['id']))
{
	$query="UPDATE `user` SET `validate`=1 where `id`='$_REQUEST[id]'";	
	$mysqli->query($query);
	echo "validated";
}
?>
