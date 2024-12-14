<?php

$serverName     = "sql.cs.oberlin.edu";
$dbName         = "alewin";
/* $user                = "rjhtest"; */

$user           = "alewin";
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

  $year = $_POST['year'];

  //  $year = "1995; delete from students;";

  $body = "<table><tr><th>Department</th><th>Title</th><th>Artist</th></tr>";

  $conn = new PDO("mysql:host=$serverName;dbname=$dbName",
                  $user, $pw);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $conn->prepare('select title, avg(rating) as avg_rating
          from movies natural join ratings natural join users
                WHERE YEAR = :year AND AGE < 30
                group by title');



  $stmt->execute( array(':year' => $year) );

  foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $key =>$val ) {
    $body .= "<tr><td>$key</td><td>" .
                   $val['department'] .
                   "</td><td>" . $val['title'] . "</td></tr>\n";
  }
  $body .= "</table>\n";

  PrintPage($body, $year);

}

catch(PDOException $e) {
  PrintPage("Connection failed: " . $e->getMessage(), "Unknown", "");
}



?>
