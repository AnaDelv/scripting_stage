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

<form action="" method="post">
    <input type="search" name="file" id="file" placeholder="Entrez le nom d'un logiciel">
    <input type="submit" value="Rechercher">
</form>


<?php
if (isset($_POST['file'])):
    if(!empty($_POST['file'])) :

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

        //suppression des caractères spéciaux
        $query = htmlspecialchars($_POST['file']);
        //si l'utilisateur a saisie quelque chose, on traite sa requete
        $sql ="SELECT * FROM `files` WHERE name LIKE ? ORDER BY `name` LIMIT $firstResult, 10";

        $req = $db->prepare($sql);
        $req->execute(array('%'.$query.'%'));
        $count = $req->rowCount();

        if($count >= 1): ?>

            <div class="container">
        <?php echo "$count résultat(s) pour <strong>$query</strong><hr/>";?>

        <table class="table table-bordered">
            <thead class="thead-light">
            <tr>
                <th>Nom</th>
                <th>Lien</th>
                <th>Action</th>
            </tr>
            </thead>

    <?php while($data = $req->fetch()){?>
            <tbody>
            <tr>
                <td><?php echo $data['name']; ?></td>
                <td><a href="<?php echo $data['file_url']?>"><input type="submit" class="btn btn-success" value="Download"></a>
                <td><a href="../library/delete_file.php?id=<?php echo $data['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')"><input type="submit" class="btn btn-danger" value="Supprimer"></a></td>
            </tr>
            </tbody>

        <?php }

    else:
        echo "aucun élément trouvé pour <strong>$query</strong><hr>";
    endif; ?>
</table>
<p align="center"> Page :
    <?php //Pour l'affichage, on centre la liste des pages
    for($i = 1; $i <= $totalPages; $i++) //On fait notre boucle
    {
        //On va faire notre condition
        if($i == $page) :
            echo ' [ '.$i.' ] ';
        else: //Sinon...
            echo ' <a href="files.php?page='.$i.'">'.$i.'</a> ';
        endif;
    }?>
</p>
        </div>
<?php endif;
endif;?>




<h2>Ajouter un programme</h2>



<form action="../library/insert.php" method="POST" enctype="multipart/form-data">
    <div class="input-group">
        <div class="custom-file">
            <input type="file" name="fichier" id="inputGroupFile04" class="btn btn-outline-secondary">
            <label for="inputGroupFile04"></label>
            <input class="btn btn-outline-secondary" type="submit"  value="Envoyez">
            <input class="btn btn-outline-secondary" type="reset" value="Annuler">
        </div>
    </div>

</form>

<h2>Fichiers enregistrés</h2>

<a href="../user/user_files.php">Page utilisateur</a>


<div>
    <a href="#demo" class="btn btn-info" data-toggle="collapse">Voir tout</a>
    <div class="container">
        <div id="demo" class="collapse">
            <div>
                <table class="table table_bordered">

                    <thead class="thead-light">
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

            </div>
        </div>
    </div>
</div>