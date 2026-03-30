<?php
// ajouter_genre.php
require_once 'includes/db.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);

    // Validation
    if (empty($nom)) {
        $errors[] = "Le nom du genre est obligatoire.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO genres (nom) VALUES (?)");
            $stmt->execute([$nom]);
            
            // Redirect to ajouter_film.php as per requirements
            header('Location: ajouter_film.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Ce genre existe déjà.";
            } else {
                $errors[] = "Erreur lors de l'ajout : " . $e->getMessage();
            }
        }
    }
}

$pageTitle = "Ajouter un genre";
include 'includes/header.php';
?>

<div class="container">
    <div class="form-container" style="max-width: 500px;">
        <h1>Nouveau Genre</h1>
        <p style="color: var(--text-dim); margin-bottom: 2rem;">Veuillez saisir le nom du nouveau genre cinématographique.</p>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" style="background-color: rgba(229, 9, 20, 0.2); color: white; padding: 1rem; border-radius: 4px; margin-bottom: 2rem; border-left: 5px solid var(--primary-color);">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="ajouter_genre.php" method="POST">
            <div class="form-group">
                <label for="nom">Nom du genre</label>
                <input type="text" name="nom" id="nom" value="<?php echo isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : ''; ?>" placeholder="Ex: Science-Fiction, Horreur..." required>
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <a href="ajouter_film.php" class="btn btn-secondary" style="background-color: var(--border-color); color: white; flex: 1; justify-content: center;">Annuler</a>
                <button type="submit" class="btn btn-primary" style="flex: 2; justify-content: center;">Enregistrer</button>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>