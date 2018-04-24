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
}
?>

<title>Gestion des utilisateurs</title>

<?php require_once '../layout/nav.php';
$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);

$getlist = getList($db);

if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'succ':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Les données ont bien été ajoutées.';
            break;
        case 'edit':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Les modifications sont faites.';
            break;
        case 'deleted':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Les données ont bien été supprimées.';
            break;
        case 'empty':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'La zone de recherche est vide.';
            break;

        case 'errdel':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Une erreur est survenue.';
            break;
        case 'invalid':
            $statusMsgClass = 'alert-danger';
            $statusMsg = "Merci d'envoyer un fichier au format CSV";
            break;
        default:
            $statusMsgClass = '';
            $statusMsg = '';
    }
}


if(!empty($statusMsg)){
    echo '<div class="alert '.$statusMsgClass.'">'.$statusMsg.'</div>';
}?>

<form action="../library/search.php" method="post">
    <input type="search" name="query" id="query" placeholder="Entrez Nom et/ou Prénom">
    <input type="submit" value="Rechercher">
</form>

<p>Ajout de données par fichier CSV</p>



<div class="panel-heading">Members list</div>

<form action="../library/import.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="csvfile">
    <input type="submit" class="btn btn-primary" name="importSubmit" value="Envoyez">
    <input type="reset" value="Annuler">
</form>

<div>
    <table class="table table-bordered">
        <thead class="thead-light">
            <tr>
                <th scope="col">Nom</th>
                <th scope="col">Prénom</th>
                <th scope="col">Identifiant</th>
                <th scope="col">Email</th>
                <th scope="col">Classe</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
        <?php
            if(count($getlist) > 0):
                foreach($getlist as $list) :

                    if($list['role'] != 'admin'):?>

                    <tr>
                        <td><?php echo $list['lastname']; ?></td>
                        <td><?php echo $list['firstname']; ?></td>
                        <td><?php echo $list['username']; ?></td>
                        <td><?php echo $list['email']; ?></td>
                        <td><?php echo $list['class']; ?></td>
                        <td><a href="edit_user.php?id=<?php echo $list['id'];?>"><input type="submit" name="edit" class="btn btn-info" value="Modifier"></a>
                            <a href="../library/delete_profile.php?id=<?php echo $list['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')"><input type="submit" class="btn btn-danger" value="Supprimer"></a></td>


                    </tr>
                    <?php endif;
                endforeach;
            else: ?>
                <tr>
                    <td colspan="6">Aucune donnée actuellement enregistrée</td>
                </tr>
            <?php endif ?>
        </tbody>
</table>



<a href="dashboard.php">Retour vers le dashboard</a>