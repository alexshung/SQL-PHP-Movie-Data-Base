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

<?php
	$typeShow = $_GET['type'];
	if($typeShow != 'Movie' && $typeShow != 'Actor')
		echo '<h1> Invalid type found. </h1>';
	else{
		echo "<h1> Show $typeShow </h1>";
		$fields = $typeShow == 'Movie' ? 'id, Title, Year' : 'id, last, first, dob';
		//Check that connecting to TEST database works
		$db = new mysqli('localhost', 'cs143', '', 'CS143');
		if($db->connect_errno > 0){
			die('Unable to connect to database [' .$db->connect_error . ']');
		}
		

		//Get the query
		$queryDirty = "SELECT $fields FROM $typeShow";
		$query = sprintf($queryDirty, $db->real_escape_string($queryDirty));
		
		//Probably need a validation here
		
		//Query the database
		$rs = $db->query($query);

		//Error Handling
		
		if (!$rs ){ 
		    $errmsg = $db->error;
		    print "Query failed: $errmsg <br />";
		    exit(1);
		}
		
		echo "<table border=\"5px solid black\"> <tr>";

		//Do the first row
		$fieldArray = $rs->fetch_fields();
		foreach ($fieldArray as $header) {
			echo "<th> {$header->name} </th>";
		}
		echo "</tr>";
		while($row = $rs->fetch_assoc()) {
			echo "<tr>";
			$row_id = $row['id'];
				foreach($fieldArray as $header){
					$val = $row[$header ->name];

					echo "<td> <a href=' ShowInformation.php?type=$typeShow&id=$row_id'> $val </a> </td>";
				}
		    echo "</tr>";
		}
		echo "</table>";		


	//Using prepare
		/*
		$statement = $db->prepare("SELECT ? FROM ? WHERE ?");
		$statement->bind_param('sdi', $name, $GPA, $age);
		$statement->execute();
		$statement->bind_result($returned_sid, $returned_email);
		while($statement->fetch()){
		    echo $returned_sid . ' ' . $returned_email . '<br />';
		}
		*/

		//Get # of rows
		//$rs->num_rows;

		//Free the result 
		//$rs->free();

		//See the affected rows after a dml
		//$db->affected_rows
	}
?>
</div>
</body>
</html>
