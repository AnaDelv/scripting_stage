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

if (isset($_POST['query'])) {


    if(!empty($_POST['query'])) {
        //suppression des caractères spéciaux
        $query = htmlspecialchars($_POST['query']);
        //si l'utilisateur a saisie quelque chose, on traite sa requete
        $ql ="SELECT * FROM `members` WHERE lastname LIKE ? OR firstname LIKE ?";

        $req = $db->prepare($ql);
        $req->execute(array('%'.$query.'%', '%'.$query.'%'));
        $count = $req->rowCount();

        if($count >= 1){   echo "$count résultat(s) pour <strong>$query</strong><hr/>";
            while($data = $req->fetch(PDO::FETCH_OBJ)){
                echo 'nom: '.$data->lastname.'<br/> prénom: '.$data->firstname.'<br/><hr/>';
            }
        }else{
            echo "aucun élément trouvé pour <strong>$query</strong><hr>";
        }
    } else {
        header('Location: ../admin/users.php?status=empty');
    }

}

if (isset($_POST['file'])) {


    if(!empty($_POST['file'])) {
        //suppression des caractères spéciaux
        $query = htmlspecialchars($_POST['file']);
        //si l'utilisateur a saisie quelque chose, on traite sa requete
        $ql ="SELECT * FROM `files` WHERE `name` LIKE ?";

        $req = $db->prepare($ql);
        $req->execute(array('%'.$query.'%'));
        $count = $req->rowCount();

        if($count >= 1){   echo "$count résultat(s) pour <strong>$query</strong><hr/>";
            while($data = $req->fetch(PDO::FETCH_OBJ)){
                echo 'nom: '.$data->name.'<br/> dossier: '.$data->file_url.'<br/><hr/>';
            }
        }else{
            echo "aucun élément trouvé pour <strong>$query</strong><hr>";
        }
    } else {
        header('Location: ../admin/files.php?status=empty');
    }

}