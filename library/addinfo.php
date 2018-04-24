<?php
require_once '../pdo/connect_db.php';
require_once 'functions.php';
require_once '../pdo/config.php';

$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);

if(isset($_POST['addinfo'])) {
    $title = htmlentities(addslashes(trim($_POST['title'])));
    $text = htmlentities(addslashes(trim($_POST['text'])));
    $date = time();


    if(!empty($title) && !empty($text)) {

        if(strlen($text) <= 280) {
            $stmt = $db->prepare("INSERT INTO `news` (`title`, `text`, `date`) VALUES (:title,
            :text, :timing) ");
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':text', $text);
            $stmt->bindParam(':timing', $date);
            $stmt->execute();

            $qstring = '?status=added';
        } else {
            $qstring = '?status=length';

        }
    } else {
    $qstring = '?status=field';
    }

}
header('Location: ../admin/dashboard.php'.$qstring);