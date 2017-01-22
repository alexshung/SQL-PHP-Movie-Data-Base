<<!DOCTYPE html>
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

<h1>Add Movie Information</h1>

<form method="POST">
    
    Title: <br/>
    <input type="text" name="title"> <br/><br/>

    Company: <br/>
    <input type="text" name="company"> <br/><br/>
    
    Year:
    <input type="year" name="year"> <br/><br/>

    MPAA Rating:
    <select name="mpaa">
    	<option value='G'>G </option>
    	<option value='PG'> PG </option>
    	<option value='PG13'>PG13</option>
    	<option value='R'>R</option>
    	<option value='NC17'>NC17 </option>
    </select> <br/><br/>

    Genre:<br/>
    <input type="checkbox" name="Comedy"> Comedy, <input type="checkbox" name="Romance"> Romance, <input type="checkbox" name="Drama"> Drama, <br/>
    <input type="checkbox" name="Crime"> Crime, <input type="checkbox" name="Horror"> Horror, <input type="checkbox" name="Mystery"> Mystery, <br/>
    <input type="checkbox" name="Thriller"> Thriller, <input type="checkbox" name="Action"> Action, <input type="checkbox" name="Adventure"> Adventure, <br/>
    <input type="checkbox" name="Fantasy"> Fantasy, <input type="checkbox" name="Documentary"> Documentary, <input type="checkbox" name="Family"> Family, <br/>
    <input type="checkbox" name="Sci-Fi"> Sci-Fi, <input type="checkbox" name="Animation"> Animation, <input type="checkbox" name="Musical"> Musical, <br/>
    <input type="checkbox" name="War"> War, <input type="checkbox" name="Western"> Western, <input type="checkbox" name="Adult"> Adult, <br/>
    <input type="checkbox" name="Short"> Short
    <br/> <br/>
    
    <input type="submit">
</form>

<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		include('Utils.php');
		$db = new mysqli('localhost', 'cs143', '', 'CS143');
		if($db->connect_errno > 0){
			die('Unable to connect to database [' .$db->connect_error . ']');
		}

		
		$company = $_POST['company'];
		$title = $_POST['title'];
		$year = $_POST['year'];
		$mpaa = $_POST['mpaa'];
		$genre = $_POST['genre'];

		$fields = 'company, title, year, rating';
		$value = "'$company', '$title', '$year', '$mpaa'";

		$queryMax = 'SELECT Id FROM MaxMovieID';
		$rs = $db->query($queryMax);
		$maxId = $rs->fetch_assoc()['Id']+1;
		
		$dmlStatement = "INSERT INTO Movie (id, $fields) VALUES ('$maxId', $value)";
		//echo $dmlStatement;
		if(Utils::isNull($title) || Utils::isNull($year) || Utils::isNull($mpaa)){
			echo 'Please enter a title, year, and rating.';
		}
		else{
			$rs = $db->query($dmlStatement);
			if (!$rs ){ 
			    $errmsg = $db->error;
				print "Insert failed: $errmsg <br />";
			    exit(1);
			}
			echo "Successfully inserted: $title";
			$updateMax = "UPDATE MaxMovieID SET Id = $maxId";
			//echo $updateMax;
			$rs = $db->query($updateMax);
			if (!$rs ){ 
			    $errmsg = $db->error;
				print "Update failed: $errmsg <br />";
			    exit(1);
			}

			//Do Genre
			$genres = array('Comedy', 'Romance', 'Drama', 'Crime', 'Horror', 'Mystery', 'Thriller', 'Action', 'Adventure', 'Fantasy', 'Documentary', 'Family', 'Sci-Fi', 'Animation', 'Musical', 'War', 'Western', 'Adult', 'Short');

			foreach ($genres as $genre) {
				if($_POST[$genre] == true){
					$dmlInsertMovieGenre = "INSERT INTO MovieGenre (mid, genre) Values ($maxId, '$genre')";
					//echo $dmlInsertMovieGenre;
					$rs = $db->query($dmlInsertMovieGenre);
					if (!$rs ){ 
					    $errmsg = $db->error;
						print "Insert failed: $errmsg <br />";
					    exit(1);
					}
				}
			}
		}
	}
?>
</div>
</body>
</html>

<!--
<?php
/*	$db = new mysqli('localhost', 'cs143', '', 'CS143');
	if($db->connect_errno > 0){
		die('Unable to connect to database [' .$db->connect_error . ']');
	}
	$query = 'SELECT distinct genre FROM MovieGenre';
	$rs = $db->query($query);
	if (!$rs ){ 
	    $errmsg = $db->error;
	    print "Query failed: $errmsg <br />";
	    exit(1);
	}
	echo '<form method="GET">';
	echo 'Genre: <br/>';
	while($row = $rs->fetch_assoc()){
		echo "<input type=\"checkbox\" name=genre> $row['genre']"
	}
?>
-->