<?php
require_once '../pdo/connect_db.php';
require_once '../pdo/config.php';
require_once 'functions.php';


$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);

$id = $_GET['id'];

try {
    if(!empty($_GET['id'])){
        $id = $_GET['id'];
    }
} catch (Exception $e) {
    echo "Message : " .$e->getMessage();
}



if(deleteInfo($db,$id) == true) {
    $qstring = "?status=errdel";

} else {
    $qstring = "?status=deleted";
}



header("Location: ../admin/info.php".$qstring);
?>