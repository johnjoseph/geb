<?php
session_start();
	require_once('connect.php');
$query="SELECT `id` FROM `user`";
$result=$mysqli->query($query);
while($row=$result->fetch_assoc())
                {
                        if(isset($_POST['$row[id]']))
                        {
                        mysqli->query("update user set validate=1 where id='$row[id]'");
                        echo "validated";
                        }
                }
                ?>
