<!DOCTYPE html>
<?php
$mysqli = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], 10091);
$tranche_id = $_GET["tranche_id"];
$tranche_id_select = $mysqli->query("SELECT * FROM db_tranches where tranche_id = $tranche_id");
$tranche = $tranche_id_select->fetch_array();

$app_status_select = $mysqli->query("SELECT status_colour FROM db_appstatus WHERE db_appstatus.status_id IN (SELECT status_id FROM db_apps WHERE app_id IN (SELECT app_id FROM db_tranchemapping WHERE db_tranchemapping.tranche_id = $tranche_id))");
$tranche_status_colour = "#25CF3A";

function number_of_working_days($from, $to) {
    $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)
    $holidayDays = ['*-12-25', '*-01-01', '2018-01-26','2018-03-12','2018-03-30','2018-04-02','2018-04-25','2018-06-11','2018-09-28','2018-11-06','2018-12-26']; # variable and fixed holidays

    $from = new DateTime($from);
    $to = new DateTime($to);
    $to->modify('+1 day');
    $interval = new DateInterval('P1D');
    $periods = new DatePeriod($from, $interval, $to);

    $days = 0;
    foreach ($periods as $period) {
        if (!in_array($period->format('N'), $workingDays)) continue;
        if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
        if (in_array($period->format('*-m-d'), $holidayDays)) continue;
        $days++;
    }
    return $days;
}



while($row = $app_status_select->fetch_array())  
{
	if ($tranche_status_colour == "#25CF3A")
	{
		$tranche_status_colour = $row['status_colour'];
	}
	else
	{
		if ($row['status_colour'] == "Red")
		{
			$tranche_status_colour = $row['status_colour'];
		}
		else
		{
			if ($row['status_colour'] == "Orange")
			{
				if ($tranche_status_colour != "Red")
				{
					$tranche_status_colour = "Orange";
				}
			}
			else
			{
				if ($tranche_status_colour != "Red")
				{
					if ($tranche_status_colour != "Orange")
					{
						$tranche_status_colour = "Black";
					}
				}
			}
		}
	}
}
$phpdate = strtotime($tranche['start_date']);
$start_date = date( 'd-M-Y', $phpdate );
$start_date2 = date( 'Y-m-d', $phpdate );
$phpdate = strtotime($tranche['end_date']);
$end_date = date( 'd-M-Y', $phpdate );
$end_date2 = date( 'Y-m-d', $phpdate );

$app_count_select = $mysqli->query("select * from db_tranchemapping where tranche_id = $tranche_id");
$app_count = mysqli_num_rows($app_count_select);

$user_count_select = $mysqli->query("select * from db_users where target_tranche = $tranche_id");
$user_count = mysqli_num_rows($user_count_select);

$workingdays = number_of_working_days($start_date2, $end_date2);
$deployments_per_day = round($user_count/$workingdays);

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
		<h1>Deployment Readiness Engine: View Tranche</h1>
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
					<circle cx="10" cy="10" r="10" fill="<?php echo $tranche_status_colour ?>" />
				</svg>
				<h1><?php echo $tranche['tranche_name'];?></h1>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="<?php echo $tranche_status_colour ?>" />
				</svg>
            </div>
			
			<div class="form-row">
                <label>
                    <spanleft>Dates</spanleft>
					<spanright><?php echo $start_date . " to " . $end_date; ?></spanright>
                </label>
            </div>
			<div class="form-row">
                <label>
                    <spanleft>Working Days</spanleft>
					<spanright><?php echo $workingdays; ?></spanright>
                </label>
            </div>
			<div class="form-row">
                <label>
                    <spanleft>Total Apps</spanleft>
					<spanright><?php echo $app_count; ?></spanright>
                </label>
            </div>
			<div class="form-row">
                <label>
                    <spanleft>Total Users</spanleft>
					<spanright><?php echo $user_count; ?></spanright>
                </label>
            </div>
			<div class="form-row">
                <label>
                    <spanleft>Deployments per Day</spanleft>
					<spanright><?php echo $deployments_per_day; ?></spanright>
                </label>
            </div>
			<BR><BR>
			
			<div class="form-title-row">
				<h1>Layer 1 Apps</h1>
            </div>

			<?php
			$app_id_select = $mysqli->query("SELECT * FROM db_apps WHERE app_layer = 1");
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
				<h1>Layer 2 Apps</h1>
            </div>

			<?php
			$app_id_select = $mysqli->query("SELECT * FROM db_apps WHERE app_layer = 2");
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
				<h1>Layer 3 Apps</h1>
            </div>

			<?php
			$app_id_select = $mysqli->query("SELECT * FROM db_apps WHERE app_layer = 3 AND app_id IN (SELECT app_id FROM db_tranchemapping WHERE tranche_id = $tranche_id)");
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
				<h1>Layer 4 Apps</h1>
            </div>

			<?php
			$app_id_select = $mysqli->query("SELECT * FROM db_apps WHERE app_layer = 4 AND app_id IN (SELECT app_id FROM db_tranchemapping WHERE tranche_id = $tranche_id)");
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
			$user_select = $mysqli->query("SELECT * FROM db_users where target_tranche = $tranche_id ORDER BY surname, first_name");
		
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
