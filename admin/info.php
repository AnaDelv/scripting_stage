<?php
session_start();
require_once '../pdo/config.php';
require_once '../library/functions.php';
require_once '../layout/header.php';

if(!isLoggedIn()) {
    header('Location: ../login.php?status=err');
}

if(!isAdmin()) {
    header('Location: ../index.php?status=not_allowed');
}?>
    <title>Gestion des informations</title>

<?php require_once '../layout/nav.php';
$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);

$getList = getLastInfo($db);
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'deleted':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Les données ont bien été supprimées.';
            break;
        case 'errdel':
            $statusMsgClass = 'alert-danger';
            $statusMsg = "Les données n'ont pas pu être supprimées.";
            break;
        default:
            $statusMsgClass = '';
            $statusMsg = '';
    }
}


if(!empty($statusMsg)){
    echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
}?>

<h3>Informations</h3>

<a href="dashboard.php">Ajouter une information</a>
<?php


$getTotal = getNbPages($db);
$totalPages = ceil($getTotal/10);

if(isset($_GET['page'])) {// Si la variable $_GET['page'] existe...
    $page = intval($_GET['page']);

    if($page > $totalPages) { // Si la valeur de $pageActuelle (le numéro de la page) est plus grande que $totalPages...
        $page  = $totalPages;
    }
} else {// Sinon

    $page = 1; // La page actuelle est la n°1
}

$firstResult = ($page - 1)*10; // On calcul la première entrée à lire

// La requête sql pour récupérer les messages de la page actuelle.
$result = $db->prepare("SELECT * FROM `news` ORDER BY id DESC LIMIT $firstResult , 10");
$result->execute(); ?>

<div class="container">
<table class="table table-bordered">
    <thead class="thead-light">
    <tr>
        <th>Date</th>
        <th>Titre</th>
        <th>Texte</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php while($data = $result->fetch(PDO::FETCH_ASSOC)) {// On lit les entrées une à une grâce à une boucle
        if(count($data) > 0) :?>

        <tr>
            <td><?php echo date('d/m/Y', $data['date']) ?></td>
            <td><?php echo stripslashes($data['title']) ?></td>
            <td><?php echo stripslashes($data['text']) ?></td>
            <td><a href="edit_info.php?id=<?php echo $data['id'];?>"><input type="submit" name="edit_info" class="btn btn-info" value="Modifier"></a>
                <a href="../library/delete_info.php?id=<?php echo $data['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')"><input type="submit" class="btn btn-danger" value="Supprimer"></a></td>

        </tr>

        <?php else: ?>
            <tr>
                <td colspan="4">Aucune information n'est actuellement enregistrée</td>
            </tr>
        <?php endif;?>
    <?php } ?>

    </tbody>

</table>

<p align="center"> Page :
    <?php //Pour l'affichage, on centre la liste des pages
    for($i = 1; $i <= $totalPages; $i++) //On fait notre boucle
    {
        //On va faire notre condition
        if($i == $page) :
            echo ' [ '.$i.' ] ';
        else: //Sinon...
            echo ' <a href="info.php?page='.$i.'">'.$i.'</a> ';
        endif;
    }?>
</p>

</div>