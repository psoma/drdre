<!DOCTYPE html>
<?php
$mysqli = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], 10091);
$search_string = $_GET["search"];
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
		<h1>Deployment Readiness Engine: Search Results</h1>
		<img align="right" src="images/dre.png">
    </header>

    <ul>
        <li><a href="search.php">Search</a></li>
        <li><a href="viewallapps.php">All Apps</a></li>
        <li><a href="viewallusers.php">All Users</a></li>
        <li><a href="viewalltranches.php">All Tranches</a></li>
    </ul>


    <div class="main-content">

        <!-- You only need this form and the form-basic.css -->

        <form class="form-basic" method="post" action="#">

            <div class="form-title-row">
				<h1>Applications</h1>
            </div>

            <?php
			$app_id_select = $mysqli->query("SELECT * FROM db_apps where (app_name LIKE '%$search_string%') OR (description LIKE '%$search_string%')");
			
			
			while($app = $app_id_select->fetch_array())
			{
				$app_status_id = $app['status_id'];
				$app_status_query = $mysqli->query("SELECT * FROM db_appstatus WHERE status_id = $app_status_id");
				$app_status_array = $app_status_query->fetch_array();
				$app_status_colour = $app_status_array['status_colour'];
				$app_status_description = $app_status_array['status_description'];
				echo "<div class='form-row'>";
				echo "<label>";
				echo "<spanleft><svg width=10 height=10 xmlns='http://www.w3.org/2000/svg'><circle cx='5' cy='5' r='5' fill='" . $app_status_colour . "'/></svg></spanleft>";
				echo "<spanright><A href='viewapp.php?app_id=" . $app['app_id'] . "'>" . $app['app_name'] . "</A></spanright>";
				echo "</label>";
				echo "</div>";
			}
			?>
			<BR><BR>
            <div class="form-title-row">
				<h1>Users</h1>
            </div>
			
			<?php
			$user_select = $mysqli->query("SELECT * FROM db_users where (first_name LIKE '%$search_string%') OR (surname LIKE '%$search_string%') OR (nab_username LIKE '%$search_string%') ORDER BY surname, first_name");
		
			while($user = $user_select->fetch_array())
			{   
				$user_id = $user['user_id'];
				$app_status_select = $mysqli->query("SELECT status_colour FROM db_appstatus WHERE db_appstatus.status_id IN (SELECT status_id FROM db_apps WHERE app_id IN (SELECT app_id FROM db_appmapping WHERE db_appmapping.user_id = $user_id)) OR status_id IN (SELECT status_id FROM db_apps WHERE app_layer = 1 OR app_layer = 2)");
				$user_status_colour = "#25CF3A";
				while($row = $app_status_select->fetch_array())  
				{
					if ($user_status_colour == "#25CF3A")
					{
						$user_status_colour = $row['status_colour'];
					}
					else
					{
						if ($row['status_colour'] == "Red")
						{
							$user_status_colour = $row['status_colour'];
						}
						else
						{
							if ($row['status_colour'] == "Orange")
							{
								if ($user_status_colour != "Red")
								{
									$user_status_colour = "Orange";
								}
							}
							else
							{
								if ($user_status_colour != "Red")
								{
									if ($user_status_colour != "Orange")
									{
										$user_status_colour = "Black";
									}
								}
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
			?>
			
			
        </form>

    </div>

</body>

</html>
