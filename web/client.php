<?php
$host = "db.ist.utl.pt";
$user = "ist425108";
$pass = "skqy1678";
$name = "ist425108";

$conn = new mysqli($host, $user, $pass, $name);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>SIBD</title>
</head>

<body>
    <?php
    $value_VAT = isset($_GET['VAT']) ? $conn->real_escape_string(htmlspecialchars($_GET['VAT'])) : '';

    if (!empty($value_VAT)) {
        $query = "SELECT * FROM client WHERE VAT=" . $value_VAT;
    }

    $raw_results = $conn->query($query) or die(mysqli_query_error());

    if ($raw_results && $raw_results->num_rows > 0) {
        echo ("<table border=\"1\">\n");
        echo ("<tr><td>VAT</td><td>Name</td><td>Birth Date</td><td>Addres</td><td>Gender</td><td>Age</td></tr>\n");
        while ($results = $raw_results->fetch_array()) {
            echo "<tr><td>" . $results['VAT'] . "</td><td>" . $results['name'] . "</td><td>" . $results['birth_date'] . "</td><td>" . $results['street'] . ", " . $results['zip'] . ", " . $results['city'] . "</td><td>" . $results['gender'] . "</td>" . "<td>" . $results['age'] . "</td></tr>\n";
        }
        echo ("</table>\n");
    }

    $conn->close();
    ?>

</body>

</html>