<?php
session_start();
require_once 'pdo/connect_db.php';
require_once 'library/functions.php';
require_once 'pdo/config.php';


$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);


if(isset($_POST['login'])){//Si le formulaire est envoyé
    $username = htmlentities(trim($_POST["username"]));
    $password = htmlentities(trim($_POST["password"]));

    if(!empty($username) && !empty($password)){//si les deux champs ont été saisis
        $query = $db -> prepare("SELECT * FROM `members` WHERE username=:username");
        $query->bindParam(':username', $username);
        $query->execute();


        if($query->rowCount() > 0) {
            $data[] = $query->fetch(PDO::FETCH_ASSOC);

            foreach ($data as $value) {
                if(password_verify($password, $value['password'])) {
                    if($value['role'] == 'admin') {

                        $_SESSION['login'] = true;
                        $_SESSION['name'] = "Admin";
                        $_SESSION['user'] = $value['role'];
                        $_SESSION['success'] = "Bienvenue !";
                        header('Location: index.php');

                    } else {
                        $_SESSION['login'] = true;
                        $_SESSION['name'] = $value['firstname'];
                        $_SESSION['role'] = $value['role'];
                        $_SESSION['success'] = "Bienvenue !";
                        header('Location: index.php');
                    }
                } else {
                    header('Location: login.php?status=wrong');
                }
            }
        }
    } else {
        header('Location: login.php?status=field');
    }
}