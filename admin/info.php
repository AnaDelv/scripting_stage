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

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Date de parution</th>
        <th>Titre</th>
        <th>Message</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>

    <?php if(count($getList) > 0):
        foreach ($getList as $list): ?>
    <tr>
        <td><?php echo date('d/m/Y', $list['date']);?></td>
        <td><?php echo stripslashes($list['title'])?></td>
        <td><?php echo stripslashes($list['text'])?></td>
        <td><a href="edit_info.php?id=<?php echo $list['id'];?>"><input type="submit" name="edit_info" class="btn btn-info" value="Modifier"></a>
            <a href="../library/delete_info.php?id=<?php echo $list['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')"><input type="submit" class="btn btn-danger" value="Supprimer"></a></td>


    </tr>



    <?php endforeach;

    else: ?>
        <tr>
            <td colspan="4">Aucune information n'est actuellement enregistrée</td>
        </tr>
    <?php endif;?>
    </tbody>
</table>