<!DOCTYPE html>
<?php
$mysqli = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], 10091);
$user_id = $_GET["user_id"];
$user_id_select = $mysqli->query("SELECT * FROM db_users where user_id = $user_id");
$user = $user_id_select->fetch_array();
$location_select = $mysqli->query("SELECT * FROM db_locations ORDER BY location_address");
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
$app_status_select2 = $mysqli->query("SELECT status_id FROM db_apps WHERE app_id IN (SELECT app_id from db_appmapping WHERE user_id = $user_id) OR app_layer = 1 OR app_layer = 2 ORDER BY app_layer,app_name");
while($row = $app_status_select2->fetch_array())  
{
	$temp_status_id = $row['status_id'];
	$get_colour_select = $mysqli->query("SELECT status_colour FROM db_appstatus WHERE status_id = $temp_status_id");
	while($row2 = $get_colour_select->fetch_array())
	{
		if ($row2['status_colour'] == "#25CF3A")
		{
			$user_green_count = $user_green_count + 1;
		}	
	}
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
		<h1>Deployment Readiness Engine: View User</h1>
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
                <svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="<?php echo $user_status_colour ?>" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<h1><?php echo $user['first_name'] . " " . $user['surname']?></h1>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="<?php echo $user_status_colour ?>" />
				</svg>
            </div>
			
			<div class="form-row">
                <label>
                    <spanleft>NAB PID</spanleft>
					<spanright><?php echo $user['nab_username']?></spanright>
                </label>
            </div>
			
			<div class="form-row">
                <label>
                    <spanleft>Apps Ready</spanleft>
					<spanright><?php echo $user_green_count . " of " . $total_apps; ?></spanright>
                </label>
            </div>
			
			<div class="form-row">
                <label>
                    <spanleft>MLC Username</spanleft>
					<spanright><?php echo $user['mlc_username']?></spanright>
                </label>
            </div>

			<div class="form-row">
                <label>
                    <spanleft>Job Title</spanleft>
					<spanright><?php echo $user['job_title']?></spanright>
                </label>
            </div>
						
			<div class="form-row">
                <label>
                    <span>VPN User?</span>
                    <input type="checkbox" name="vpn_user" <?php if ($user['vpn_user'] == true) {echo "checked";} ?>>
                </label>
            </div>
			
			<div class="form-row">
				<label>
                    <span>Location</span>
                    <select name="location_id">
					<? while($row = $location_select->fetch_array())  
					{
						if ($row['location_id'] == $user['location_id']) 
						{
							echo "<option selected value='" . $row['location_id'] . "'>" . $row['location_address'] . "</option>";
						}
						else
						{
							echo "<option value='" . $row['location_id'] . "'>" . $row['location_address'] . "</option>";
						}
					}
					?>
                    </select>
                </label>
            </div>
			
            <div class="form-row">
                <label>
                    <span>Status</span>
                    <select name="status_id">
                    </select>
                </label>
            </div>
			
			<div class="form-row">
                <label>
                    <span>Link to Binary</span>
                    <input type="text" name="link_to_binary" value="">
                </label>
            </div>

            <div class="form-row">
                <label>
                    <span>Checkbox</span>
                    <input type="checkbox" name="checkbox" checked>
                </label>
            </div>
			
	        <div class="form-row">
                <button type="submit">Update</button>
            </div>
			<BR><BR>
			<h1>Applications</h1>
			<BR><BR><BR><BR>
			
			<?
			$app_id_select = $mysqli->query("SELECT * FROM db_apps WHERE app_id IN (SELECT app_id from db_appmapping WHERE user_id = $user_id) OR app_layer = 1 OR app_layer = 2 ORDER BY app_layer,app_name");
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


			
        </form>

    </div>

</body>

</html>
