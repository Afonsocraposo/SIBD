<?php
include("database.php");
$db = new Database();
$conn = $db->connect();
?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>SIBD</title>
</head>

<body>
	<form action="clients.php" method="get">
		<input name="search" type="text" placeholder="VAT, name or addres">
		<input type="submit" value="Search">
		<input type="radio" name="search_type" value="all" checked="checked">All
		<input type="radio" name="search_type" value="VAT">VAT
		<input type="radio" name="search_type" value="name">Name
		<input type="radio" name="search_type" value="address">Address
	</form>
	<form action="newClient.php">
		<input type="submit" value="New Client">
	</form>

	<?php
	$value = isset($_GET['search']) ? $conn->real_escape_string(htmlspecialchars($_GET['search'])) : '';
	$field = $conn->real_escape_string($_GET['search_type']);

	if (!empty($value)) {

		switch ($field) {
			case "VAT":
				$query = "SELECT * FROM client
			WHERE (`VAT` LIKE '" . $value . "%')";
				break;
			case "name":
				$query = "SELECT * FROM client
			WHERE (`name` LIKE '%" . $value . "%')";
				break;
			case "address":
				$query = "SELECT * FROM client
			WHERE (`street` LIKE '%" . $value . "%') or (`zip` LIKE '" . $value . "%') or (`city` LIKE '%" . $value . "%')";
				break;
			default:
				$query = "SELECT * FROM client
			WHERE (`VAT` LIKE '" . $value . "%') OR (`name` LIKE '%" . $value . "%') OR (`street` LIKE '%" . $value . "%') or (`zip` LIKE '" . $value . "%') or (`city` LIKE '%" . $value . "%')";
				break;
		}
	} else {
		$query = "SELECT * FROM client
			WHERE (`VAT` LIKE '" . $value . "%') OR (`name` LIKE '%" . $value . "%') OR (`street` LIKE '%" . $value . "%') or (`zip` LIKE '" . $value . "%') or (`city` LIKE '%" . $value . "%')";
	}

	$raw_results = $conn->query($query) or die(mysqli_query_error());


	if ($raw_results && $raw_results->num_rows > 0) {

		echo ("<table border=\"1\">\n");
		echo ("<tr><td>VAT</td><td>Name</td><td>Birth Date</td><td>Addres</td><td>Gender</td><td>Age</td></tr>\n");
		while ($results = $raw_results->fetch_array()) {
			echo "<tr onclick=\" location.href = '" . $db->url() . "client.php?VAT=" . $results['VAT'] . "';\"><td>" . $results['VAT'] . "</td><td>" . $results['name'] . "</td><td>" . $results['birth_date'] . "</td><td>" . $results['street'] . ", " . $results['zip'] . ", " . $results['city'] . "</td><td>" . $results['gender'] . "</td>" . "<td>" . $results['age'] . "</td></tr>\n";
		}
		echo ("</table>\n");
	} else {
		echo "No results";
	}




	$conn->close();
	?>

</body>

</html>