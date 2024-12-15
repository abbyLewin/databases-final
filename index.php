<?php

$serverName     = "sql.cs.oberlin.edu";
$dbName         = "aotoole";
/* $user                = "rjhtest"; */

$user           = "aotoole";
$pw             = "Oberlin@123";

$conn = new PDO("mysql:host=$serverName;dbname=$dbName",
                  $user, $pw);
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$stmt = $conn->prepare("SELECT DISTINCT department FROM culture");
?>

<!DOCTYPE html>
<html>
<head>
<title>Allen Memorial Art Museum</title>

<style>
.formLabel
{
        font-size: 12;
        font-weight: bold;
}

#oberlinLogo
{
        width: 2cm;
        height: 2cm;
}
</style>
</head>

<body onload=loadDoc()>

<h1>The Allen Memorial Art Museum Database</h1>

<div>
<form action="project.php" method="POST">

        <div class='inputGroup'>
                <label class="formLabel">What year are you interested in?</label>
                <input type="range"  name="year" id="year" min="-2300" max="2024" value="0" 
                oninput="rangeValue.innerText = this.value">
                <p id="rangeValue">0</p>
	</div>

        <div class='inputGroup'>
                <label for='department'>Select Department:</label>
                <select name='department'>
                <?php
                $stmt = $conn->prepare("SELECT DISTINCT department FROM culture");
                $stmt->execute();
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    echo "<option value='" . $row['department'] . "'>" . $row['department'] . "</option>";
                }
                ?>
                </select>
        </div>

        <div class='inputGroup'>
                <input type='submit'></input>
        </div>

</form>
</div>


</body>
</html>
