<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " - CineMMI" : "CineMMI - Votre catalogue de films"; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Outfit:wght@400;600;800&display=swap" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="container container-header">
            <a href="index.php" class="logo">
                <i class="fas fa-film"></i> Cine<span>MMI</span>
            </a>
            
            <?php include 'nav.php'; ?>

            <div class="header-actions">
                <form action="categorie.php" method="GET" class="search-form">
                    <input type="text" name="search" placeholder="Rechercher un film..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button type="submit"><i class="fas fa-search"></i></button>
                </form>
                <a href="ajouter_film.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nouveau Film</a>
            </div>
        </div>
    </header>
    <main>
