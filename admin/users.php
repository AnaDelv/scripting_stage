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
}
?>

<form action="" method="post">
    <input type="search" name="query" id="query" placeholder="Entrez Nom et/ou Prénom">
    <input type="submit" value="Rechercher">
</form>

<?php if (isset($_POST['query'])):

    if(!empty($_POST['query'])) :




        //suppression des caractères spéciaux
        $query = htmlspecialchars($_POST['query']);
        //si l'utilisateur a saisie quelque chose, on traite sa requete
        $sql ="SELECT * FROM `members` WHERE lastname LIKE ? OR firstname LIKE ? ORDER BY lastname, firstname LIMIT $firstResult, 10";

        $req = $db->prepare($sql);
        $req->execute(array('%'.$query.'%', '%'.$query.'%'));
        $count = $req->rowCount();

        if($count >= 1):
            echo "$count résultat(s) pour <strong>$query</strong><hr/>";?>
            <table class="table table-bordered">
                <thead class="thead-light">
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Identifiant</th>
                    <th>E-mail</th>
                    <th>Classe</th>
                    <th>Action</th>
                </tr>
                </thead>

            <?php while($data = $req->fetch()){
                if($data['role'] != 'admin'):?>
                <tbody>
                <tr>
                    <td><?php echo $data['lastname']; ?></td>
                    <td><?php echo $data['firstname']; ?></td>
                    <td><?php echo $data['username']; ?></td>
                    <td><?php echo $data['email']; ?></td>
                    <td><?php echo $data['class']; ?></td>
                    <td><a href="edit_user.php?id=<?php echo $data['id'];?>"><input type="submit" name="edit" class="btn btn-info" value="Modifier"></a>
                        <a href="../library/delete_profile.php?id=<?php echo $data['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')"><input type="submit" class="btn btn-danger" value="Supprimer"></a></td>
                </tr>
                </tbody>
           <?php endif;}
        else:
            echo "aucun élément trouvé pour <strong>$query</strong><hr>";
        endif;?>
        </table>
<p align="center"> Page :

    <?php for($i = 1; $i <= $totalPages; $i++) //On fait notre boucle
    {
        //On va faire notre condition
        if($i == $page) :
            echo ' [ '.$i.' ] ';
        else: //Sinon...
            echo ' <a href="users.php?page='.$i.'">'.$i.'</a> ';
        endif;
    }?>
</p>
    <?php endif;
endif;?>





<p>Ajout de données par fichier CSV</p>






<div class="panel-heading">Members list</div>

<form action="../library/import.php" method="POST" enctype="multipart/form-data">
    <input type="file" name="csvfile">
    <input type="submit" class="btn btn-primary" name="importSubmit" value="Envoyez">
    <input type="reset" value="Annuler">
</form>



    <a href="#demo" class="btn btn-info" data-toggle="collapse">Voir tout</a>
<div class="container">
    <div id="demo" class="collapse">
        <div>
            <table class="table table-bordered">
                <thead class="thead-light">
                <tr>
                    <th scope="col">Nom</th>
                    <th scope="col">Prénom</th>
                    <th scope="col">Identifiant</th>
                    <th scope="col">E-mail</th>
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
        </div>
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
</div>
    <div>
        <a href="dashboard.php">Retour vers le dashboard</a>
    </div>