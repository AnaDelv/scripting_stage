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

$email = $_POST['username'] . "@exemple.com";

$newUser = [
    'lastname' => htmlentities(trim($_POST['lastname'])),
    'firstname' => htmlentities(trim($_POST['firstname'])),
    'username' => htmlentities(trim($_POST['username'])),
    'email' => htmlentities(trim($email)),
    'password' => htmlentities(trim(password_hash($_POST['password'],PASSWORD_DEFAULT))),
    'class' => htmlentities(trim($_POST['class']))
];

if(isset($_POST['add'])) {

    if(!empty($_POST['lastname']) || !empty($_POST['firstname']) || !empty($_POST['username']) || !empty($_POST['password']) || !empty($_POST['repeatpassword']) || !empty($_POST['class'])) {
        if($_POST['password'] == $_POST['repeatpassword']) {

            $query = $db->prepare("SELECT lastname, firstname FROM `members` WHERE lastname= :lastname AND firstname=:firstname");
            $query->bindParam(':lastname', $newUser['lastname']);
            $query->bindParam(':firstname', $newUser['firstname']);
            $query->execute();

            $row = $query->rowCount();

            if ($row > 0) {

                $qstring = '?status=user';
            } else {
            addUser($db, $newUser);
                $qstring = '?status=succ';
            }
        } else {
            $qstring = '?status=wrong';
        }
    } else {
        $qstring = '?status=field';
    }


}
//header('Location: ../admin/dashboard.php'.$qstring);