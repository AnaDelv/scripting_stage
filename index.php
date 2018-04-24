<?php
session_start();
require_once 'layout/header.php';
require_once 'pdo/config.php';
require_once 'library/functions.php';

if(!isLoggedIn()) {
    header('Location: ../login.php?status=err');
}

$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="index.php">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo "Bonjour, " .getName(); ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <?php if(isAdmin()) :?>
                        <a class="dropdown-item" href="admin/dashboard.php">Panneau de contrôle</a>
                        <a class="dropdown-item" href="admin/users.php">Gestion des utilisateurs</a>
                        <a class="dropdown-item" href="admin/files.php">Gestion des programmes</a>
                        <a class="dropdown-item" href="admin/info.php">Gestion des informations</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="admin/logout.php">Deconnexion</a>
                    <?php else:?>
                        <a class="dropdown-item" href="user/user_files.php">Téléchargement</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="user/logout.php">Deconnexion</a>
                    <?php endif ?>
                </div>
            </li>
        </ul>
    </div>
</nav>

<?php
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'not_allowed':
        $statusMsgClass = 'alert-danger';
        $statusMsg = "Vous n'êtes pas autorisé à consulter cette page";
        break;
        default:
        $statusMsgClass = '';
        $statusMsg = '';
    }
}

if(!empty($statusMsg)){
echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
}?>

<div class="slider">
    <div class="slides">

        <?php
        //on recupere les 5 dernieres news
        $retour= $db->prepare('SELECT * FROM `news` ORDER BY id DESC LIMIT 0, 3');
        $retour->execute();

        while($donnees=$retour->fetch()){?>
            <div class="slide">
                <span><?php echo date('d/m/Y', $donnees['date']).' -- INFOS : ';?></span>
                <?php $contenu =nl2br(stripslashes($donnees['text']));
                echo  $contenu;
                ?>
            </div>
        <?php } ?>
    </div>
</div>

<div class="carousel-inner form">
    <div class="item active carSize">
        <div class="carousel-content" id="googlesearch"><!------------SLIDE GOOGLE----------------->
            <form action="http://www.google.fr/search" id="google" target="_blank">
                <input type="hidden" name="hl" value="fr" />
                <img src="data:image/png;base64,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" alt="logo google" class="search center-block" height="80px">
                <input class="center-block form-control all" id="g-search" type="text" name="q" size="50" placeholder="Recherche avec Google..." />
                <button class="btn btn-info" type="submit" ><span class="glyphicon glyphicon-search"></span> Rechercher</button>
            </form>
        </div>
    </div>
</div>


