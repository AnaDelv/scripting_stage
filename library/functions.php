<?php
require_once __DIR__ . '\..\pdo\connect_db.php';
require_once __DIR__ . '\..\pdo\config.php';


/**Fonction récupérant le contenu de la base de données
 * @param mysqli $db
 * @return array
 */

 function fetchData(PDO $db) {

     $data = [];

     $sql = $db->prepare("SELECT * FROM files");
     $sql -> execute();

     $count = $sql->rowCount();

     if ($count > 0) {

         while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
             $data[] = $row;
         }
     }
     return $data;
 }

 function getFilesId(PDO $db, $id) {

     $sql = $db->prepare("SELECT * FROM files WHERE id= :id");
     $sql->bindParam(':id',$id);
     $result = $sql -> execute();

     return $result;
 }

 function getListId(PDO $db, $id){
     $sql = $db->prepare("SELECT * FROM members WHERE id= :id");
     $sql->bindParam(':id',$id);
     $sql -> execute();

     return $id;
 }


 function getList(PDO $db) {

     $data = [];
     $sql = $db->prepare("SELECT * FROM members ORDER BY lastname, firstname");
     $sql -> execute();
     $count = $sql->rowCount();

     if ($count > 0) {
         while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
             $data[] = $row;
         }
     }
     return $data;
 }

 function getLastInfo(PDO $db){
     $data = [];
     $sql = $db->prepare("SELECT * FROM `news` ORDER BY id DESC");
     $sql -> execute();
     $count = $sql->rowCount();

     if ($count > 0) {
         while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
             $data[] = $row;
         }
     }
     return $data;
 }


 function getInfo(PDO $db, $firstResult, $resultPerPages){
     $result = $db->prepare("SELECT * FROM `news` ORDER BY id DESC LIMIT $firstResult , $resultPerPages");
     $result->execute();
     $data = $result->fetch(PDO::FETCH_ASSOC);
     return $data;
 }

 function getNbPages(PDO $db){
     $sql = $db->prepare("SELECT count(*) AS total FROM `news`");
     $sql->execute();
     $row = $sql->fetch(PDO::FETCH_ASSOC);
     return $row['total'];
 }



/**Insert des fichiers dans la base de données
 * @param PDO $db
 * @param array $record
 * @return PDO
 */

 function insertFile(PDO $db, array $record) {
     $sql = "INSERT INTO `files` (`name`, `file_url`)";
     $sql .= "VALUES (:name,
            :file_url)";

     $stmt = $db->prepare($sql);

     $stmt->bindParam(':name', $record['name'], PDO::PARAM_STR);
     $stmt->bindParam(':file_url', $record['file_url'], PDO::PARAM_STR);
     $stmt->execute();

     return $db;
 }


function addUser(PDO $db, array $record) {
    $sql = "INSERT INTO `members` (`lastname`, `firstname`, `username`, `password`, `email`, `class`) ";
    $sql .= "VALUES (:lastname,
            :firstname, :username, :password, :email, :class)";

    $stmt = $db->prepare($sql);

    $stmt->bindParam(':lastname', $record['lastname'], PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $record['firstname'], PDO::PARAM_STR);
    $stmt->bindParam(':username', $record['username'], PDO::PARAM_STR);
    $stmt->bindParam(':password', $record['password'], PDO::PARAM_STR);
    $stmt->bindParam(':email', $record['email'], PDO::PARAM_STR);
    $stmt->bindParam(':class', $record['class'], PDO::PARAM_STR);
    $stmt->execute();

    return $db;
}

//function addInfo(PDO $db, array $record) {
//    $sql = "INSERT INTO `news` (`title`, `text`) ";
//    $sql .= "VALUES (:title,
//            :text)";
//
//    $stmt = $db->prepare($sql);
//    $stmt->bindParam(':title', $record['title'], PDO::PARAM_STR);
//    $stmt->bindParam(':text', $record['text'], PDO::PARAM_STR);
//    $stmt->execute();
//
//    return $db;
//}


/** Supprimer des élements de la table "files" par leur ID
 * @param PDO $db
 * @param $id
 */

 function deleteFile(PDO $db, $id){

     try {
         $select = $db->prepare("SELECT * FROM `files` WHERE id = :id");
         $select->bindParam(':id', $id);
         $select->execute();

         while($row = $select->fetch()) {

             $url = $row['file_url'];
             var_dump($url);

         }

         if(unlink($url)) {
             echo "YES !";
         } else {
             echo "NO ! T__T";
         }

         $sql = $db-> prepare("DELETE FROM `files` WHERE id = :id");
         $sql->bindParam(':id', $id);
         $sql->execute();

     } catch (PDOException $e) {
         echo $e->getMessage();
     }
 }

/** Supprime des éléments de la table "members" par leur ID
 * @param PDO $db
 * @param $id
 */

function deleteProfile(PDO $db, $id){
    try {

        $sql = $db-> prepare("DELETE FROM `members` WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function deleteInfo(PDO $db, $id){
    try {

        $sql = $db-> prepare("DELETE FROM `news` WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}


function isLoggedIn(){
    if (isset($_SESSION['login']) == true) {
        return true;
    }else{
        return false;
    }
}

function isAdmin()
{
    if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin' ) {
        return true;
    }else{
        return false;
    }
}



function getName() {
    if (isset($_SESSION['login'])){
        $name = $_SESSION['name'];
    }
    return $name;
}
