
<?php
const USERNAME = "root";
const PASSWORD = null;

// maak verbinding met database
function db_connect()
{
    $db = new PDO('mysql:host=localhost;dbname=jackydev', USERNAME, PASSWORD);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

    return $db;
}

?>
