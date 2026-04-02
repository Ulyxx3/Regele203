<?php
/* Page pour valider et traiter les données du formulaire pour ajouter un 
 * nouveau film sur la page « nouveau_film.php ».
 * 
 * Validez les données du formulaire, en affichant des messages d'erreur si
 * nécessaire. Si tout est bon, ajoutez le film dans la base de données
 * et renvoyez l'utilisateur vers la page avec les nouveautés.
 *
 * Notez que l'identifiant du film se trouve dans $_POST['id_film']
 */

include('connexion.php');

// On récupère les données du formulaire
$titre        = $_POST['titre'];
$annee        = $_POST['annee'];
$duree        = $_POST['duree'];
$realisateur  = $_POST['realisateur'];
$acteurs      = $_POST['acteurs'];
$resume       = $_POST['resume'];
$id_genre     = $_POST['genre'];
$affiche      = $_POST['affiche'];
$bande_annonce = $_POST['bande_annonce'];

// Validation des données
$erreur = false;

if (strlen($titre) == 0) {
    echo '<p>Le titre est obligatoire. <a href="nouveau_film.php">Retour</a></p>';
    $erreur = true;
}
if (strlen($realisateur) == 0) {
    echo '<p>Le réalisateur est obligatoire. <a href="nouveau_film.php">Retour</a></p>';
    $erreur = true;
}
if (!is_numeric($annee) || $annee < 1888 || $annee > 2100) {
    echo '<p>L\'année n\'est pas valide. <a href="nouveau_film.php">Retour</a></p>';
    $erreur = true;
}

// Si tout est bon, on insère le film dans la base de données
if (!$erreur) {
    $sth = $dbh->prepare('INSERT INTO film (titre, annee, duree, resume, affiche, bandeannonce, realisateur, acteurs, idgenre) VALUES (:titre, :annee, :duree, :resume, :affiche, :bande_annonce, :realisateur, :acteurs, :id_genre)');
    $values = array(
        'titre'         => $titre,
        'annee'         => $annee,
        'duree'         => $duree,
        'resume'        => $resume,
        'affiche'       => $affiche,
        'bande_annonce' => $bande_annonce,
        'realisateur'   => $realisateur,
        'acteurs'       => $acteurs,
        'id_genre'      => $id_genre
    );
    $sth->execute($values);

    // On redirige vers la page des nouveautés
    header('Location: categorie.php?cat=nouveaux');
    exit();
}