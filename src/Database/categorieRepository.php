<?php



function createCategorie(PDO $pdo, string $nom): bool {
    $nom = trim($nom);
    if ($nom === '') return false;

    $st = $pdo->prepare("SELECT 1 FROM categorie WHERE nom = ?");
    $st->execute([$nom]);
    if ($st->fetchColumn()) return false;

    $ins = $pdo->prepare("INSERT INTO categorie (nom) VALUES (?)");
    return $ins->execute([$nom]);
}

function updateCategorie(PDO $pdo, int $id, string $nom): bool {
    $id  = (int)$id;
    $nom = trim($nom);
    if ($id <= 0 || $nom === '') return false;

    $check = $pdo->prepare("SELECT 1 FROM categorie WHERE nom = ? AND id <> ?");
    $check->execute([$nom, $id]);
    if ($check->fetchColumn()) return false;

    $up = $pdo->prepare("UPDATE categorie SET nom = ? WHERE id = ?");
    return $up->execute([$nom, $id]);
}

function supprimerCategorie(PDO $pdo, int $id, bool $deleteArticles = false): bool {
    $id = (int)$id;
    if ($id <= 0) return false;

    if (!$deleteArticles) {
        $stmt = $pdo->prepare("UPDATE article SET categorie_id = NULL WHERE categorie_id = ?");
        $stmt->execute([$id]);

        $del = $pdo->prepare("DELETE FROM categorie WHERE id = ?");
        return $del->execute([$id]);
    }

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
