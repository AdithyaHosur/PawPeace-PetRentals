<?php
// db_connect.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pawpeace_db"; // Use the database name you created (or "pet_store" if you used that)

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

?>