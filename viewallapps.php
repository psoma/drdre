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
		<h1>Deployment Readiness Engine: View All Applications</h1>
		<img align="right" src="images/dre.png">
    </header>

    <ul>
        <li><a href="search.php">Search</a></li>
        <li><a href="viewallapps.php" class="active">All Apps</a></li>
        <li><a href="viewallusers.php">All Users</a></li>
        <li><a href="viewalltranches.php">All Tranches</a></li>
    </ul>


    <div class="main-content">

        <!-- You only need this form and the form-basic.css -->

        <form class="form-basic" method="post" action="#">

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
			$app_id_select = $mysqli->query("SELECT * FROM db_apps WHERE app_layer = 3");
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
			$app_id_select = $mysqli->query("SELECT * FROM db_apps WHERE app_layer = 4");
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
