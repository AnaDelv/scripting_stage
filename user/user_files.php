<?php
session_start();
require_once '../layout/header.php';
require_once '../pdo/config.php';
require_once '../library/functions.php';

if(!isLoggedIn()) {
    header('Location: ../login.php?status=err');
}

?>

<title>Gestion des fichiers</title>

<?php require_once '../layout/nav.php';
$db = connect(
    DB_HOST,
    DB_NAME,
    DB_USER,
    DB_PWD
);
$records = fetchData($db);?>

<h2>Fichiers enregistrés</h2>


<table>

    <thead>
    <tr>
        <th>Liste de fichiers uploadés</th>
        <th>Lien de téléchargement</th>
    </tr>

    </thead>

    <tbody>
    <?php

    if(count($records) > 0):
        foreach($records as $record):;?>

            <tr>
                <td><?php echo $record['name']; ?></td>
                <td><a href="<?php echo $record['file_url']?>"><input type="submit" class="btn btn-success" value="Download"></a>
            </tr>

        <?php endforeach;

    else: ?>
        <tr>
            <td colspan="3">Aucun fichier n'est actuellement uploadé</td>
        </tr>
    <?php endif ?>
    </tbody>
</table>