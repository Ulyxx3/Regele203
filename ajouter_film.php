<?php
// ajouter_film.php
require_once 'includes/db.php';

// Fetch genres for the dropdown
$stmt = $pdo->query("SELECT * FROM genres ORDER BY nom ASC");
$genres = $stmt->fetchAll();

$errors = [];
$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_POST['id']) ? (int)$_POST['id'] : null);
$movie = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $movie = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre']);
    $annee = (int)$_POST['annee'];
    $duree = (int)$_POST['duree'];
    $genre_id = (int)$_POST['genre_id'];
    $realisateur = trim($_POST['realisateur']);
    $acteurs = trim($_POST['acteurs']);
    $resume = trim($_POST['resume']);
    $affiche_url = trim($_POST['affiche_url']);
    $bande_annonce_url = trim($_POST['bande_annonce_url']);

    // Validation
    if (empty($titre)) $errors[] = "Le titre est obligatoire.";
    if ($annee < 1895 || $annee > date('Y') + 5) $errors[] = "L'année n'est pas valide.";
    if ($duree <= 0) $errors[] = "La durée doit être positive.";
    if ($genre_id <= 0) $errors[] = "Veuillez choisir un genre.";
    if (empty($realisateur)) $errors[] = "Le réalisateur est obligatoire.";
    if (empty($acteurs)) $errors[] = "Les acteurs sont obligatoires.";
    if (empty($resume)) $errors[] = "Le résumé est obligatoire.";
    if (empty($affiche_url) || !filter_var($affiche_url, FILTER_VALIDATE_URL)) $errors[] = "L'URL de l'affiche est invalide.";

    if (empty($errors)) {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE movies SET titre = ?, annee = ?, duree = ?, genre_id = ?, realisateur = ?, acteurs = ?, resume = ?, affiche_url = ?, bande_annonce_url = ? WHERE id = ?");
                $stmt->execute([$titre, $annee, $duree, $genre_id, $realisateur, $acteurs, $resume, $affiche_url, $bande_annonce_url, $id]);
                header('Location: film.php?id=' . $id);
            } else {
                $stmt = $pdo->prepare("INSERT INTO movies (titre, annee, duree, genre_id, realisateur, acteurs, resume, affiche_url, bande_annonce_url) 
                                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$titre, $annee, $duree, $genre_id, $realisateur, $acteurs, $resume, $affiche_url, $bande_annonce_url]);
                header('Location: categorie.php?cat=nouveautes');
            }
            exit;
        } catch (PDOException $e) {
            $errors[] = "Erreur : " . $e->getMessage();
        }
    }
}

$pageTitle = $id ? "Modifier le film" : "Ajouter un film";
include 'includes/header.php';
?>

<div class="container">
    <div class="form-container">
        <h1><?php echo $id ? "Modifier : " . htmlspecialchars($movie['titre']) : "Ajouter un nouveau film"; ?></h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" style="background-color: rgba(229, 9, 20, 0.2); color: white; padding: 1rem; border-radius: 4px; margin-bottom: 2rem; border-left: 5px solid var(--primary-color);">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="ajouter_film.php" method="POST">
            <?php if ($id): ?>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
            <?php endif; ?>
            <div class="form-group">
                <label for="titre">Titre du film</label>
                <input type="text" name="titre" id="titre" value="<?php echo isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : ($movie ? htmlspecialchars($movie['titre']) : ''); ?>" required>
            </div>

            <div class="form-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div class="form-group">
                    <label for="annee">Année de sortie</label>
                    <input type="number" name="annee" id="annee" value="<?php echo isset($_POST['annee']) ? htmlspecialchars($_POST['annee']) : ($movie ? htmlspecialchars($movie['annee']) : date('Y')); ?>" required>
                </div>
                <div class="form-group">
                    <label for="duree">Durée (minutes)</label>
                    <input type="number" name="duree" id="duree" value="<?php echo isset($_POST['duree']) ? htmlspecialchars($_POST['duree']) : ($movie ? htmlspecialchars($movie['duree']) : ''); ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="genre_id">Genre</label>
                <div style="display: flex; gap: 1rem;">
                    <select name="genre_id" id="genre_id" required style="flex-grow: 1;">
                        <option value="">-- Choisir un genre --</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?php echo $genre['id']; ?>" <?php 
                                if (isset($_POST['genre_id']) && $_POST['genre_id'] == $genre['id']) echo 'selected';
                                elseif ($movie && $movie['genre_id'] == $genre['id']) echo 'selected';
                            ?>>
                                <?php echo htmlspecialchars($genre['nom']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <a href="ajouter_genre.php" class="btn btn-secondary" style="background-color: var(--border-color); color: white;" title="Ajouter un genre"><i class="fas fa-plus"></i></a>
                </div>
            </div>

            <div class="form-group">
                <label for="realisateur">Réalisateur</label>
                <input type="text" name="realisateur" id="realisateur" value="<?php echo isset($_POST['realisateur']) ? htmlspecialchars($_POST['realisateur']) : ($movie ? htmlspecialchars($movie['realisateur']) : ''); ?>" required>
            </div>

            <div class="form-group">
                <label for="acteurs">Acteurs principaux (séparés par des virgules)</label>
                <textarea name="acteurs" id="acteurs" rows="2" required><?php echo isset($_POST['acteurs']) ? htmlspecialchars($_POST['acteurs']) : ($movie ? htmlspecialchars($movie['acteurs']) : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="resume">Résumé</label>
                <textarea name="resume" id="resume" rows="5" required><?php echo isset($_POST['resume']) ? htmlspecialchars($_POST['resume']) : ($movie ? htmlspecialchars($movie['resume']) : ''); ?></textarea>
            </div>

            <div class="form-group">
                <label for="affiche_url">URL de l'affiche</label>
                <input type="url" name="affiche_url" id="affiche_url" value="<?php echo isset($_POST['affiche_url']) ? htmlspecialchars($_POST['affiche_url']) : ($movie ? htmlspecialchars($movie['affiche_url']) : ''); ?>" placeholder="https://exemple.com/image.jpg" required>
            </div>

            <div class="form-group">
                <label for="bande_annonce_url">URL de la bande annonce (YouTube)</label>
                <input type="url" name="bande_annonce_url" id="bande_annonce_url" value="<?php echo isset($_POST['bande_annonce_url']) ? htmlspecialchars($_POST['bande_annonce_url']) : ($movie ? htmlspecialchars($movie['bande_annonce_url']) : ''); ?>" placeholder="https://youtube.com/watch?v=...">
            </div>

            <div style="margin-top: 2rem;">
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 1.1rem; padding: 1rem;">
                    <?php echo $id ? "Enregistrer les modifications" : "Ajouter le film"; ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>