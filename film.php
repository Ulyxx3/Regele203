<?php
// film.php
require_once 'includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

// Fetch movie details
$stmt = $pdo->prepare("SELECT m.*, g.nom as genre_nom 
                      FROM movies m 
                      JOIN genres g ON m.genre_id = g.id 
                      WHERE m.id = ?");
$stmt->execute([$id]);
$movie = $stmt->fetch();

if (!$movie) {
    header('Location: index.php');
    exit;
}

// Fetch average rating
$stmt = $pdo->prepare("SELECT AVG(note) as avg_note FROM comments WHERE movie_id = ?");
$stmt->execute([$id]);
$avgRating = $stmt->fetch()['avg_note'];

// Fetch comments
$stmt = $pdo->prepare("SELECT * FROM comments WHERE movie_id = ? ORDER BY date_publication DESC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll();

$pageTitle = $movie['titre'];
include 'includes/header.php';
?>

<div class="container">
    <div class="movie-hero">
        <div class="movie-poster-large">
            <img src="<?php echo htmlspecialchars($movie['affiche_url']); ?>" alt="<?php echo htmlspecialchars($movie['titre']); ?>">
        </div>
        <div class="movie-details">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <h1><?php echo htmlspecialchars($movie['titre']); ?></h1>
                <a href="ajouter_film.php?id=<?php echo $id; ?>" class="btn bin-secondary" style="background-color: var(--border-color); color: white;" title="Modifier les informations">
                    <i class="fas fa-edit"></i> Modifier le film
                </a>
            </div>
            <div class="movie-meta">
                <span><i class="far fa-calendar-alt"></i> <?php echo htmlspecialchars($movie['annee']); ?></span>
                <span><i class="far fa-clock"></i> <?php echo htmlspecialchars($movie['duree']); ?> min</span>
                <span><i class="fas fa-film"></i> <?php echo htmlspecialchars($movie['genre_nom']); ?></span>
                <span><i class="fas fa-star"></i> <?php echo $avgRating ? number_format($avgRating, 1) : 'N/A'; ?> / 5</span>
            </div>
            <div class="movie-summary">
                <h3>Résumé</h3>
                <p><?php echo nl2br(htmlspecialchars($movie['resume'])); ?></p>
            </div>
            <div class="movie-cast">
                <p><strong>Réalisateur :</strong> <span><?php echo htmlspecialchars($movie['realisateur']); ?></span></p>
                <p><strong>Acteurs principaux :</strong> <span><?php echo htmlspecialchars($movie['acteurs']); ?></span></p>
            </div>
        </div>
    </div>

    <?php if (!empty($movie['bande_annonce_url'])): ?>
        <div class="video-section">
            <h3>Bande Annonce</h3>
            <div class="video-container">
                <?php 
                // Basic conversion for YouTube URLs to embed URLs if needed
                $videoUrl = $movie['bande_annonce_url'];
                if (strpos($videoUrl, 'watch?v=') !== false) {
                    $videoUrl = str_replace('watch?v=', 'embed/', $videoUrl);
                }
                ?>
                <iframe src="<?php echo htmlspecialchars($videoUrl); ?>" allowfullscreen></iframe>
            </div>
        </div>
    <?php endif; ?>

    <section class="comments-section" id="comments-section">
        <h2>Commentaires (<?php echo count($comments); ?>)</h2>
        
        <!-- Add Comment Form -->
        <div class="form-container" style="margin: 2rem 0; width: 100%; max-width: 100%;">
            <h3>Laisser un commentaire</h3>
            <form action="ajouter_commentaire.php" method="POST">
                <input type="hidden" name="movie_id" value="<?php echo $id; ?>">
                <div class="form-group">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" required placeholder="Votre nom ou pseudo">
                </div>
                <div class="form-group">
                    <label for="note">Note</label>
                    <select name="note" id="note" required>
                        <option value="5">⭐⭐⭐⭐⭐ (5/5)</option>
                        <option value="4">⭐⭐⭐⭐ (4/5)</option>
                        <option value="3">⭐⭐⭐ (3/5)</option>
                        <option value="2">⭐⭐ (2/5)</option>
                        <option value="1">⭐ (1/5)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="commentaire">Votre commentaire</label>
                    <textarea name="commentaire" id="commentaire" rows="4" required placeholder="Qu'avez-vous pensé du film ?"></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
        </div>

        <!-- List Comments -->
        <div class="comments-list" id="comments-list">
            <?php foreach ($comments as $comment): ?>
                <div class="comment-card">
                    <div class="comment-header">
                        <span class="comment-author"><?php echo htmlspecialchars($comment['pseudo']); ?></span>
                        <span class="comment-date"><?php echo date('d/m/Y à H:i', strtotime($comment['date_publication'])); ?></span>
                    </div>
                    <div class="comment-note">
                        <?php for($i=1; $i<=5; $i++): ?>
                            <i class="<?php echo $i <= $comment['note'] ? 'fas' : 'far'; ?> fa-star"></i>
                        <?php endfor; ?>
                    </div>
                    <div class="comment-text">
                        <p><?php echo nl2br(htmlspecialchars($comment['commentaire'])); ?></p>
                    </div>
                    <a href="supprimer_commentaire.php?id=<?php echo $comment['id']; ?>&movie_id=<?php echo $id; ?>" class="btn-delete" title="Supprimer ce commentaire" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<?php include 'includes/footer.php'; ?>