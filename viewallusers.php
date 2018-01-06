<!DOCTYPE html>
<?php
$mysqli = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], 10091);
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
		<h1>Deployment Readiness Engine: View All Users</h1>
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
					echo "<div class='form-row'>";
					echo "<label>";
					echo "<spanleft>" . $user['nab_username'] . "&nbsp;&nbsp;<svg width=10 height=10 xmlns='http://www.w3.org/2000/svg'><circle cx='5' cy='5' r='5' fill='" . $user_status_colour . "'/></svg></spanleft>";
					echo "<spanright><A href='viewuser.php?user_id=" . $user['user_id'] . "'>" . $user['first_name'] . " " . $user['surname'] . "</A></spanright>";
					echo "</label>";
					echo "</div>";
				}
			}
			?>
        </form>

    </div>

</body>

</html>
