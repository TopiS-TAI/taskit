<?php

$tietokkayttaja = "tasks";
$tietoksalasana = "tasks";
$tietokanta = "tasks";

$osoite = 'localhost';

try {
	    $yhteys_pdo = new PDO("mysql:host=$osoite; dbname=$tietokanta", $tietokkayttaja, $tietoksalasana,
	        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
	    $yhteys_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
catch (PDOException $e) {
	    die("Virhe! : " . $e->getMessage());
	}


?>