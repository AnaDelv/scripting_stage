<?php
session_start();
require_once '../layout/header.php';
require_once '../pdo/config.php';
require_once '../library/functions.php';

if(!isLoggedIn()) {
    header('Location: ../login.php?status=err');
}

if(!isAdmin()) {
    header('Location: ../index.php?status=not_allowed');
}?>

<title>Panneau de contrôle</title>

<?php require_once '../layout/nav.php';
$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);

if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'L\'ajout a été un succès.';
            break;
        case 'field':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Veuillez renseigner tous les champs';
            break;
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

<h1>Administration</h1>
<h2>Ajouter information</h2>


<form action="" method="POST" enctype="multipart/form-data">
    <textarea required></textarea>
    <div>
        <label for="choice1">Choice 1 <input id="choice1" type="checkbox"></label>
        <label for="choice2">Choice 2 <input id="choice2" type="checkbox"></label>
        <label for="choice3">Choice 3 <input id="choice3" type="checkbox"></label>
    </div>
    <input type="submit" name="add" value="Envoyez">
    <input type="reset" value="Annuler">
    </form>

<h2>Ajouter utilisateur</h2>


<form action="../library/adduser.php" method="POST" enctype="multipart/form-data">
    <input type="text" name="lastname" placeholder="Last name" required>
    <input type="text" name="firstname" placeholder="First name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="repeatpassword" placeholder="Confirm password" required>
    <input type="text" name="class" placeholder="class" required>

    <input type="submit" name="add" value="Envoyez">
    <input type="reset" value="Annuler">
</form>

<?php require_once '../layout/footer.php'; ?>