<?php
require_once "../business.php";

$db = get_db();

//Mongo
// select a collection (analogous to a relational database's table)
$collection = $db->photos;
$collection->remove();
$collection = $db->users;
$collection->remove();


?>