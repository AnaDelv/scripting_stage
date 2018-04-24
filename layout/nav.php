</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="../index.php">Navbar</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="../index.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?php echo "Bonjour, " .getName(); ?>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">

                    <?php if(isAdmin()) :?>
                    <a class="dropdown-item" href="../admin/dashboard.php">Panneau de contrôle</a>
                    <a class="dropdown-item" href="../admin/users.php">Gestion des utilisateurs</a>
                    <a class="dropdown-item" href="../admin/files.php">Gestion des programmes</a>
                    <a class="dropdown-item" href="../admin/info.php">Gestion des informations</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../admin/logout.php">Deconnexion</a>
                    <?php else:?>
                    <a class="dropdown-item" href="../user/user_files.php">Téléchargement</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="../user/logout.php">Deconnexion</a>
                    <?php endif ?>

                </div>
            </li>
        </ul>
    </div>
</nav>