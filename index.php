<!--
Page d'accueil avec :
 1. Un menu avec trois onglets : genre, nouveautés et mieux notés. L'onglet 
    genre contient un sous-menu avec tous les genres disponibles. Le menu 
    contient aussi un bouton pour ajouter un film (ajouter_film.php)
 2. La liste de tous les films. Chaque film est un lien cliquable vers la page
    film.php.
-->

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Crousti Movies - Accueil</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
/* Importez le fichier « entete.php » dans le dossier « include » pour la barre de 
 * menu.
 */
include('include/entete.php');
?>

<?php
/*
 * Ensuite, faites une requête SQL pour recupérer tous les films et les afficher.
 */

// On récupère tous les films avec leur genre
$sth = $dbh->prepare('SELECT film.*, genre.libelle FROM film JOIN genre ON film.idgenre = genre.idgenre ORDER BY film.titre');
$sth->execute();
$result = $sth->fetchAll();
?>

<h2 class="titre-section">Tous les films</h2>

<section class="grille-films">
  <?php foreach ($result as $row) { ?>
    <article class="carte-film">
      <a href="film.php?id=<?php echo $row['idfilm']; ?>">
        <?php if (!empty($row['affiche'])) { ?>
          <img src="<?php echo $row['affiche']; ?>" alt="Affiche de <?php echo $row['titre']; ?>">
        <?php } else { ?>
          <img src="https://via.placeholder.com/200x300?text=Pas+d%27affiche" alt="Pas d'affiche">
        <?php } ?>
        <div class="infos-carte">
          <p class="titre-film"><?php echo $row['titre']; ?></p>
          <p class="annee-film"><?php echo $row['annee']; ?></p>
          <p class="genre-film"><?php echo $row['libelle']; ?></p>
        </div>
      </a>
    </article>
  <?php } ?>
</section>

<?php include('include/pied.php'); ?>