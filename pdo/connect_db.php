<?php

function connect($dbHost, $dbName, $dbUser, $dbPwd) {

    try {
        $pdo = new PDO('mysql:host='.$dbHost.';dbname='.$dbName, $dbUser, $dbPwd);
        $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

    return $pdo;
}