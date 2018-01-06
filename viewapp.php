<!DOCTYPE html>
<?php
$mysqli = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], 10091);
$app_id = $_GET["app_id"];
$app_id_select = $mysqli->query("SELECT * FROM db_apps where app_id = $app_id");
$app = $app_id_select->fetch_array();
$app_status_select = $mysqli->query("SELECT * FROM db_appstatus");
$app_status_id = $app['status_id'];
$app_status_colour_query = $mysqli->query("SELECT status_colour FROM db_appstatus WHERE status_id = $app_status_id");
$app_status_colour_array = $app_status_colour_query->fetch_array();
$app_status_colour = $app_status_colour_array['status_colour'];

$app_number_select = $mysqli->query("SELECT * FROM db_appmapping WHERE app_id = $app_id");
$app_number_discretionary = mysqli_num_rows($app_number_select);

$app_number_select = $mysqli->query("SELECT user_id FROM db_users");
$app_number_layer12 = mysqli_num_rows($app_number_select);

if ($app['app_layer'] == 1 || $app['app_layer'] == 2)
{
	$total_installs = $app_number_layer12;
}
else
{
	$total_installs = $app_number_discretionary;
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
		<h1>Deployment Readiness Engine: View Application</h1>
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
					<circle cx="10" cy="10" r="10" fill="<?php echo $app_status_colour ?>" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<h1><?php echo $app['app_name']?></h1>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="white" />
				</svg>
				<svg width=20 height=20 xmlns="http://www.w3.org/2000/svg">
					<circle cx="10" cy="10" r="10" fill="<?php echo $app_status_colour ?>" />
				</svg>
            </div>

            <div class="form-row">
                <label>
                    <span>Description</span>
                    <textarea name="description"><?php echo $app['description']; ?></textarea>
                </label>
            </div>
			
			<div class="form-row">
                <label>
                    <spanleft>Number of Users</spanleft>
                    <spanright><?php echo $total_installs;?></spanright>
                </label>
            </div>			
			
			
			<div class="form-row">
                <label>
                    <span>Vendor</span>
                    <input type="text" name="vendor" value="<?php echo $app['vendor'];?>">
                </label>
            </div>
			
			<div class="form-row">
                <label>
                    <span>Licensing Type</span>
                    <input type="text" name="licensing_type" value="<?php echo $app['licensing_type'];?>">
                </label>
            </div>

			<div class="form-row">
                <label>
                    <span>License Confirmed?</span>
                    <input type="checkbox" name="license_confirmed" <?php if ($app['license_confirmed'] == true) {echo "checked";}?>>
                </label>
            </div>
			
            <div class="form-row">
                <label>
                    <span>Status</span>
                    <select name="status_id">
					<? while($row = $app_status_select->fetch_array())  
					{
						if ($row['status_id'] == $app['status_id']) 
						{
							echo "<option selected value='" . $row['status_id'] . "'>" . $row['status_description'] . "</option>";
						}
						else
						{
							echo "<option value='" . $row['status_id'] . "'>" . $row['status_description'] . "</option>";
						}
					}
					?>
                    </select>
                </label>
            </div>
			
			<div class="form-row">
                <label>
                    <span>Link to Binary</span>
                    <input type="text" name="link_to_binary" value="<?php echo $app['link_to_binary']?>">
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

        </form>

    </div>

</body>

</html>
