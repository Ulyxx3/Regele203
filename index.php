<?php
// index.php
require_once 'includes/db.php';

$pageTitle = "Accueil";

// Base query to fetch all movies alphabetically
$sql = "SELECT m.*, g.nom as genre_nom, 
        (SELECT COUNT(*) FROM comments WHERE movie_id = m.id) as comment_count,
        (SELECT AVG(note) FROM comments WHERE movie_id = m.id) as avg_note 
        FROM movies m 
        JOIN genres g ON m.genre_id = g.id
        ORDER BY m.titre ASC";

$stmt = $pdo->query($sql);
$movies = $stmt->fetchAll();

include 'includes/header.php';
?>

<section class="hero-banner">
    <div class="container">
        <h1>Bienvenue sur Cine<span>MMI</span></h1>
        <p>Explorez notre catalogue complet de films.</p>
    </div>
</section>

<div class="container">
    <div class="movie-grid">
        <?php foreach ($movies as $movie): ?>
            <div class="movie-card">
                <a href="film.php?id=<?php echo $movie['id']; ?>">
                    <img src="<?php echo htmlspecialchars($movie['affiche_url']); ?>" alt="<?php echo htmlspecialchars($movie['titre']); ?>">
                    <div class="badge">
                        <i class="fas fa-star"></i> <?php echo $movie['avg_note'] ? number_format($movie['avg_note'], 1) : 'N/A'; ?>
                    </div>
                    <div class="info">
                        <h3><?php echo htmlspecialchars($movie['titre']); ?></h3>
                        <p><?php echo htmlspecialchars($movie['annee']); ?> • <?php echo htmlspecialchars($movie['genre_nom']); ?></p>
                        <p><i class="far fa-comment"></i> <?php echo $movie['comment_count']; ?> commentaires</p>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
.hero-banner {
    background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
    background-size: cover;
    background-position: center;
    padding: 6rem 0;
    text-align: center;
    margin-bottom: 2rem;
}

.hero-banner h1 {
    font-size: 3.5rem;
    margin-bottom: 1rem;
}

.hero-banner h1 span {
    color: var(--primary-color);
}
</style>

<?php include 'includes/footer.php'; ?>