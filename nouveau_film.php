<!--
Page avec le formulaire pour ajouter un nouveau film. Cette page ne contient que
la barre de menu et un long formulaire pour entrer les données.
Remarquez que, pour ajouter l'affiche et la bande annonce, il faudra fournir
une adresse URL.
-->

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Crousti Movies - Ajouter un film</title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php
/* Importez le fichier « entete.php » dans le dossier « include » pour la barre de 
 * menu.
 *
 * Voici un exemple incomplet pour le formulaire :
 */
include('include/entete.php');
?>

<section class="section-formulaire">
  <h2>Ajouter un film</h2>
  <form method="post" action="ajouter_film.php">

    <div class="formulaire-groupe">
      <label for="titre">Titre :</label>
      <input type="text" id="titre" name="titre" placeholder="Titre du film">
    </div>

    <div class="formulaire-groupe">
      <label for="annee">Année de sortie :</label>
      <input type="number" id="annee" name="annee" placeholder="Ex : 2024" min="1888" max="2100">
    </div>

    <div class="formulaire-groupe">
      <label for="duree">Durée (en minutes) :</label>
      <input type="number" id="duree" name="duree" placeholder="Ex : 120" min="1">
    </div>

    <div class="formulaire-groupe">
      <label for="realisateur">Réalisateur :</label>
      <input type="text" id="realisateur" name="realisateur" placeholder="Nom du réalisateur">
    </div>

    <div class="formulaire-groupe">
      <label for="acteurs">Acteurs principaux :</label>
      <input type="text" id="acteurs" name="acteurs" placeholder="Ex : Brad Pitt, Margot Robbie">
    </div>

    <div class="formulaire-groupe">
      <label for="resume">Résumé :</label>
      <textarea id="resume" name="resume" placeholder="Résumé du film..."></textarea>
    </div>

    <div class="formulaire-groupe">
      <label for="genre">Genre :</label>
      <select id="genre" name="genre">
        <!-- Les options pour le genre sont générées dynamiquement avec PHP -->
        <?php
        $sth = $dbh->prepare('SELECT * FROM genre ORDER BY libelle');
        $sth->execute();
        $genres = $sth->fetchAll();
        foreach ($genres as $g) {
          echo '<option value="' . $g['idgenre'] . '">' . $g['libelle'] . '</option>';
        }
        ?>
      </select>
      <a href="nouveau_genre.php" class="lien-genre">+ Ajouter un nouveau genre</a>
    </div>

    <div class="formulaire-groupe">
      <label for="affiche">Affiche (URL) :</label>
      <input type="url" id="affiche" name="affiche" placeholder="https://exemple.com/image.jpg">
    </div>

    <div class="formulaire-groupe">
      <label for="bande_annonce">Bande annonce (URL YouTube embed) :</label>
      <input type="url" id="bande_annonce" name="bande_annonce" placeholder="https://www.youtube.com/embed/xxx">
    </div>

    <input type="submit" class="btn-valider" value="Ajouter le film">
  </form>
</section>

<?php include('include/pied.php'); ?>