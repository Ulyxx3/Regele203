<!--
Page d'un film. En plus de la barre de menu, il faut afficher les informations du
film qui se trouvent sur la base de données, la liste de commentaires et un 
formulaire pour laisser un nouveau commentaire avec une note.
-->
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Crousti Movies - Film</title>
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
/* Utilisez la méthode get pour savoir quel film afficher. Vous devrez utiliser
 * la valeur de $_GET['id'] pour faire une requête SQL.
 */

// On récupère les informations du film
$id_film = $_GET['id'];

$sth = $dbh->prepare('SELECT film.*, genre.libelle FROM film JOIN genre ON film.idgenre = genre.idgenre WHERE film.idfilm = :id');
$values = array('id' => $id_film);
$sth->execute($values);
$film = $sth->fetch();

// On récupère la note moyenne
$sth = $dbh->prepare('SELECT AVG(note) AS note_moyenne, COUNT(*) AS nb_commentaires FROM commentaire WHERE idfilm = :id');
$values = array('id' => $id_film);
$sth->execute($values);
$notes = $sth->fetch();

// On récupère tous les commentaires du film, triés par date décroissante
$sth = $dbh->prepare('SELECT * FROM commentaire WHERE idfilm = :id ORDER BY datecommentaire DESC');
$values = array('id' => $id_film);
$sth->execute($values);
$commentaires = $sth->fetchAll();
?>

<div class="film-detail">
  <!-- Affiche du film -->
  <div class="film-affiche">
    <?php if (!empty($film['affiche'])) { ?>
      <img src="<?php echo $film['affiche']; ?>" alt="Affiche de <?php echo $film['titre']; ?>">
    <?php } else { ?>
      <img src="https://via.placeholder.com/250x375?text=Pas+d%27affiche" alt="Pas d'affiche">
    <?php } ?>
  </div>

  <!-- Informations du film -->
  <div class="film-infos">
    <h1 class="film-titre"><?php echo $film['titre']; ?></h1>

    <div class="film-meta">
      <span><?php echo $film['annee']; ?></span>
      <span><?php echo $film['duree']; ?> min</span>
      <span class="badge-genre"><?php echo $film['libelle']; ?></span>
    </div>

    <?php if ($notes['nb_commentaires'] > 0) { ?>
      <p class="film-note-moyenne">
        ⭐ <?php echo number_format($notes['note_moyenne'], 1); ?> / 5
        (<?php echo $notes['nb_commentaires']; ?> avis)
      </p>
    <?php } else { ?>
      <p class="film-note-moyenne" style="color: #9a9ab0;">Pas encore de note</p>
    <?php } ?>

    <p class="film-resume"><?php echo $film['resume']; ?></p>

    <p class="film-detail-ligne"><strong>Réalisateur :</strong> <?php echo $film['realisateur']; ?></p>
    <p class="film-detail-ligne"><strong>Acteurs :</strong> <?php echo $film['acteurs']; ?></p>

    <!-- Bouton pour modifier le film -->
    <a href="modifier_film.php?id=<?php echo $id_film; ?>" class="btn-modifier">✏️ Modifier ce film</a>
  </div>

</div>

<!-- Bande annonce -->
<?php if (!empty($film['bandeannonce'])) { ?>
  <section class="bande-annonce">
    <h2>Bande annonce</h2>
    <iframe
      src="<?php echo $film['bandeannonce']; ?>"
      allowfullscreen>
    </iframe>
  </section>
<?php } ?>

<!-- Liste des commentaires -->
<?php
/*
 * Affichez tous les commentaires de ce film avec : auteur, note, commentaire,
 * bouton pour le supprimer, date, etc.
 */
?>
<section class="section-commentaires">
  <h2>Commentaires (<?php echo count($commentaires); ?>)</h2>

  <?php foreach ($commentaires as $row) { ?>
    <article class="commentaire">
      <div class="commentaire-entete">
        <span class="commentaire-pseudo"><?php echo $row['pseudo']; ?></span>
        <span class="commentaire-note">
          <?php for ($i = 1; $i <= 5; $i++) {
            if ($i <= $row['note']) { echo '⭐'; } else { echo '☆'; }
          } ?>
        </span>
        <span class="commentaire-date"><?php echo $row['datecommentaire']; ?></span>
        <a class="btn-supprimer"
           href="supprimer_commentaire.php?id=<?php echo $row['idcommentaire']; ?>&idFilm=<?php echo $id_film; ?>">
          Supprimer
        </a>
      </div>
      <p class="commentaire-texte"><?php echo $row['avis']; ?></p>
    </article>
  <?php } ?>

  <?php if (count($commentaires) == 0) { ?>
    <p style="color: #9a9ab0; font-size: 0.9rem;">Aucun commentaire pour l'instant.</p>
  <?php } ?>
</section>

<!-- Formulaire pour ajouter un commentaire -->
<?php
/*
 * Enfin, ajoutez un formulaire pour insérer un nouveau commentaire.
 */
?>
<section class="section-formulaire">
  <h2>Laisser un commentaire</h2>
  <form method="post" action="ajouter_commentaire.php">
    <div class="formulaire-groupe">
      <label for="nom">Votre pseudo :</label>
      <input type="text" id="nom" name="nom" placeholder="Votre pseudo" maxlength="15">
    </div>
    <div class="formulaire-groupe">
      <label for="note">Note (1 à 5) :</label>
      <input type="number" id="note" name="note" min="1" max="5" placeholder="Entre 1 et 5">
    </div>
    <div class="formulaire-groupe">
      <label for="message">Votre commentaire :</label>
      <textarea id="message" name="message" placeholder="Écrivez votre avis..."></textarea>
    </div>
    <!-- L'identifiant du film est passé en champ caché -->
    <input type="hidden" name="id_film" value="<?php echo $id_film; ?>">
    <input type="submit" class="btn-valider" value="Envoyer">
  </form>
</section>

<?php include('include/pied.php'); ?>