<?php
/**
 * Repository Catégorie — aucune dépendance métier “article” ici.
 * On travaille avec un PDO passé en argument.
 */

/** Retourne toutes les catégories, triées par nom */
function cat_getAll(PDO $pdo): array {
    $sql = "SELECT id, nom FROM categorie ORDER BY nom ASC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


function createCategorie(PDO $pdo, int $id, string $nom): bool {
    $id = (int)$id;
    $nom = trim($nom);
    if ($id <= 0 || $nom === '') return false;

    
    $st = $pdo->prepare("SELECT 1 FROM categorie WHERE nom = ? AND id <> ?");
    $st->execute([$nom, $id]);
    if ($st->fetchColumn()) return false;

    $up = $pdo->prepare("UPDATE categorie SET nom = ? WHERE id = ?");
    return $up->execute([$nom, $id]);
}


function supprimerCategorie(PDO $pdo, int $id, bool $deleteArticles = true): bool {
    if ($id <= 0) return false;

    $pdo->beginTransaction();
    try {
        if ($deleteArticles) {
            $pdo->prepare("DELETE FROM article WHERE categorie_id = ?")->execute([$id]);
        }
        $pdo->prepare("DELETE FROM categorie WHERE id = ?")->execute([$id]);

        $pdo->commit();
        return true;
    } catch (Throwable $e) {
        $pdo->rollBack();
        return false;
    }
}

