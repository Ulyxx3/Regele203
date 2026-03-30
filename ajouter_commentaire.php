<?php
// ajouter_commentaire.php
require_once 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $movie_id = (int)$_POST['movie_id'];
    $pseudo = trim($_POST['pseudo']);
    $note = (int)$_POST['note'];
    $commentaire = trim($_POST['commentaire']);

    if (empty($pseudo) || empty($commentaire) || $note < 1 || $note > 5 || $movie_id <= 0) {
        // Simple error handling: redirect back if invalid
        header('Location: film.php?id=' . $movie_id . '&error=invalid_data');
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO comments (movie_id, pseudo, note, commentaire) VALUES (?, ?, ?, ?)");
        $stmt->execute([$movie_id, $pseudo, $note, $commentaire]);
        
        // Redirect back to the movie page
        header('Location: film.php?id=' . $movie_id . '#comments-section');
        exit;
    } catch (PDOException $e) {
        header('Location: film.php?id=' . $movie_id . '&error=db_error');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>