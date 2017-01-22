<?php
	class Utils{
		public static function printTable($rs, $tableWidth){
			echo "<table border=\"" . $tableWidth . "px solid black\"> <tr>";

			//Do the first row
			$fieldArray = $rs->fetch_fields();
			foreach ($fieldArray as $header) {
				if($header->name != 'id')
					echo "<th> {$header->name} </th>";
			}
			echo "</tr>";
			while($row = $rs->fetch_assoc()) {
				echo "<tr>";
				$row_id = $row['id'];
					foreach($fieldArray as $header){
						if($header->name != 'id'){
							$val = $row[$header ->name];
							if($header->name == 'dod' && is_null($val))
								$val = 'Still Alive';
							echo "<td>$val</td>";
						}
					}
			    echo "</tr>";
			}
			echo "</table>";
			echo '<br/>';
		}

		public static function printLinkTable($rs, $tableWidth, $typeShow){
			echo "<table border=\"" . $tableWidth . "px solid black\"> <tr>";

			//Do the first row
			$fieldArray = $rs->fetch_fields();
			foreach ($fieldArray as $header) {
				if($header->name != 'id')
					echo "<th> {$header->name} </th>";
			}
			echo "</tr>";
			while($row = $rs->fetch_assoc()) {
				echo "<tr>";
				$row_id = $row['id'];
					foreach($fieldArray as $header){
						if($header->name != 'id'){
							$val = $row[$header ->name];
							if($header->name == 'dod' && is_null($val))
								$val = 'Still Alive';
							echo "<td><a href='ShowInformation.php?type=$typeShow&id=$row_id'>$val </a> </td>";
						}
					}
			    echo "</tr>";
			}
			echo "</table>";
			echo '<br/>';
		}

		public static function isNull($param){
			return is_null($param) || $param == '';
		}
	}
?>