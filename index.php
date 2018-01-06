<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Deployment Readiness Engine</title>

	<link rel="stylesheet" href="assets/demo.css">
	<link rel="stylesheet" href="assets/form-search.css">

</head>


	<header>
		<h1>Deployment Readiness Engine: View All Applications</h1>
		<img align="right" src="images/dre.png">
    </header>


    <ul>
        <li><a href="search.php" class="active">Search</a></li>
        <li><a href="viewallapps.php">All Apps</a></li>
        <li><a href="viewallusers.php">All Users</a></li>
        <li><a href="viewalltranches.php">All Tranches</a></li>
    </ul>


    <div class="main-content">

        <!-- You only need this form and the form-search.css -->

        <form class="form-search" method="get" action="searchresults.php">
            <input type="search" name="search" placeholder="I am looking for..">
            <button type="submit">Search</button>
            <i class="fa fa-search"></i>
        </form>

    </div>

</body>

</html>
