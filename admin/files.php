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

    <title>Gestion des fichiers</title>

<?php require_once '../layout/nav.php';
$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);
$records = fetchData($db);

if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'ok':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Le fichier a bien été uploadé';
            break;
        case 'deleted':
            $statusMsgClass = 'alert-success';
            $statusMsg = 'Le fichier a bien été supprimé';
            break;
        case 'err':
            $statusMsgClass = 'alert-danger';
            $statusMsg = "Une erreur est survenue lors de l'envoi";
            break;
        case 'empty':
            $statusMsgClass = 'alert-danger';
            $statusMsg = "La zone de recherche est vide.";
            break;
        case 'ext':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Veuillez envoyer un fichier au format PDF';
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

<form action="../library/search.php" method="post">
    <input type="search" name="file" id="file" placeholder="Entrez le nom d'un logiciel">
    <input type="submit" value="Rechercher">
</form>


<h2>Upload de fichiers</h2>



<form action="../library/insert.php" method="POST" enctype="multipart/form-data">
    <div class="input-group">
        <div class="custom-file">
            <input type="file" name="fichier" class="custom-file-input" id="inputGroupFile04">
            <label class="custom-file-label" for="inputGroupFile04">Choose file</label>
        </div>
        <div class="input-group-append">
            <input class="btn btn-outline-secondary" type="submit"  value="Envoyez">
            <input class="btn btn-outline-secondary" type="reset" value="Annuler">
        </div>
    </div>

</form>

<h2>Fichiers enregistrés</h2>

<a href="../user/user_files.php">Page utilisateur</a>

<table class="table table_bordered">

    <thead>
    <tr>
        <th>Liste de fichiers uploadés</th>
        <th>Lien de téléchargement</th>
        <th>Supprimer fichier</th>
    </tr>

    </thead>

    <tbody>
    <?php

    if(count($records) > 0):
        foreach($records as $record):;?>

            <tr>
                <td><?php echo $record['name']; ?></td>
                <td><a href="<?php echo $record['file_url']?>"><input type="submit" class="btn btn-success" value="Download"></a>
                <td>


                    <a href="../library/delete_file.php?id=<?php echo $record['id'] ;?>
                    " onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')" ><input type="submit" class="btn btn-danger" value="Supprimer"</a> </td>

            </tr>

        <?php endforeach;

    else: ?>
        <tr>
            <td colspan="3">Aucun fichier n'est actuellement uploadé</td>
        </tr>
    <?php endif ?>
    </tbody>
</table>
