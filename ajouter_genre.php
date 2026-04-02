<?php
/* Page pour valider et traiter les données du formulaire pour ajouter un 
 * nouveau genre sur la page « nouveau_genre.php ».
 * 
 * Validez les données du formulaire, en affichant des messages d'erreur si
 * nécessaire. Si tout est bon, ajoutez le genre dans la base de données et
 * renvoyez l'utilisateur vers la page avec un formulaire pour ajouter un
 * nouveau film.
 */

include('connexion.php');

// On récupère le nom du genre depuis le formulaire
$libelle = $_POST['libelle'];

// Validation : le libellé ne doit pas être vide
if (strlen($libelle) == 0) {
    echo '<p>Le nom du genre est obligatoire. <a href="nouveau_genre.php">Retour</a></p>';
} else {
    // On insère le nouveau genre dans la base de données
    $sth = $dbh->prepare('INSERT INTO genre (libelle) VALUES (:libelle)');
    $values = array('libelle' => $libelle);
    $sth->execute($values);

    // On redirige vers la page d'ajout d'un film
    header('Location: nouveau_film.php');
    exit();
}