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

/** Crée une catégorie (retourne true si créée) */
function cat_create(PDO $pdo, string $nom): bool {
    $nom = trim($nom);
    if ($nom === '') return false;

    // éviter les doublons
    $st = $pdo->prepare("SELECT 1 FROM categorie WHERE nom = ?");
    $st->execute([$nom]);
    if ($st->fetchColumn()) return false;

    $ins = $pdo->prepare("INSERT INTO categorie (nom) VALUES (?)");
    return $ins->execute([$nom]);
}

/**
 * Met à jour le nom d’une catégorie (optionnel, pratique si tu ajoutes l’édition)
 * Retourne true si modifiée.
 */
function cat_update(PDO $pdo, int $id, string $nom): bool {
    $id = (int)$id;
    $nom = trim($nom);
    if ($id <= 0 || $nom === '') return false;

    // éviter les doublons (autre id)
    $st = $pdo->prepare("SELECT 1 FROM categorie WHERE nom = ? AND id <> ?");
    $st->execute([$nom, $id]);
    if ($st->fetchColumn()) return false;

    $up = $pdo->prepare("UPDATE categorie SET nom = ? WHERE id = ?");
    return $up->execute([$nom, $id]);
}

/**
 * Supprime une catégorie.
 * ⚠️ Ton schéma a `article.categorie_id` avec ON DELETE SET NULL,
 * donc les articles concernés ne seront pas supprimés.
 */
function cat_delete(PDO $pdo, int $id, bool $deleteArticles = true): bool {
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

