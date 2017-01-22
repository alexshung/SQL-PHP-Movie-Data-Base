<!DOCTYPE html>
<html>
<head>
<style>

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 25%;
    background-color: #f1f1f1;
    position: fixed;
    height: 100%;
    overflow: auto;
}

	li a {
	    display: block;
	    color: #000;
	    padding: 8px 16px;
	    text-decoration: none;
	}

	/* Change the link color on hover */
	li a:hover {
	    background-color: #555;
	    color: white;
	}
</style>
</head>
<body>

	<ul>
		<li> <a href="AddDirectorActor.php"> Add Director/Actor </a> </li>
		<li> <a href="AddMovie.php"> Add Movie </a> </li>
		<li> <a href="AddMovieActorRelation.php"> Add Movie Actor Relation </a> </li>
		<li> <a href="AddMovieDirectorRelation.php"> Add Movie Director Relation </a> </li>
		<li> <a href="ShowMovie.php?type=Movie"> Show All Movies </a> </li>
		<li> <a href="ShowMovie.php?type=Actor"> Show All Actors </a> </li>
		<li> <a href="search.php"> Search </a> </li>
	</ul>
<div style= "margin-left:25%;padding:1px 16px;height:1000px;">

<h1>Add Movie Director Relation Information</h1>

<form method="POST">


<?php
	$movieQuery = 'SELECT id, title, year FROM Movie ORDER BY title';
	$db = new mysqli('localhost', 'cs143', '', 'CS143');
	if($db->connect_errno > 0){
		die('Unable to connect to database [' .$db->connect_error . ']');
	}
	$rs = $db->query($movieQuery);
	
	//Movie title
	echo "Movie Title: <br/><select name='mid'>";
	while($row = $rs->fetch_assoc()){
		$name = $row['title'] . ' ('. $row['year'] . ')';
		$id = $row['id'];
		echo "<option value=$id> $name </option>";
	}
	echo "</select><br/><br/>";
	
	//Actor
	echo "Director: <br/><select name='did'>";
	$actorQuery = 'SELECT id, first, last, dob FROM Director ORDER BY first';
	$rs = $db->query($actorQuery);
	while($row = $rs->fetch_assoc()){
		$name = $row['first'] . " " . $row['last'] . ' (' . $row['dob'] . ')';
		$id = $row['id'];
		echo "<option value=$id> $name </option>";
	}
	echo "</select><br/><br/>";
?>
	<input type="submit" value="Add Relation">
</form>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$mid = $_POST['mid'];
		$did = $_POST['did'];

		$dmlStatement = "INSERT INTO MovieDirector (mid, did) VALUES ($mid, $did)";
		$rs = $db->query($dmlStatement);
		if (!$rs ){ 
		    $errmsg = $db->error;
			print "Insert failed: $errmsg <br />";
		    exit(1);
		}
		else{
			echo "Successfully inserted relation.";
		}
		
	}
?>
</div>
</body>
</html>