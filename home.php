<html lang="en">

    <head>

        <meta charset="utf-8">
        <title>Kaleido - Global Elective Blocker</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">

        <!-- CSS -->
       <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">

    </head>

    <body>
        <!-- Header -->
        <div class="container">
            <div class="header row">
                <div class="span12">
                    <div class="navbar">
                        <div class="navbar-inner">
                            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </a>
                            <h1>
                                <a class="brand" href="index.php">Kaleido - a super cool design agency...</a>
                            </h1>
                            <div class="nav-collapse collapse">
                                <ul class="nav pull-right">
                                    <li  class="current-page">
                                        <a href="index.php"><i class="icon-home"></i><br />Home</a>
                                    </li>
                                    
                                    <li>
                                        <a href="about.html"><i class="icon-user"></i><br />About</a>
                                    </li>
                                    <li >
                                        <a href="contact.html"><i class="icon-envelope-alt"></i><br />Contact</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Title -->
        <div class="page-title">
            <div class="container">
                <div class="row">
                    <div class="span12">
                       
                        <h2>Global Elective Blocker</h2></br>
                       
                    </div>
                </div>
            </div>
        </div>

        <!-- About Us Text -->
        <div class="about-us container">
            <div class="row">
                <div id="area" style=" padding-top:10px;margin:auto;width:800px; text-align:center; vertical-align:bottom;">
                
                
                
     <?php
	session_start();
	require_once('connect.php');
	/* signup */
	if(isset($_REQUEST['id']))
	{
		$query="UPDATE `user` SET `validate`=1 where `id`='$_REQUEST[id]'";	
		$mysqli->query($query);
		echo "<p style='color:#3087a9'>Validated</p>";
	}
	else if(isset($_REQUEST['name'])&&($_REQUEST['name']!=''))
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
	else if(isset($_REQUEST['username'])&&($_REQUEST['username']!=''))
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
			echo ($result)?"<p style='color:#3087a9'>Registered</p>":"<p style='color:#3087a9'>Error!</p>";
		}
		else
		{
			echo "<p style='color:#3087a9'>Sorry. You can register for only 2 subjects.</p>";
		}
	}
?>


<?php
	$token=explode('_',$_COOKIE['token']);
	echo "<a style='color:#3087a9' href='logout.php' style='float:right'>Logout</a>";
	if($token[0]=='fac')
	{
		echo "<h3>Subjects</h3>";
		$query="SELECT * FROM `subject` WHERE `fac_id`=$token[1]";
		$result=$mysqli->query($query);
		$sub="<table align='center'>";
		$sub.="<tr style='color:#3087a9'><th>Name</th><th>Slot</th><th>Cut off</th><th>Max</th></tr>";
		while($row=$result->fetch_assoc())
		{
			$sub.="<tr><td>".$row['name']."</td><td>".$row['slot']."</td><td>".$row['cut_off']."</td><td>".$row['max']."</td><td><span class='reg_form'><form action='home.php' method='post'><input type='text' style='margin-right:5px;' name='rollno' placeholder='Roll Number'/><input type='hidden' name='sub_id' value='$row[id]'/><span class='loginbutton'><input type='submit'/></span></form></span></td></tr>";
			$q="SELECT `id` FROM `reg` WHERE `sub_id`='$row[id]'";
			$r=$mysqli->query($q);
			$sub.="<tr><td><ul>";
			while($rw=$r->fetch_assoc())
			{
				$sub.="<li>".$rw['id']."</li>";
			}
			$sub.="</ul></td></tr>";
		}
		$sub.="</table>";
		echo $sub;		
		$add="<label for='plus'><p>Add</p><img src='img/addbtn.jpg' alt=''/></label>";
		$add.="<input type='checkbox' id='plus'/>";
		$add.="<div id='add'>";
		$add.="<h3>Add Subject</h3>";							
		$add.="<form action='home.php' method='post'>";
		$add.="<input type='text' name='sub' placeholder='Subject Name'/>";
		$add.="<input type='text' name='slot' placeholder='Slot'/>";
		$add.="<input type='text' name='cutoff' placeholder='Cut Off'/>";
		$add.="<input type='text' name='max' placeholder='Max number'/>";
		$add.="<span class='loginbutton'><input type='submit'/></span>";
		$add.="</form>";
		$add.="</div>";
		echo $add;
	}
	else if($token[0]=='adm')
	{
		echo "<h3>Faculties</h3>";
		$query="SELECT `name`,`id` FROM `faculty` WHERE `id` IN (SELECT `id` FROM `user` WHERE `validate`=0)";
		$result=$mysqli->query($query);
        $sub="<table>";
        $sub.="<tr><th>waiting to be validated</th></tr><tr><th>faculty name</th></tr>";
        while($row=$result->fetch_assoc())
        {
                $sub.="<tr><form action='home.php' method='post'><td>".$row['name']."</td><td><input type='hidden' value='$row[id]' name='id'/><input type='submit' value='validate'></td></form></tr>";
        }
        $sub.="</table>";
		echo $sub;
	}
	else if($token[0]=='std')
	{
		$std="<h3>List of registered subjects</h3>";		
		$query="SELECT * FROM `subject` WHERE `id` IN (SELECT `sub_id` FROM `reg` WHERE `id`='$token[1]')";
		$result=$mysqli->query($query);
		$std.="<ul>";
		while($row=$result->fetch_assoc())
		{
			$std.="<li>".$row['name']."</li>";
		}
		$std.="</ul>";
		$std.="<h3>List of available subjects</h3>";
		$query="SELECT * FROM `subject` WHERE `id` NOT IN (SELECT `sub_id` FROM `reg` WHERE `id`='$token[1]')";
		$result=$mysqli->query($query);
		$std.="<ul>";
		while($row=$result->fetch_assoc())
		{
			$std.="<li>".$row['name']."</li>";
		}
		$std.="</ul>";
		echo $std;
	}
?>
                 </div>
                 
            </div>
        </div>

       	

        <!-- Footer -->
        <footer>
            <div class="container">
                <div class="row">
                    <div class="widget span6" style="text-align:center">
                       <a href="about.html"> <h4>About Us</h4></a>
                       <p>       Kaleido team members are:</br>
                              	<table id='credits' align="center" cellpadding="">
                                <tr>
                                <td>John P Joseph </td>
                                <td> Aravind Bk </td>
                                </tr>
                                <tr>
                                <td>Arjun Haris </td>
                                <td> Godly T Alias</td>
                                </tr>
                                <tr>
                                <td>Bandna Kumari </td>
                                <td>Harsha Raj </td>
                                </tr>
                                </table> 
                         </p>
                       
                       
                    </div>
                    
                    <div class="widget span6"  style="text-align:center">
                       <a href="contact.html"> <h4>Contact Us</h4></a>
                       
                        <p><i class="icon-phone"></i> Phone: +91 8129038009</p>
                       
                    </div>
                    
                </div>
                <div class="footer-border"></div>
                <div class="row" style="margin-top:20px">
                   
                        <p>Copyright 2013 Kaleido - All rights reserved.</p>
                   
                    
                </div>
            </div>
        </footer>



    </body>

</html>
	
=======
<?php
	session_start();
	require_once('connect.php');
	/* signup */
	if(isset($_REQUEST['id']))
	{
		$query="UPDATE `user` SET `validate`=1 where `id`='$_REQUEST[id]'";	
		$mysqli->query($query);
		echo "validated";
	}
	else if(isset($_REQUEST['name'])&&($_REQUEST['name']!=''))
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
	else if(isset($_REQUEST['username'])&&($_REQUEST['username']!=''))
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
	echo "<a href='logout.php' style='float:right'>logout</a>";
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
			$q="SELECT `id` FROM `reg` WHERE `sub_id`='$row[id]'";
			$r=$mysqli->query($q);
			$sub.="<tr><td><ul>";
			while($rw=$r->fetch_assoc())
			{
				$sub.="<li>".$rw['id']."</li>";
			}
			$sub.="</ul></td></tr>";
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
	else if($token[0]=='adm')
	{
		echo "<h3>Faculties</h3>";
		$query="SELECT `name`,`id` FROM `faculty` WHERE `id` IN (SELECT `id` FROM `user` WHERE `validate`=0)";
		$result=$mysqli->query($query);
		$sub="<table>";
		$sub.="<tr><th>waiting to be validated</th></tr><tr><th>faculty name</th></tr>";
		while($row=$result->fetch_assoc())
		{
			$sub.="<tr><form action='home.php' method='post'><td>".$row['name']."</td><td><input type='hidden' value='$row[id]' name='id'/><input type='submit' value='validate'></td></form></tr>";
		}
		$sub.="</table>";
		echo $sub;
	}
	else if($token[0]=='std')
	{
		$std="<h3>List of registered subjects</h3>";		
		$query="SELECT * FROM `subject` WHERE `id` IN (SELECT `sub_id` FROM `reg` WHERE `id`='$token[1]')";
		$result=$mysqli->query($query);
		$std.="<ul>";
		while($row=$result->fetch_assoc())
		{
			$std.="<li>".$row['name']."</li>";
		}
		$std.="</ul>";
		$std.="<h3>List of available subjects</h3>";
		$query="SELECT * FROM `subject` WHERE `id` NOT IN (SELECT `sub_id` FROM `reg` WHERE `id`='$token[1]')";
		$result=$mysqli->query($query);
		$std.="<ul>";
		while($row=$result->fetch_assoc())
		{
			$std.="<li>".$row['name']."</li>";
		}
		$std.="</ul>";
		echo $std;
	}
?>
	
</body>
</html>

