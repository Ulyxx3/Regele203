<?php
// includes/nav.php
require_once 'db.php';

// Fetch genres for the dropdown
$stmt = $pdo->query("SELECT * FROM genres ORDER BY nom ASC");
$genres = $stmt->fetchAll();
?>
<nav>
    <ul class="nav-links">
        <li><a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Accueil</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle">Genres <i class="fas fa-chevron-down"></i></a>
            <ul class="dropdown-menu">
                <?php foreach ($genres as $genre): ?>
                    <li><a href="categorie.php?genre=<?php echo urlencode($genre['nom']); ?>"><?php echo htmlspecialchars($genre['nom']); ?></a></li>
                <?php endforeach; ?>
                <li class="divider"></li>
                <li><a href="ajouter_genre.php"><i class="fas fa-plus"></i> Nouveau Genre</a></li>
            </ul>
        </li>
        <li><a href="categorie.php?cat=nouveautes" class="<?php echo isset($_GET['cat']) && $_GET['cat'] == 'nouveautes' ? 'active' : ''; ?>">Nouveautés</a></li>
        <li><a href="categorie.php?cat=mieux-notes" class="<?php echo isset($_GET['cat']) && $_GET['cat'] == 'mieux-notes' ? 'active' : ''; ?>">Mieux notés</a></li>
    </ul>
</nav>
