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
            <?php echo "$count résultat(s) pour <strong>$query</strong><hr/>";?>
        <div class="container">
            <table class="table table-bordered">
            <thead class="thead-light">
            <tr>
                <th>Nom</th>
                <th>Lien</th>
                <?php if(isAdmin()) :?>
                <th>Action</th>
                <?php endif; ?>
            </tr>
            </thead>

            <?php while($data = $req->fetch()){?>
            <tbody>
            <tr>
                <td><?php echo $data['name']; ?></td>
                <td><a href="<?php echo $data['file_url']?>"><input type="submit" class="btn btn-success" value="Download"></a>
                    <?php if(isAdmin()) :?>
                <td><a href="../library/delete_file.php?id=<?php echo $data['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')"><input type="submit" class="btn btn-danger" value="Supprimer"></a></td>
                <?php endif; ?>
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
                    echo ' <a href="user_files.php?page='.$i.'">'.$i.'</a> ';
                endif;
            }?>
        </p>
        </div>
    <?php endif;
endif;?>


<div>
    <a href="#demo" class="btn btn-info" data-toggle="collapse">Voir tout</a>
    <div class="container">
        <div id="demo" class="collapse">
            <div>
                <table class="table table_bordered">

                    <thead class="thead-light">
                    <tr>
                        <th>Liste de fichiers uploadés</th>
                        <th>Lien</th>
                        <?php if(isAdmin()) :?>
                            <th>Action</th>
                        <?php endif; ?>
                    </tr>

                    </thead>

                    <tbody>
                    <?php

                    if(count($records) > 0):
                        foreach($records as $record):;?>

                            <tr>
                                <td><?php echo $record['name']; ?></td>
                                <td><a href="<?php echo $record['file_url']?>"><input type="submit" class="btn btn-success" value="Download"></a></td>
                                <?php if(isAdmin()) :?>
                                    <td><a href="../library/delete_file.php?id=<?php echo $record['id']; ?>" onclick="return confirm('Voulez-vous supprimer cet élément de la liste ?')"><input type="submit" class="btn btn-danger" value="Supprimer"></a></td>
                                <?php endif; ?>
                            </tr>

                        <?php endforeach;

                    else: ?>
                        <tr>
                            <td colspan="2">Aucun fichier n'est actuellement uploadé</td>
                        </tr>
                    <?php endif ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>