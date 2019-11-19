<?php
include("database.php");
$db = new Database();
$mysqli = $db->connect();
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
	$field = $_GET['search_type'];
	$value = $_GET['search'];

	$stmt = $mysqli->stmt_init();
	if (!empty($value)) {

		switch ($field) {
			case "VAT":
				$stmt->prepare("SELECT * FROM client WHERE (VAT LIKE CONCAT(?,'%'))") or die($mysqli->error);
				echo $value;
				$stmt->bind_param('s', $value);
				break;
			case "name":
				$stmt->prepare("SELECT * FROM client WHERE (name LIKE CONCAT('%',?,'%'))") or die($mysqli->error);
				$stmt->bind_param('s', $value);
				break;
			case "address":
				$stmt->prepare("SELECT * FROM client WHERE (street LIKE CONCAT('%',?,'%')) OR (zip LIKE CONCAT(?,'%')) OR (city LIKE CONCAT('%',?,'%'))") or die($mysqli->error);
				$stmt->bind_param('sss', $value, $value, $value);
				break;
			default:
				$stmt->prepare("SELECT * FROM client WHERE (VAT LIKE CONCAT(?,'%')) OR (name LIKE CONCAT('%',?,'%')) OR (street LIKE CONCAT('%',?,'%')) OR (zip LIKE CONCAT(?,'%')) OR (city LIKE CONCAT('%',?,'%'))") or die($mysqli->error);
				$stmt->bind_param('sssss', $value, $value, $value, $value, $value);
				break;
		}
	} else {
		$stmt->prepare("SELECT * FROM client") or die($mysqli->error);
	}

	if (!$stmt->execute()) {
		print("Something went wrong");
	} else {
		$result = $stmt->get_result();
		if ($result && $result->num_rows > 0) {
			echo ("<table border=\"1\">\n");
			echo ("<tr><td>VAT</td><td>Name</td><td>Birth Date</td><td>Addres</td><td>Gender</td><td>Age</td></tr>\n");
			while ($row = $result->fetch_array()) {
				echo "<tr onclick=\" location.href = '" . $db->url() . "client.php?VAT=" . $row['VAT'] . "';\"><td>" . $row['VAT'] . "</td><td>" . $row['name'] . "</td><td>" . $row['birth_date'] . "</td><td>" . $row['street'] . ", " . $row['zip'] . ", " . $row['city'] . "</td><td>" . $row['gender'] . "</td>" . "<td>" . $row['age'] . "</td></tr>\n";
			}
			echo ("</table>\n");
		} else {
			echo "No results";
		}
	}



	$mysqli->close();
	?>

</body>

</html>