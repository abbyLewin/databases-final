<!-- <!DOCTYPE html>
<html>
<head>
<div>
<form action="project.php" method="POST">
		<div class="inputGroup">
                <label for='artist'>Search Artist:</label>
                <input type="text" name ="artist" placeholder="Andy Warhol..." value="<?php echo isset($_POST['artist']) ? $_POST['artist'] : ''; ?>">
        </div>

		<div class='inputGroup'>
                <input type='submit' value="Search">
        </div>
</div>
</form>
</head>
</html> -->

<?php

$serverName     = "sql.cs.oberlin.edu";
$dbName         = "aotoole";

$user           = "aotoole";
$pw             = "Oberlin@123";

function PrintPage($body, $year, $department, $artist) {
	print("<!DOCTYPE html>\n");
	print("<html>\n<head>\n<title>This is the AMAM handler!</title>\n");
	print("</head>\n<body>\n");
	if ($year){
		print("<h1>Objects from the year $year</h1>\n");
	}
	if ($artist) {
		print("<h2>Showing results for artist: $artist</h2>\n");
	}
	if ($department) {
		print("<h2>From department $department</h2>\n");
	}
	print("<div class='formOutput'>$body\n</div>\n");
	print("</body>\n</html>\n");
}


try {

	$department = $_POST['department'];
	
	$year = $_POST['year'];

	$artist = $_POST['artist'];

	$culture = $_POST['culture'];
	$period = $_POST['period'];

	$length = $_POST['length'];
	$width = $_POST['width'];
	$height = $_POST['height'];
	
	$body = "";
	
	$conn = new PDO("mysql:host=$serverName;dbname=$dbName",
                  $user, $pw);
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	$query = 'SELECT title, artist, exact AS date, description, department
              FROM object
              LEFT JOIN date ON object.dateID = date.dateID
              LEFT JOIN description ON object.descriptionID = description.descriptionID
              LEFT JOIN culture ON object.cultureID = culture.cultureID
              WHERE 1';

	if ($department) {
		$query .= ' AND culture.department = :department';
	}

	if ($year) {
		$query .= ' AND :year BETWEEN date.min AND date.max';
	}

	if ($artist) {
		$query .= ' AND object.artist LIKE :artist';
	}


	$stmt = $conn->prepare($query);

	if ($department) {
		$stmt->bindParam(':department', $department, PDO::PARAM_STR);
	}

	if ($year) {
		$stmt->bindParam(':year', $year, PDO::PARAM_INT);
	}
	if ($artist) {
		$stmt->bindParam(':artist', $artist, PDO::PARAM_STR);
	}
	$stmt->bindParam(':department', $department, PDO::PARAM_STR);

	
	$stmt->execute();

	
	foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
		$body .= "<p>Title: " . $row['title'] . "<br>" .
			"Artist: " . $row['artist'] . "<br>" .
			"Date: " . $row['date'] . "<br>" .
			"Description: " . $row['description'] . "<br>" .
			"Department: " . $row['department'] . "</p>\n";
	}
	

	PrintPage($body, $year, $department, $artist);

}

catch(PDOException $e) {
  PrintPage("Connection failed: " . $e->getMessage(), "Unknown", "");
}



?>