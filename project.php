<?php

$serverName     = "sql.cs.oberlin.edu";
$dbName         = "aotoole";
/* $user                = "rjhtest"; */

$user           = "aotoole";
$pw             = "Oberlin@123";

function PrintPage($body, $year) {
	print("<!DOCTYPE html>\n");
	print("<html>\n<head>\n<title>This is the AMAM handler!</title>\n");
	print("</head>\n<body>\n");
	print("<h1>Objects from the year $year</h1>\n");
	print("<div class='formOutput'>$body\n</div>\n");
	print("</body>\n</html>\n");
}


try {

	// $department = $_POST['department'];
	
	$year = $_POST['year'];
	
	$body = "";
	
	$conn = new PDO("mysql:host=$serverName;dbname=$dbName",
                  $user, $pw);
	
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	

	$stmt = $conn->prepare(
		'SELECT title, artist, exact AS date, description
		FROM object 
			LEFT JOIN date 
				ON object.dateID = date.dateID 
			LEFT JOIN description 
				ON object.descriptionID = description.descriptionID 
		WHERE :year BETWEEN date.min AND date.max');



  	$stmt->execute( array(':year' => $year) );

  	foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $key =>$val ) {
		$body .= "<p>" . "Title: " . $val['title'] . "<br>" .
			"Artist: " . $val['artist'] . "<br>" . 
			"Date: " . $val['date'] . "<br>" . 
			"Description: " . $val['description'] .
			"</p>\n";
  }

	PrintPage($body, $year);

}

catch(PDOException $e) {
  PrintPage("Connection failed: " . $e->getMessage(), "Unknown", "");
}



?>
