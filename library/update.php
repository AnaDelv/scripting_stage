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


if (isset($_POST['edit'])){
    $id = $_POST['id'];
    $lastname = htmlentities(trim($_POST['lastname']));
    $firstname = htmlentities(trim($_POST['firstname']));
    $username = htmlentities(trim($_POST['username']));
    $password = htmlentities(trim(password_hash($_POST['password'], PASSWORD_DEFAULT)));
    $email = htmlentities(trim($_POST['email']));
    $class = htmlentities(trim($_POST['class']));


    if($_POST['password'] && $_POST['repeatpassword']) {

        if($_POST['password'] == $_POST['repeatpassword']) {

            $update = "UPDATE `members` SET password= :password WHERE id= :id";
            $statement = $db->prepare($update);
            $statement->bindParam(':password', $password);
            $statement->bindParam(':id', $id);
            $statement->execute();
            header('Location: ../admin/users.php?status=edit');

        } else {
            header('Location: ../admin/edit.php?id='.$id.'&status=wrong');
        }

    } else if ($_POST['password'] || $_POST['repeatpassword']){
        header('Location: ../admin/edit.php?id='.$id.'&status=wrong');

    } else if ($lastname || $firstname || $username || $email || $class) {

        $query = $db->prepare("SELECT lastname, firstname FROM `members` WHERE lastname= :lastname AND firstname=:firstname");
        $query->bindParam(':lastname', $lastname);
        $query->bindParam(':firstname', $firstname);
        $query->execute();

        $row = $query->rowCount();

        if ($row > 0) {

            header('Location: ../admin/edit.php?id='.$id.'&status=user');
        } else {


            $sql = "UPDATE `members` SET lastname= :lastname, firstname= :firstname, username= :username, email= :email, class = :class  WHERE id= :id";
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':lastname',$lastname);
            $stmt->bindParam(':firstname',$firstname);
            $stmt->bindParam(':username',$username);
            $stmt->bindParam(':email',$email);
            $stmt->bindParam(':class',$class);
            $stmt->bindParam(':id',$id);
            $stmt->execute();

            header('Location: ../admin/users.php?status=edit');
        }

    }

}