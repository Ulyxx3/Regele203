<?php include('connexion.php'); ?>

<?php
// On récupère tous les genres pour le sous-menu
$sth = $dbh->prepare('SELECT * FROM genre ORDER BY libelle');
$sth->execute();
$genres = $sth->fetchAll();
?>

<header>
  <div class="header-interieur">
    <a href="index.php" class="logo">
      <h1>Crousti <span>Movies</span></h1>
    </a>
    <nav>
      <ul>
        <li><a href="index.php">Accueil</a></li>
        <li>
          <a href="#">Genres ▾</a>
          <ul class="sous-menu">
            <?php foreach ($genres as $genre) { ?>
              <li>
                <a href="categorie.php?cat=<?php echo urlencode($genre['libelle']); ?>">
                  <?php echo $genre['libelle']; ?>
                </a>
              </li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="categorie.php?cat=nouveaux">Nouveautés</a></li>
        <li><a href="categorie.php?cat=notes">Mieux notés</a></li>
        <li><a href="nouveau_film.php" class="btn-ajouter">+ Ajouter un film</a></li>
      </ul>
    </nav>
  </div>
</header>
<main>
