<?php

$serverName     = "sql.cs.oberlin.edu";
$dbName         = "aotoole";
/* $user                = "rjhtest"; */

$user           = "aotoole";
$pw             = "Oberlin@123";


function PrintPage($body, $year, $department) {
	print("<!DOCTYPE html>\n");
	print("<html>\n<head>\n<title>This is the AMAM handler!</title>\n");
	print("</head>\n<body>\n");
	print("<h1>Objects from the year $year</h1>\n");
	print("<h2>From department $department</h2>\n");
	print("<div class='formOutput'>$body\n</div>\n");
	print("</body>\n</html>\n");
}


try {

	$department = $_POST['department'];
	
	$year = $_POST['year'];

	$culture = $_POST['culture'];
	$period = $_POST['period'];

	$length = $_POST['length'];
	$width = $_POST['width'];
	$height = $_POST['height'];
	
	$body = "";
	
	$conn = new PDO("mysql:host=$serverName;dbname=$dbName",
                  $user, $pw);
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$stmt = $conn->prepare("SELECT DISTINCT department FROM culture");
	$stmt->execute();

	$stmt = $conn->prepare(
		'SELECT title, artist, exact AS date, description, department
		FROM object 
			LEFT JOIN date 
				ON object.dateID = date.dateID 
			LEFT JOIN description 
				ON object.descriptionID = description.descriptionID
			LEFT JOIN culture
				ON object.cultureID = culture.cultureID
		WHERE culture.department = :department AND :year BETWEEN date.min AND date.max');



  	$stmt->execute( array(':department' => $department, ':year' => $year) );

  	foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $key =>$val ) {
		$body .= "<p>" . "Title: " . $val['title'] . "<br>" .
			"Artist: " . $val['artist'] . "<br>" . 
			"Date: " . $val['date'] . "<br>" . 
			"Description: " . $val['description'] . "<br>" .
			"Department: " . $val['department'] .
			"</p>\n";
  }

	PrintPage($body, $year, $department);

}

catch(PDOException $e) {
  PrintPage("Connection failed: " . $e->getMessage(), "Unknown", "");
}



?>