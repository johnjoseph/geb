<?php
	session_start();
	if($_REQUEST['logout'])
	{
		session_destroy();
	}
	else if(isset($_COOKIE['token']))
	{
		header('location:home.php');
	}
?>
<html>
<head>
<style type='text/css'>
#signup,input[type=checkbox]
{
	display:none;
}
input:checked ~ #signup
{
	display:block;
}
</style>
</head>
<body>
<?php
	require_once('connect.php');
?>
<form action='home.php' method='post'>
<input type='text' name='username' placeholder='username'/>
<input type='password' name='password' placeholder='password'/>
<label for='check'><p style='text-decoration:underline;color:blue;'>Not registered yet</p></label>
<input type='checkbox' id='check' name='signup' value='signup' />
<div id='signup'>
<input type='text' name='name' placeholder='name'/>
<select name='dept'>
<?php
	$query="SELECT * FROM `department` WHERE 1";
	$result=$mysqli->query($query);
	while($row=$result->fetch_assoc())
	{
		echo "<option value='".$row['id']."''>".$row['name']."</option>";
	}
?>
</select>
</div>
<input type='submit'/>
</form>	
</body>
</html>