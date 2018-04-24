<?php
session_start();
require_once '../layout/header.php';
require_once '../pdo/config.php';
require_once '../library/functions.php';
require_once '../layout/nav.php';


if(!isLoggedIn()) {
    header('Location: ../login.php?status=err');
}

if(!isAdmin()) {
    header('Location: ../index.php?status=not_allowed');
}

$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);

if(!empty($_GET['id'])){
    $id = $_REQUEST['id'];

}

if(isset($id)) {
    $sql = "SELECT * FROM `news` WHERE id= :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $row = $stmt->fetch();
}


if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'edited':
            $statusMsgClass = 'alert-success';
            $statusMsg = "Les données ont bien été modifiées";
            break;
        case 'field':
            $statusMsgClass = 'alert-danger';
            $statusMsg = "Veuillez remplir l'un des champs";
            break;
        default:
            $statusMsgClass = '';
            $statusMsg = '';
    }
}

if(!empty($statusMsg)){
    echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
}

?>

    <title>Modifier le profil</title>

<?php require_once '../layout/nav.php'; ?>


    <h1>Modifier le profil</h1>
    <form action="../library/update_info.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $row['id']?>">
        <div><label for="title">Titre</label>
            <input type="text" name="title" id="title"  value="<?php echo $row['title']?>">
        </div>
        <div>
            <label for="text">Texte</label>
            <textarea id="text" name="text"><?php echo $row['text']?></textarea>
        </div>
        <div>
            <label for="choice1">Choice 1 <input id="choice1" type="checkbox"></label>
            <label for="choice2">Choice 2 <input id="choice2" type="checkbox"></label>
            <label for="choice3">Choice 3 <input id="choice3" type="checkbox"></label>
        </div>
        <input type="submit" name="edit_info" value="Envoyez">
        <input type="reset" value="Annuler">
    </form>

    <a href="info.php">Retour à la liste des informations</a>

<?php require_once '../layout/footer.php'; ?>