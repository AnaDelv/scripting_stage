<?php
session_start();
require_once '../layout/header.php';
require_once '../pdo/config.php';
require_once '../library/functions.php';

//On vérifie que l'utilisateur est connecté pour autoriser l'accès à la page
if(!isLoggedIn()) {
    header('Location: ../login.php?status=err');
}
//On vérifie que la personne a le droit d'accès à la page
if(!isAdmin()) {
    header('Location: ../index.php?status=not_allowed');
}?>

<title xmlns="http://www.w3.org/1999/html">Panneau de contrôle</title>

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
        case 'added':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Le message a bien été ajouté.';
            break;
        case 'field':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Veuillez renseigner tous les champs.';
            break;
        case 'length':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Votre message doit contenir 280 charactères maximum.';
            break;
        case 'wrong':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Les mots de passe doivent être identiques.';
            break;
        case 'user':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Cet utilisateur existe déjà.';
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


<form action="../library/addinfo.php" method="POST" enctype="multipart/form-data">
    <div><label for="title">Titre</label>
        <input type="text" name="title" id="title">
    </div>
    <div>
        <label for="text">Texte</label>
        <textarea id="text" name="text"></textarea>
    </div>
    <div>
        <label for="choice1">Choice 1 <input id="choice1" type="checkbox"></label>
        <label for="choice2">Choice 2 <input id="choice2" type="checkbox"></label>
        <label for="choice3">Choice 3 <input id="choice3" type="checkbox"></label>
    </div>
    <input type="submit" name="addinfo" value="Envoyez">
    <input type="reset" value="Annuler">
    </form>

    <a href="info.php">Voir la liste des informations</a>

<h2>Ajouter utilisateur</h2>


<form action="../library/adduser.php" method="POST" enctype="multipart/form-data">
    <div>
        <label for="lastname_form">Nom : </label>
        <input type="text" id="lastname_form" name="lastname" placeholder="Last name">
    </div>
    <div>
        <label for="firstname_form">Prénom : </label>
        <input type="text" id="firstname_form" name="firstname" placeholder="First name">
    </div>
    <div>
        <label for="username_form">Identifiant : </label>
        <input type="text" id="username_form" name="username" placeholder="Username">
    </div>
    <div>
        <label for="pass_form">Mot de passe : </label>
        <input type="password" id="pass_form" name="password" placeholder="Password">
    </div>
    <div>
        <label for="confpass_form">Confirmez le mot de passe : </label>
        <input type="password" id="confpass_form" name="repeatpassword" placeholder="Confirm password">
    </div>
    <div>
        <label for="class_form">Classe : </label>
        <input type="text" id="class_form" name="class" placeholder="Class">
    </div>
    <input type="submit" name="add" value="Envoyez">
    <input type="reset" value="Annuler">
</form>
    <a href="users.php">Voir les utilisateurs</a>

<?php require_once '../layout/footer.php'; ?>