<?php
        session_start();
        if(isset($_COOKIE['token']))
        {
                header('location:home.php');
        }
?>

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
    <?php
        require_once('connect.php');
?>

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
                                    <li class="current-page">
                                        <a href="index.php"><i class="icon-home"></i><br />Home</a>
                                    </li>
                                    
                                    <li>
                                        <a href="about.html"><i class="icon-user"></i><br />About</a>
                                    </li>
                                    <li>
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
                <div id="login" style=" padding-top:10px;margin:auto;width:800px;">
                	<h2>LOGIN</h2>
                   <form action='home.php' method='post'>
                    <input type='text' style='height:30px' name='username' placeholder='username'/>
                    <input type='password' style='height:30px' name='password' placeholder='password'/>
                    <label for='check'> <p style='text-decoration:underline;color: #3087a9;'>Not registered yet</p></label>
                    <input type='checkbox' id='check' name='signup' value='signup' />
                    
                    <div id='signup'>
                    <input type='text' style='height:30px' name='name' placeholder='name'/>
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
                    
                    <p class="loginbutton"> 
                        <input type='submit'/>
					</p>
                    
                    
                    </form>	
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
                              	<table align="center" cellpadding="">
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

