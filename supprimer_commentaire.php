<?php
// supprimer_commentaire.php
require_once 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int)$_GET['id'];
$movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : null;

try {
    // If movie_id was not passed via GET, fetch it first before deleting
    if (!$movie_id) {
        $stmt = $pdo->prepare("SELECT movie_id FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        $comment = $stmt->fetch();
        if ($comment) {
            $movie_id = $comment['movie_id'];
        }
    }

    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$id]);

    // Redirect back to the movie page or index if not found
    if ($movie_id) {
        header('Location: film.php?id=' . $movie_id . '#comments-list');
    } else {
        header('Location: index.php');
    }
    exit;
} catch (PDOException $e) {
    header('Location: index.php?error=delete_failed');
    exit;
}
?>