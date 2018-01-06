<!DOCTYPE html>
<?php
$mysqli = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], 10091);

if(isset($_GET['number_of_apps'])) 
{
    $number_of_apps = $_GET['number_of_apps'];
}
else
{
	$number_of_apps = 0;
}

if(isset($_GET['fewer'])) 
{
    $fewer = $_GET['fewer'];
	if (($fewer != 0) && ($fewer != 1))
	{
		$fewer = 0;
	}
}
else
{
	$fewer = 0;
}



?>

<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Deployment Readiness Engine</title>

	<link rel="stylesheet" href="assets/demo.css">
	<link rel="stylesheet" href="assets/form-basic.css">

</head>


	<header>
		<?php if ($number_of_apps == 0)
		{
			echo "<h1>Deployment Readiness Engine: View Users with All Apps Packaged</h1>";
		}
		else
		{
			if ($fewer == 0)
			{
				echo "<h1>Deployment Readiness Engine: View Users with " . $number_of_apps . " App(s) Remaining to be Packaged</h1>";
			}
			else
			{
				echo "<h1>Deployment Readiness Engine: View Users with " . $number_of_apps . " or Fewer Apps Remaining to be Packaged</h1>";
			}
		}
		?>
		<img align="right" src="images/dre.png">
    </header>

    <ul>
        <li><a href="search.php">Search</a></li>
        <li><a href="viewallapps.php">All Apps</a></li>
        <li><a href="viewallusers.php" class="active">All Users</a></li>
        <li><a href="viewalltranches.php">All Tranches</a></li>
    </ul>


    <div class="main-content">

        <!-- You only need this form and the form-basic.css -->

        <form class="form-basic" method="post" action="#">
            <?php
            $grand_total = 0;
			$letters = range('A', 'Z');
			echo "| ";
			foreach ($letters as $letter)
			{
				echo "<A HREF='#".$letter."'>".$letter."</A> | ";
			}
			echo "<BR><BR><BR>";
            foreach ($letters as $letter)
			{
				echo "<div class='form-title-row'>";
				echo "<A name=". $letter . " ></A>";
				echo "<h1>". $letter ."</h1>";
				echo "</div>";
				$user_select = $mysqli->query("SELECT * FROM db_users WHERE surname LIKE '$letter%' ORDER BY surname, first_name");
				while($user = $user_select->fetch_array())
				{
					$user_id = $user['user_id'];
					$app_status_select = $mysqli->query("SELECT status_colour FROM db_appstatus WHERE db_appstatus.status_id IN (SELECT status_id FROM db_apps WHERE app_id IN (SELECT app_id FROM db_appmapping WHERE db_appmapping.user_id = $user_id)) OR status_id IN (SELECT status_id FROM db_apps WHERE app_layer = 1 OR app_layer = 2)");
					$user_status_colour = "#25CF3A";
					$user_green_count = 0;
					$user_black_count = 0;
					$user_orange_count = 0;
					$user_red_count = 0;

					while($row = $app_status_select->fetch_array())  
					{
						if ($row['status_colour'] == "#25CF3A")
						{
							$user_green_count = $user_green_count + 1;
						}
						else
						{
							if ($row['status_colour'] == "Red")
							{
								$user_red_count = $user_red_count + 1;
							}
							else
							{
								if ($row['status_colour'] == "Orange")
								{
									$user_orange_count = $user_orange_count + 1;
								}
								else
								{
									if ($row['status_colour'] == "Black")
									{
										$user_black_count = $user_black_count + 1;
									}
								}
							}
						}
					}

					if ($user_red_count > 0)
					{
						$user_status_colour = "Red";
					}
					else
					{
						if ($user_orange_count > 0)
						{
							$user_status_colour = "Orange";
						}
						else
						{
							if ($user_black_count > 0)
							{
								if ($user_green_count > 0)
								{
									$user_status_colour = "Orange";
								}
								else
								{
									$user_status_colour = "Black";
								}
							}
						}
					}
					
					$discretionary_app_count_select = $mysqli->query("SELECT app_id FROM db_appmapping WHERE db_appmapping.user_id = $user_id");
					$layer12_app_count_select = $mysqli->query("SELECT status_id FROM db_apps WHERE app_layer = 1 OR app_layer = 2");

					$total_apps = mysqli_num_rows($discretionary_app_count_select) + mysqli_num_rows($layer12_app_count_select);

					$user_green_count = 0;
					$app_status_select2 = $mysqli->query("SELECT status_id FROM db_apps WHERE (app_id IN (SELECT app_id from db_appmapping WHERE user_id = $user_id) OR app_id IN (SELECT app_id FROM db_apps WHERE db_apps.app_layer = 2) OR app_id IN (SELECT app_id FROM db_apps WHERE db_apps.app_layer = 1)) AND status_id = 160");
					$user_green_count = mysqli_num_rows($app_status_select2);
					if ($fewer == 1)
					{
						if (($total_apps - $user_green_count) <= $number_of_apps)
						{					
							$grand_total = $grand_total + 1;
							echo "<div class='form-row'>";
							echo "<label>";
							echo "<spanleft>" . $user['nab_username'] . "&nbsp;&nbsp;<svg width=10 height=10 xmlns='http://www.w3.org/2000/svg'><circle cx='5' cy='5' r='5' fill='" . $user_status_colour . "'/></svg></spanleft>";
							echo "<spanright><A href='viewuser.php?user_id=" . $user['user_id'] . "'>" . $user['first_name'] . " " . $user['surname'] . "</A></spanright>";
							echo "</label>";
							echo "</div>";
						}
					}
					else
					{
						if (($total_apps - $user_green_count) == $number_of_apps)
						{					
							$grand_total = $grand_total + 1;
							echo "<div class='form-row'>";
							echo "<label>";
							echo "<spanleft>" . $user['nab_username'] . "&nbsp;&nbsp;<svg width=10 height=10 xmlns='http://www.w3.org/2000/svg'><circle cx='5' cy='5' r='5' fill='" . $user_status_colour . "'/></svg></spanleft>";
							echo "<spanright><A href='viewuser.php?user_id=" . $user['user_id'] . "'>" . $user['first_name'] . " " . $user['surname'] . "</A></spanright>";
							echo "</label>";
							echo "</div>";
						}
					}
						
				}
				
			}
			?>
			<h1><BR><BR>Total : <?php echo $grand_total ?> Users<BR><BR> 
        </form>

    </div>

</body>

</html>
