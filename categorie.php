<?php
// categorie.php
require_once 'includes/db.php';

$genreFilter = isset($_GET['genre']) ? $_GET['genre'] : null;
$catFilter = isset($_GET['cat']) ? $_GET['cat'] : null;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

$pageTitle = "Catégories";
if ($genreFilter) $pageTitle = "Genre : " . htmlspecialchars($genreFilter);
if ($catFilter == 'nouveautes') $pageTitle = "Nouveautés";
if ($catFilter == 'mieux-notes') $pageTitle = "Mieux notés";
if ($searchQuery) $pageTitle = "Résultats pour : " . htmlspecialchars($searchQuery);

// Base query
$sql = "SELECT m.*, g.nom as genre_nom, 
        (SELECT COUNT(*) FROM comments WHERE movie_id = m.id) as comment_count,
        (SELECT AVG(note) FROM comments WHERE movie_id = m.id) as avg_note 
        FROM movies m 
        JOIN genres g ON m.genre_id = g.id";

$params = [];

if ($genreFilter) {
    $sql .= " WHERE g.nom = ?";
    $params[] = $genreFilter;
} elseif ($catFilter == 'nouveautes') {
    $sql .= " ORDER BY m.id DESC LIMIT 10";
} elseif ($catFilter == 'mieux-notes') {
    $sql .= " ORDER BY avg_note DESC LIMIT 10";
} elseif ($searchQuery) {
    $sql .= " WHERE m.titre LIKE ? OR m.realisateur LIKE ? OR m.acteurs LIKE ?";
    $searchTerm = "%$searchQuery%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
} else {
    // If no filter, redirect to home
    header('Location: index.php');
    exit;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$movies = $stmt->fetchAll();

include 'includes/header.php';
?>

<section class="hero-banner">
    <div class="container">
        <h1><?php echo $pageTitle; ?></h1>
        <p>Explorez notre sélection de films exclusifs.</p>
    </div>
</section>

<div class="container">
    <?php if (empty($movies)): ?>
        <div class="no-results">
            <i class="fas fa-search"></i>
            <p>Aucun film ne correspond à votre recherche.</p>
            <a href="index.php" class="btn btn-primary">Voir tous les films</a>
        </div>
    <?php else: ?>
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
    <?php endif; ?>
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
    color: var(--primary-color);
}

.no-results {
    text-align: center;
    padding: 5rem 0;
    color: var(--text-dim);
}

.no-results i {
    font-size: 4rem;
    margin-bottom: 1.5rem;
    display: block;
}
</style>

<?php include 'includes/footer.php'; ?>