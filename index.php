<?php

$serverName     = "sql.cs.oberlin.edu";
$dbName         = "aotoole";

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
<link rel="stylesheet" href="style.css">
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

<h3>Leave year at 0 to view all art in the selected department!</h3>
        <div class='inputGroup'>
                <label class="formLabel">What year are you interested in?</label>
                <input type="range"  name="year" id="year" min="-2300" max="2024" value="0" 
                oninput="rangeValue.innerText = this.value">
                <p id="rangeValue">0</p>
	</div>

        <div class='inputGroup'>
                <label for='department'>Select Department:</label>
		<select name='department'>
			<option value="All departments">All departments</option>
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
                <label for='culture'>Select Culture:</label>
                <select name='culture'>
                        <option value="All cultures">All cultures</option>
                <?php
                $stmt = $conn->prepare("SELECT DISTINCT name FROM culture WHERE name NOT LIKE 'NA' AND name NOT LIKE 'unknown'");
                $stmt->execute();
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                }
                ?>
                </select>
	</div>

	<div class='inputGroup'>
                <label for='period'>Select Period/Dynasty:</label>
                <select name='period'>
                        <option value="All periods">All periods</option>
                <?php
                $stmt = $conn->prepare("SELECT DISTINCT period FROM culture WHERE period NOT LIKE 'NA'");
                $stmt->execute();
                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    echo "<option value='" . $row['period'] . "'>" . $row['period'] . "</option>";
                }
                ?>
                </select>
        </div>

        <div class="inputGroup">
                <label for='artist'>Search Artist:</label>
                <input type="text" name ="artist" placeholder="Andy Warhol..." value="<?php echo isset($_POST['artist']) ? $_POST['artist'] : ''; ?>">
	</div>

	<p>Enter Dimensions (optional):</p>

	<div class="inputGroup">
		<label for='length'>Enter Length:</label>
		<input type="number" name="length" min="0" value="<?php echo isset($_POST['length']) ? $_POST['length'] : ''; ?>">
	</div>
	<div class="inputGroup">
                <label for='width'>Enter Width:</label>
                <input type="number" name="width" min="0" value="<?php echo isset($_POST['width']) ? $_POST['width'] : ''; ?>">
	</div>
	<div class="inputGroup">
                <label for='height'>Enter Length:</label>
                <input type="number" name="height" min="0" value="<?php echo isset($_POST['height']) ? $_POST['height'] : ''; ?>">
        </div>

        <div class='inputGroup'>
                <input type='submit'></input>
        </div>

</form>
</div>


</body>
</html>
