<?php
ini_set("display_errors",1);
error_reporting(E_ALL);


$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

define('CONNECTBDD', array(
    'type'=>'mysql',
    'host' => 'localhost',
    'user' => 'root',
    'pass' => '',
    'database' => 'todoliste'
));



try {
    $pdo = new PDO(CONNECTBDD['type'].':host='.CONNECTBDD['host'].';dbname='.CONNECTBDD['database'],CONNECTBDD['user'],CONNECTBDD['pass'],$options);
} catch (PDOException $e) {
    die('<p>Erreur lors de la connexion à la base de données : '.$e->getMessage() . '</p>'); 
}
