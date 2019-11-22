<?php
include("database.php");
$db = new Database();
$dbh = $db->connect();
?>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>SIBD</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<form action="clients.php" method="get">
		<input name="search" type="text" placeholder="VAT, name or addres">
		<input type="submit" value="Search">
		<input type="radio" name="search_type" value="all" checked="checked" id="all"><label for="all"> All </label>
		<input type="radio" name="search_type" value="VAT" id="VAT"><label for="VAT"> VAT </label>
		<input type="radio" name="search_type" value="name" id="name"><label for="name"> Name </label>
		<input type="radio" name="search_type" value="address" id="address"><label for="address"> Address </label>
	</form>
	<br>
	<form action="newClient.php">
		<input type="submit" value="New Client">
	</form>
	<br>
	<?php
	$field = $_GET['search_type'];
	$value = $_GET['search'];

	if (!empty($value)) {

		switch ($field) {
			case "VAT":
				$stmt = $dbh->prepare("SELECT * FROM client WHERE (VAT LIKE CONCAT(?,'%')) ORDER BY VAT ASC") or die($dbh->error);
				$stmt->bindParam(1, $value);
				break;
			case "name":
				$stmt = $dbh->prepare("SELECT * FROM client WHERE (name LIKE CONCAT('%',?,'%')) ORDER BY name ASC") or die($dbh->error);
				$stmt->bindParam(1, $value);
				break;
			case "address":
				$stmt = $dbh->prepare("SELECT * FROM client WHERE (street LIKE CONCAT('%',?,'%')) OR (zip LIKE CONCAT(?,'%')) OR (city LIKE CONCAT('%',?,'%')) ORDER BY street ASC") or die($dbh->error);
				$stmt->bindParam(1, $value);
				$stmt->bindParam(2, $value);
				$stmt->bindParam(3, $value);
				break;
			default:
				$stmt = $dbh->prepare("SELECT * FROM client WHERE (VAT LIKE CONCAT(?,'%')) OR (name LIKE CONCAT('%',?,'%')) OR (street LIKE CONCAT('%',?,'%')) OR (zip LIKE CONCAT(?,'%')) OR (city LIKE CONCAT('%',?,'%')) ORDER BY name ASC") or die($dbh->error);
				$stmt->bindParam(1, $value);
				$stmt->bindParam(2, $value);
				$stmt->bindParam(3, $value);
				$stmt->bindParam(4, $value);
				$stmt->bindParam(5, $value);
				break;
		}
	} else {
		$stmt = $dbh->prepare("SELECT * FROM client ORDER BY name ASC") or die($dbh->error);
	}

	if (!$stmt->execute()) {
		print("Something went wrong");
	} else {
		if ($stmt->rowCount() > 0) {
			echo ("<table border=\"1\">\n");
			echo ("<tr class='header'><td>VAT</td><td>Name</td><td>Addres</td><td>Birth Date</td></tr>\n");
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				echo "<tr onclick=\" location.href = '" . $db->url() . "client.php?VAT=" . $row['VAT'] . "';\"><td>" . $row['VAT'] . "</td><td>" . $row['name'] . "</td><td>" . $row['street'] . ", " . $row['zip'] . ", " . $row['city'] . "</td><td>" . $row['birth_date'] . "</td></tr>\n";
			}
			echo ("</table>\n");
		} else {
			echo "No results";
		}
		$stmt = null;
	}
	$dbh = null;
	?>

</body>

</html>