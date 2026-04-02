<!--
Page d'une catégorie. La structure est semblable à la page index.php, mais vous 
devez filtrer les films à afficher. Utilisez la méthode get et une requête SQL
pour filtrer ces films.
-->

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Crousti Movies - Catégorie</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
/* Importez le fichier « entete.php » dans le dossier « include » pour la barre 
 * de menu.
 */
include('include/entete.php');
?>

<?php
/* Ensuite, utilisez la méthode get pour savoir quelle catégorie il faut afficher :
 * 1. Si c'est les nouveautés, faites une requête SQL qui affiche les 10 derniers
 *    films ajoutés
 * 2. Sinon, mais que c'est les mieux notés, faites une requête SQL qui affiche 
 *    les 10 films les mieux notés
 * 3. Sinon, alors c'est un genre. Dans ce cas, faites une requête SQL qui prend 
 *    tous les films dans cette catégorie.
 *
 * Par exemple :
 * if ($_GET['cat'] == 'nouveaux') {
 *	  afficher les nouveautés
 * } elseif (les mieux notés) {
 *	  afficher les mieux notés
 * } else {
 *	  afficher les films du genre $_GET['cat']
 * }
 */

$cat = $_GET['cat'];

if ($cat == 'nouveaux') {
    // Les 10 derniers films ajoutés
    $titre_page = 'Nouveautés';
    $sth = $dbh->prepare('SELECT film.*, genre.libelle FROM film JOIN genre ON film.idgenre = genre.idgenre ORDER BY film.idfilm DESC LIMIT 10');
    $sth->execute();

} elseif ($cat == 'notes') {
    // Les 10 films les mieux notés
    $titre_page = 'Mieux notés';
    $sth = $dbh->prepare('SELECT film.*, genre.libelle, AVG(commentaire.note) AS note_moyenne FROM film JOIN genre ON film.idgenre = genre.idgenre LEFT JOIN commentaire ON film.idfilm = commentaire.idfilm GROUP BY film.idfilm, film.titre, film.annee, film.duree, film.resume, film.affiche, film.bandeannonce, film.realisateur, film.acteurs, film.idgenre, genre.libelle ORDER BY note_moyenne DESC NULLS LAST LIMIT 10');
    $sth->execute();

} else {
    // Filtrer par genre
    $titre_page = $cat;
    $sth = $dbh->prepare('SELECT film.*, genre.libelle FROM film JOIN genre ON film.idgenre = genre.idgenre WHERE genre.libelle = :cat ORDER BY film.titre');
    $values = array('cat' => $cat);
    $sth->execute($values);
}

$result = $sth->fetchAll();
?>

<h2 class="titre-section"><?php echo $titre_page; ?></h2>

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

<?php if (count($result) == 0) { ?>
  <p style="color: #9a9ab0; font-size: 0.9rem;">Aucun film dans cette catégorie.</p>
<?php } ?>

<?php include('include/pied.php'); ?>