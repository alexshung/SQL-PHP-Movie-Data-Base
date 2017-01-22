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

<h1>Add a Director / Actor</h1>

<form method="GET">
    <input type="radio" name="type" value="Actor" checked> Actor
    <input type="radio" name="type" value="Director"> Director <br/><br/>	
    
    First Name: <br/>
    <input type="text" name="firstName"> <br/>
    Last Name: <br/>
    <input type="text" name="lastName"> <br/><br/>
    
    Sex: <br/>
    <input type="radio" name="sex" value="male"> Male 
    <input type="radio" name="sex" value="female"> Female
    <input type="radio" name="sex" value="other"> Other <br/><br/>

    Date of Birth:
    <input type="date" name="dob"> <br/><br/>

    Date of Death (Leave blank if still alive):
    <input type="date" name="dod"> <br/><br/>

    <input type="submit">
</form>
<?php
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$db = new mysqli('localhost', 'cs143', '', 'CS143');
		if($db->connect_errno > 0){
			die('Unable to connect to database [' .$db->connect_error . ']');
		}
		$type = $_GET['type'];
		$firstName = $_GET['firstName'];
		$lastName = $_GET['lastName'];
		$sex = $_GET['sex'];
		$dob = $_GET['dob'];
		$dod = $_GET['dod'];
		$fields = 'first, last, dob';
		$values = "'$firstName', '$lastName', '$dob'";
		if(is_null($dod)){
			$values .= ", '$dod'";
			$fields .= ', dod';
		}
		if($type == 'Actor'){
			$fields .= ", sex";
			$values .= ", '$sex'";
		}
		$queryMax = 'SELECT Id FROM MaxPersonID';
		$rs = $db->query($queryMax);
		
		$maxId = $rs->fetch_assoc()['Id']+1;
		/*
		echo $fields;
		echo '<br/>';
		echo $values;
		echo '<br/>';
		*/
		$dmlStatement = "INSERT INTO $type (id, $fields) VALUES ('$maxId', $values)";
		//echo "$dmlStatement <br/> ";
		if(is_null($lastName)){
			echo 'Please enter a last name';
		}
		else{
			$rs = $db->query($dmlStatement);
			if (!$rs ){ 
			    $errmsg = $db->error;
			    /*if(strpos($errmsg, 'Duplicate')!== false){
			    	echo 'Attempting to submit a duplicate entry.';
			    }*/
			    //else{
				    print "Insert failed: $errmsg <br />";
				//}
			    exit(1);
			}
			echo "Successfully inserted: $firstName $lastName";
			$updateMax = "UPDATE MaxPersonID SET Id = $maxId";
			$db->query($updateMax);
		}
	}
?>
</div>
</body>
</html>