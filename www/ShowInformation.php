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
		echo '<h1> Invalid Type Found </h1>';
	else{
		include('Utils.php');
		$id = $_GET['id'];
		echo "<h1> $typeShow Information Page </h1>";
		$fields = $typeShow == 'Movie' ? 'id, Title, Year' : 'id, last, first, sex, dob, dod';
		//Check that connecting to TEST database works
		$db = new mysqli('localhost', 'cs143', '', 'CS143');
		if($db->connect_errno > 0){
			die('Unable to connect to database [' .$db->connect_error . ']');
		}

		//Get the query
		$queryDirty = "SELECT $fields FROM $typeShow WHERE id=$id";
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
		echo "<h2> $typeShow Information is: </h1>";		
		Utils::printTable($rs, 2);

		if($typeShow == 'Actor'){
			//For actors
			echo '<h2> Actor\'s Movies and Roles </h1>';
			$relationQuery = "SELECT role, title FROM MovieActor JOIN Movie ON MovieActor.aid = $id AND MovieActor.mid = Movie.id";
			$rs = $db->query($relationQuery);
			Utils::printLinkTable($rs, 2, 'Actor');
		}
		else{

			//Genres
			echo '<h2> Movie\'s Genre(s) </h1>';
			$relationQuery = "SELECT genre FROM MovieGenre WHERE mid = $id";
			$rs = $db->query($relationQuery);
			Utils::printTable($rs, 1);

			//Directors:
			echo '<h2> Movie\'s Director(s) </h2>';
			$relationQuery = "SELECT first, last, dob FROM Director JOIN MovieDirector ON MovieDirector.mid = $id AND Director.id = MovieDirector.did";
			$rs = $db->query($relationQuery);
			Utils::printTable($rs, 1);

			//Actors in this Movie:
			echo '<h2> Movie\'s Actor(s) </h2>';
			$relationQuery = "SELECT id, first, last, role FROM Actor JOIN MovieActor ON MovieActor.mid = $id AND Actor.id = MovieActor.aid";
			$rs = $db->query($relationQuery);
			Utils::printLinkTable($rs, 1, 'Actor');

			//Comments
			echo '<h2> Reviews </h2>';
			$relationQuery = "SELECT name, comment, rating, time FROM Review WHERE mid = $id";
			$rs = $db->query($relationQuery);
			$rsOrig = $rs;
			$avg = 0;
			$size = 0;
			echo "<table border=1px solid black\"> <tr>";
			//Do the first row
			$fieldArray = $rs->fetch_fields();
			foreach ($fieldArray as $header) {
				echo "<th> {$header->name} </th>";
			}
			echo "</tr>";
			while($row = $rs->fetch_assoc()) {
				echo "<tr>";
				$avg += $row['rating'];
				++$size;
					foreach($fieldArray as $header){
						$val = $row[$header ->name];
						echo "<td>$val</td>";
					}
			    echo "</tr>";
			}
			echo "</table>";

			$avg = number_format($avg / $size, 3);
			$url = "\"AddComments.php?mid=$id\"";
			echo "Average rating: $avg";
			echo "<form method=\"POST\">";
			echo "<button type=\"submit\" formaction=$url>Add Comment </button>";
			echo "</form>";
		}
	}
?>

<br/><br/><br/><br/>
</div>
</body>
</html>
