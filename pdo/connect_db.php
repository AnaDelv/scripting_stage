<?php

function connect($dbHost, $dbName, $dbUser, $dbPwd) {

    try {
        $pdo = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));

        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $pdo;
}