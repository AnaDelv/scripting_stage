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


$file_name = $_FILES['csvfile']['name'];
$file_extension = strrchr($file_name, ".");
$allowed_ext = array ('.csv', '.CSV');


if(isset($_POST['importSubmit'])) {

    if(!empty($_FILES) && in_array($file_extension,$allowed_ext)) {

        $handle = fopen($_FILES['csvfile']['tmp_name'],'r');

        $file = file($_FILES['csvfile']['tmp_name']);

        foreach ($file as $case) {

            $result = explode(';',$case);
            $result[4] = $result[2] . "@exemple.com";
            $result[3] = password_hash($result[3], PASSWORD_DEFAULT);
            $test = $db -> query('INSERT INTO `members` (lastname, firstname, username, password, email, class) VALUES ("'.$result[0].'","'.$result[1].'","'.$result[2].'","'.$result[3].'", "'.$result[4].'",  "'.$result[5].'" )');
        }
        fclose($handle);

        $qstring = '?status=succ';

    } else {

        $qstring = '?status=invalid';
    }


}

header('Location: ../admin/users.php'.$qstring);