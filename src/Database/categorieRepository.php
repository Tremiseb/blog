<?php



/** Crée une catégorie (sans id, auto_increment) */
function createCategorie(PDO $pdo, string $nom): bool {
    $nom = trim($nom);
    if ($nom === '') return false;

    // Vérifie si la catégorie existe déjà
    $st = $pdo->prepare("SELECT 1 FROM categorie WHERE nom = ?");
    $st->execute([$nom]);
    if ($st->fetchColumn()) return false;

    $ins = $pdo->prepare("INSERT INTO categorie (nom) VALUES (?)");
    return $ins->execute([$nom]);
}

/** Renomme une catégorie existante */
function updateCategorie(PDO $pdo, int $id, string $nom): bool {
    $id  = (int)$id;
    $nom = trim($nom);
    if ($id <= 0 || $nom === '') return false;

    // Empêcher deux catégories avec le même nom
    $check = $pdo->prepare("SELECT 1 FROM categorie WHERE nom = ? AND id <> ?");
    $check->execute([$nom, $id]);
    if ($check->fetchColumn()) return false;

    $up = $pdo->prepare("UPDATE categorie SET nom = ? WHERE id = ?");
    return $up->execute([$nom, $id]);
}

/**
 * Supprime une catégorie (+ éventuellement ses articles)
 * $deleteArticles = true → supprime aussi les articles liés
 * $deleteArticles = false → laisse les articles avec categorie_id = NULL
 */
function supprimerCategorie(PDO $pdo, int $id, bool $deleteArticles = false): bool {
    $id = (int)$id;
    if ($id <= 0) return false;

    if (!$deleteArticles) {
        // Mode sécurisé : les articles ne sont pas supprimés
        $stmt = $pdo->prepare("UPDATE article SET categorie_id = NULL WHERE categorie_id = ?");
        $stmt->execute([$id]);

        $del = $pdo->prepare("DELETE FROM categorie WHERE id = ?");
        return $del->execute([$id]);
    }

    // Mode "hard" : supprime articles + catégorie
    $pdo->beginTransaction();
    try {
        $pdo->prepare("DELETE FROM article WHERE categorie_id = ?")->execute([$id]);
        $pdo->prepare("DELETE FROM categorie WHERE id = ?")->execute([$id]);
        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        $pdo->rollBack();
        return false;
    }
}
