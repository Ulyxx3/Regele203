<?php
/* Page pour valider et traiter les données du formulaire pour ajouter un 
 * nouveau commentaire sur la page « film.php ».
 * 
 * Validez les données du formulaire, en affichant des messages d'erreur si
 * nécessaire. Si tout est bon, ajoutez le commentaire dans la base de données
 * et renvoyez l'utilisateur vers la page du film.
 *
 * Notez que l'identifiant du film se trouve dans $_POST['id_film']
 */

include('connexion.php');

// On récupère les données du formulaire
$pseudo  = $_POST['nom'];
$note    = $_POST['note'];
$avis    = $_POST['message'];
$id_film = $_POST['id_film'];

// Validation des données
$erreur = false;

if (strlen($pseudo) == 0) {
    echo '<p>Le pseudo est obligatoire. <a href="film.php?id=' . $id_film . '">Retour</a></p>';
    $erreur = true;
}
if ($note < 1 || $note > 5) {
    echo '<p>La note doit être entre 1 et 5. <a href="film.php?id=' . $id_film . '">Retour</a></p>';
    $erreur = true;
}
if (strlen($avis) == 0) {
    echo '<p>Le commentaire est obligatoire. <a href="film.php?id=' . $id_film . '">Retour</a></p>';
    $erreur = true;
}

// Si tout est bon, on insère le commentaire dans la base de données
if (!$erreur) {
    $sth = $dbh->prepare('INSERT INTO commentaire (note, pseudo, avis, idfilm) VALUES (:note, :pseudo, :avis, :id_film)');
    $values = array(
        'note'    => $note,
        'pseudo'  => $pseudo,
        'avis'    => $avis,
        'id_film' => $id_film
    );
    $sth->execute($values);

    // On redirige vers la page du film
    header('Location: film.php?id=' . $id_film);
    exit();
}