<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "kyselylomake";
try {
       $yhteys = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
       $yhteys->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "Yhteys muodostettu<br>";
    }
catch(PDOException $e)
    {
    echo "Ei yhteyttä tietokantaan!<br> " . $e->getMessage();
    }
?>