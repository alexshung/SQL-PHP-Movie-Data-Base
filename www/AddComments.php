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
<h1>Add Movie Comment</h1>

<form method="POST">
	Rating:
	<select name="rating">
		<option value="1">1</option>
		<option value="2">2</option>
		<option value="3">3</option>
		<option value="4">4</option>
		<option value="5">5</option>
	</select>
	<br/><br/>

	Name:
	<input type="text" name="name"> <br/><br/>

	Comment: <br/>
	<textarea name="comment" cols="60" rows="8"></textarea>
	<br/><br/>

	<input type="submit" value="Add Comment">
</form>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$mid = $_GET['mid'];
		$name = $_POST['name'] == ''?'Anonymous' : $_POST['name'];
		$rating = $_POST['rating'];
		$comment = $_POST['comment'];
		$time = gmdate();

		if($comment == '')
			echo 'Please enter a comment';
		else{
			$dmlStatement = "INSERT INTO Review (time, mid, name, rating, comment) VALUES (now(), $mid, '$name', $rating, '$comment')";
			$db = new mysqli('localhost', 'cs143', '', 'CS143');
			if($db->connect_errno > 0){
				die('Unable to connect to database [' .$db->connect_error . ']');
			}
			//echo $dmlStatement . '<br/>';
			$rs = $db->query($dmlStatement);

			if (!$rs ){ 
			    $errmsg = $db->error;
			    print "Insertion failed: $errmsg <br />";
			    exit(1);
			}

			echo "Successfully added comment: $comment";
			$url = "\"ShowInformation.php?type=Movie&id=$mid\"";
			echo "<form method=\"POST\">";
			echo "<button type=\"submit\" formaction=$url>Return to movie </button>";
			echo "</form>";
		}
		//echo "$mid, $name, $rating, $comment, $time";
	}
?>
</div>
</body>
</html>