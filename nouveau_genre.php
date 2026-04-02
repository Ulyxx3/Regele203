<!--
Page avec le formulaire pour ajouter un nouveau genre. Cette page ne contient 
que la barre de menu et un très petit formulaire pour entrer le nom du nouveau
genre
-->

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Crousti Movies - Ajouter un genre</title>
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
  <h2>Ajouter un nouveau genre</h2>
  <form method="post" action="ajouter_genre.php">
    <div class="formulaire-groupe">
      <label for="libelle">Nom du genre :</label>
      <input type="text" id="libelle" name="libelle" placeholder="Ex : Aventure">
    </div>
    <input type="submit" class="btn-valider" value="Ajouter le genre">
  </form>
</section>

<?php include('include/pied.php'); ?>