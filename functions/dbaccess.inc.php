<?php
function getDBConnection() {
	$conn = mysqli_connect("localhost","root","password","leps");
	mysqli_set_charset($conn,"utf8");
	if($conn) {
		return $conn;
	} else {
		// TODO
	}
}
?>