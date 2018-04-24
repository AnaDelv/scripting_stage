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


$file_name = $_FILES['fichier']['name'];
$file_extension = strrchr($file_name, ".");
$allowed_ext = array ('.exe', '.EXE', '.msi', '.MSI');

$file_tmp_name = $_FILES['fichier']['tmp_name'];
$file_dest = '../files/' . $file_name;

$record = [
    'id' => '',
    'name' => ''.$file_name,
    'file_url' => ''.$file_dest
];




if (!empty($_FILES)) {

    if(in_array($file_extension, $allowed_ext)) {

        if(move_uploaded_file($file_tmp_name, $file_dest) && $_FILES['fichier']['error'] == 0) {
            insertFile($db,$record);
            $qstring = '?status=ok';

        } else {

            $qstring = '?status=err';
        }

    } else {
        $qstring = '?status=ext';
    }

}

header("Location: ../admin/files.php".$qstring);
