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
		<h1>Deployment Readiness Engine: View All Tranches</h1>
		<img align="right" src="images/dre.png">
    </header>

    <ul>
        <li><a href="search.php">Search</a></li>
        <li><a href="viewallapps.php">All Apps</a></li>
        <li><a href="viewallusers.php">All Users</a></li>
        <li><a href="viewalltranches.php" class="active">All Tranches</a></li>
    </ul>


    <div class="main-content">

        <!-- You only need this form and the form-basic.css -->

        <form class="form-basic" method="post" action="#">

            <div class="form-title-row">
			<h1>Phase 1</h1>
            </div>

			<?php
			$tranche_select = $mysqli->query("SELECT * FROM db_tranches WHERE phase=1");
			while($tranche = $tranche_select->fetch_array())
			{
				$phpdate = strtotime($tranche['start_date']);
				$start_date = date( 'd-M-Y', $phpdate );
				$phpdate = strtotime($tranche['end_date']);
				$end_date = date( 'd-M-Y', $phpdate );
				
				echo "<div class='form-row'>";
				echo "<label>";
				echo "<spanfull><A href='viewtranche.php?tranche_id=" . $tranche['tranche_id'] . "'><B>" . $tranche['tranche_name'] . ":</B> " . $start_date . " to " . $end_date . "</A></spanfull>";
				echo "</label>";
				echo "</div>";
			}
			?>
			<BR><BR>
			<div class="form-title-row">
			<h1>Phase 2</h1>
            </div>

			<?php
			$tranche_select = $mysqli->query("SELECT * FROM db_tranches WHERE phase=2");
			while($tranche = $tranche_select->fetch_array())
			{
				$phpdate = strtotime($tranche['start_date']);
				$start_date = date( 'd-M-Y', $phpdate );
				$phpdate = strtotime($tranche['end_date']);
				$end_date = date( 'd-M-Y', $phpdate );
				
				echo "<div class='form-row'>";
				echo "<label>";
				echo "<spanfull><A href='viewtranche.php?tranche_id=" . $tranche['tranche_id'] . "'><B>" . $tranche['tranche_name'] . ":</B> " . $start_date . " to " . $end_date . "</A></spanfull>";
				echo "</label>";
				echo "</div>";
			}
			?>		
        </form>

    </div>

</body>

</html>
