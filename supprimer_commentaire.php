<?php
/* Page pour supprimer le commentaire d'un film.
 * 
 * Chaque commentaire de la page « film.php » contient un lien vers cette page
 * avec un paramètre pour identifier le commentaire.
 *
 * Vous devez :
 * 1. Supprimer le commentaire
 * 2. Rediriger l'utilisateur vers la page du film.
 *
 * Pour savoir vers quel film envoyer l'utilisateur, vous pouvez utiliser la 
 * base de données ou ajouter l'identifiant du film dans l'URL.
 */

include('connexion.php');

// On récupère l'identifiant du commentaire et du film depuis l'URL
$id_commentaire = $_GET['id'];
$id_film        = $_GET['idFilm'];

// On supprime le commentaire dans la base de données
$sth = $dbh->prepare('DELETE FROM commentaire WHERE idcommentaire = :id');
$values = array('id' => $id_commentaire);
$sth->execute($values);

// On redirige vers la page du film
header('Location: film.php?id=' . $id_film);
exit();