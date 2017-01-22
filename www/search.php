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

<h1> Search for a specific Movie / Actor </h1>

<form method="POST">
	Search Text: <br/>
    <input type="text" name="search"> <br/><br/>
    <input type="submit">
</form>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$searchText = $_POST['search'];
		$emptySearch = trim($searchText) == "";
		$words = $emptySearch ? array() : explode(" ", str_replace("  ", " ", $searchText));
		
		//var_dump($emptySearch);
		//var_dump($words);
		
		//Build the query
		$actorQuery = "SELECT id, first, last, dob FROM Actor";
		$movieQuery = "SELECT id, title, year FROM Movie";
		if(!$emptySearch){
			$actorQuery .= " WHERE ";
			$movieQuery .= " WHERE ";
			foreach($words as $word){
				$actorQuery .= "(UPPER(first) LIKE UPPER('%$word%') OR UPPER(last) LIKE UPPER('%$word%')) AND ";
				$movieQuery .= "UPPER(title) LIKE UPPER('%$word%') AND ";
			}
			$actorQuery = substr($actorQuery, 0, strrpos($actorQuery, "AND"));
			$movieQuery = substr($movieQuery, 0, strrpos($movieQuery, "AND"));
			
		}
		/*
		echo "<br/>";
		echo "$actorQuery <br/>";
		echo "$movieQuery <br/>";
		*/
		$db = new mysqli('localhost', 'cs143', '', 'CS143');
		if($db->connect_errno > 0){
			die('Unable to connect to database [' .$db->connect_error . ']');
		}

		$rs = $db->query($actorQuery);
		if (!$rs ){ 
		    $errmsg = $db->error;
		    print "Query failed: $errmsg <br />";
		    exit(1);
		}
		include('Utils.php');
		echo '<h2> Actors </h2>';
		Utils::printLinkTable($rs, 2, 'Actor');

		$rs = $db->query($movieQuery);
		if (!$rs ){ 
		    $errmsg = $db->error;
		    print "Query failed: $errmsg <br />";
		    exit(1);
		}
		echo '<h2> Movies </h2>';
		Utils::printLinkTable($rs, 2, 'Movie');
	}
?>
</div>
</body>
</html>
