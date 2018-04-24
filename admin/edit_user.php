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
    $sql = "SELECT * FROM `members` WHERE id= :id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':id',$id);
    $stmt->execute();
    $row = $stmt->fetch();
}


if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'wrong':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Les mots de passe doivent être identiques';
            break;
        case 'user':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Cet utilisateur existe déjà';
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
    <div class="panel panel-default">
        <div class="panel-body">
            <form action="../library/update.php" method="POST" name="edit" enctype="multipart/form-data">


                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="text" name="lastname" value="<?php echo $row['lastname'];?>">
                <input type="text" name="firstname" value="<?php echo $row['firstname'];?>">
                <input type="text" name="username" value="<?php echo $row['username'];?>">
                <input type="password" name="password" placeholder="Password">
                <input type="password" name="repeatpassword" placeholder="Confirm Password">
                <input type="text" name="email" value="<?php echo $row['email'];?>">
                <input type="text" name="class" value="<?php echo $row['class'];?>">
                <input type="submit" class="btn btn-primary" name="edit" value="Envoyez">
                <input type="reset" value="Annuler">
            </form>
        </div>
    </div>

    <div>
        <a href="users.php">Retour</a
    </div>

<?php require_once '../layout/footer.php'; ?>

