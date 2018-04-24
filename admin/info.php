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
);?>

<h3>Informations</h3>

<table>
    <thead>
    <tr>
        <th>Date de parution</th>
        <th>Message</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
        <tr>
            <td colspan="3">Aucun fichier n'est actuellement uploadé</td>
        </tr>

    </tbody>
</table>