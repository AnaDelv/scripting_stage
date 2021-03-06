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


if (isset($_POST['edit_info'])){
    $id = $_POST['id'];
    $title = htmlentities(addslashes(trim($_POST['title'])));
    $date = time();
    $text = htmlentities(addslashes(trim($_POST['text'])));
    $section = $_POST['section'];

    if(!empty($title) || !empty($text) || !empty($section)) {

        $sql = "UPDATE `news` SET title= :title, text= :text, etablissement=:etablissement, date=:date WHERE id= :id";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title',$title);
        $stmt->bindParam(':text',$text);
        $stmt->bindParam(':etablissement',$section[0]);
        $stmt->bindParam(':date',$date);
        $stmt->bindParam(':id',$id);
        $stmt->execute();

        header('Location: ../admin/edit_info.php?id='.$id.'&status=edited');
    } else {
        header('Location: ../admin/edit_info.php?id='.$id.'&status=field');
    }

}