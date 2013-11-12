<?php
	session_start();
	require_once('connect.php');
	/* signup */
	if(isset($_REQUEST['name'])&&($_REQUEST['name']!=''))
	{
		$query="SELECT MAX(`id`) FROM `faculty`";
		$result=$mysqli->query($query);
		$row=$result->fetch_assoc();
		$max=($row['MAX(`id`)'])?$row['MAX(`id`)']+1:1;
		$query="INSERT INTO `user` (`username`,`password`,`type`,`id`) VALUES ('$_REQUEST[username]','$_REQUEST[password]','fac','$max')";
		$result=$mysqli->query($query);
		$query="INSERT INTO `faculty` (`id`,`name`,`dept_id`) VALUES ('$max','$_REQUEST[name]','$_REQUEST[dept]')";
		$result=$mysqli->query($query);
		setcookie('token','fac_'.$max,time()+24*3600);
		$_COOKIE['token']='fac_'.$max;
	}
	/* login */
	else if(isset($_REQUEST['username'])&&($_REQUEST['name']!=''))
	{
		$query="SELECT * FROM `user` WHERE `username`='$_REQUEST[username]' AND `password`='$_REQUEST[password]'";
		if($result=$mysqli->query($query))
		{
			$row=$result->fetch_assoc();
			setcookie('token',$row['type'].'_'.$row['id'],time()+24*3600);
			$_COOKIE['token']=$row['type'].'_'.$row['id'];
		}		
	}
	/* add subject */
	else if(isset($_REQUEST['sub']))
	{
		$id=explode('_',$_COOKIE['token']);
		$query="INSERT INTO `subject` (`name`,`fac_id`,`slot`,`cut_off`,`max`) VALUES ('$_REQUEST[sub]','$id[1]','$_REQUEST[slot]','$_REQUEST[cutoff]','$_REQUEST[max]')";
		$result=$mysqli->query($query);
	}
	/* register for subject */
	else if(isset($_REQUEST['sub_id']))
	{
		$query="SELECT COUNT(*) FROM `reg` WHERE `id`='$_REQUEST[rollno]'";
		$result=$mysqli->query($query);
		$row=$result->fetch_assoc();
		$count=$row['COUNT(*)'];
		if($count<2)
		{
			$query="INSERT INTO `reg` VALUES ('$_REQUEST[rollno]','$_REQUEST[sub_id]')";
			$result=$mysqli->query($query);
			echo ($result)?"Registered":"Error";
		}
		else
		{
			echo "Sorry you can register for only 2 subjects";
		}
	}
?>
<html>
<head>
<style type="text/css">
input[type=checkbox],#add
{
	display: none;
}
#plus:checked + #add
{
	display: block;
}
</style>	
</head>
<body>
<?php
	$token=explode('_',$_COOKIE['token']);
	echo "<a href='index.php?logout=1' style='float:right'>logout</a>";
	if($token[0]=='fac')
	{
		echo "<h3>Subjects</h3>";
		$query="SELECT * FROM `subject` WHERE `fac_id`=$token[1]";
		$result=$mysqli->query($query);
		$sub="<table>";
		$sub.="<tr><th>Name</th><th>Slot</th><th>Cut off</th><th>Max</th></tr>";
		while($row=$result->fetch_assoc())
		{
			$sub.="<tr><td>".$row['name']."</td><td>".$row['slot']."</td><td>".$row['cut_off']."</td><td>".$row['max']."</td><td><input type='checkbox' class='reg'/><label for='reg'>register</label><span class='reg_form'><form action='home.php' method='post'><input type='text' name='rollno' placeholder='rollno'/><input type='hidden' name='sub_id' value='$row[id]'/><input type='submit'/></form></span></td></tr>";
		}
		$sub.="</table>";
		echo $sub;		
		$add="<label for='plus'><p>plus</p></label>";
		$add.="<input type='checkbox' id='plus'/>";
		$add.="<div id='add'>";
		$add.="<form action='home.php' method='post'>";
		$add.="<input type='text' name='sub' placeholder='subject name'/>";
		$add.="<input type='text' name='slot' placeholder='slot'/>";
		$add.="<input type='text' name='cutoff' placeholder='cut off'/>";
		$add.="<input type='text' name='max' placeholder='max'/>";
		$add.="<input type='submit'/>";
		$add.="</form>";
		$add.="</div>";
		echo $add;
	}
	if($token[0]=='adm')
	{
		echo "<h3>FACULTIES</h3>";
		$query="SELECT name,id FROM (SELECT faculty.name,faculty.id,user.validate FROM faculty INNER JOIN user ON faculty.id=user.id) AS fac WHERE validate=0;";
		$result=$mysqli->query($query);
		$sub="<form action='validate.php' method='post'><table>";
		$sub.="<tr><th>WAITING TO BE VALIDATED</th></tr><tr><th>FACULTY NAME</th></tr>";
		while($row=$result->fetch_assoc())
		{
			$sub.="<tr><td>".$row['name']."</td><td>
			<input type='submit' value='validate' name='".$row['id']."'></td></tr>"
		}
		$sub.="</table></form>";
		echo $sub;
	}
?>
	
</body>
</html>
