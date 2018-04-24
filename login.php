<?php
if(!empty($_GET['status'])){
    switch($_GET['status']){
        case 'field':
            $statusMsgClass = 'alert-danger';
            $statusMsg = 'Tous les champs doivent être renseignés';
            break;
        case 'wrong':
            $statusMsgClass = 'alert-danger';
            $statusMsg = "Identifiant ou mot de passe incorrect";
            break;
        case 'err':
            $statusMsgClass = 'alert-danger';
            $statusMsg = "Vous devez être connecté pour accéder à cette page";
            break;
        case 'logout':
            $statusMsgClass = 'alert-success';
            $statusMsg = "Vous vous êtes déconnecté avec succès";
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

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" href="/style/style.css">
    <title>Connexion</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li>
        </ul>
    </div>
</nav>

<div class="wrapper">
    <form action="session.php" method="POST" enctype="multipart/form-data" class="form-signin">
        <h2 class="form-signin-heading">Connexion</h2>
        <input type="text" name="username" placeholder="Username" class="form-control" autofocus="">
        <input type="password" name="password" placeholder="Password" class="form-control" autofocus="">
        <input type="submit" name="login" value="Envoyez" class="btn btn-lg btn-primary btn-block">
    </form>
</div>




</body>
</html>
