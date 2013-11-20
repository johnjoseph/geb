<?php
	setcookie('token','',time()-24*3600);
	header('location:index.php');
?>