<?php

function getArticlesLimit(PDO $pdo, int $limit = 5, int $offset = 0): array {
    $stmt = $pdo->prepare("
        SELECT a.id, a.titre, a.description, a.user_id, u.username, a.categorie_id, c.nom AS categorie
        FROM article a
        LEFT JOIN users u ON a.user_id = u.id
        LEFT JOIN categorie c ON a.categorie_id = c.id
        ORDER BY a.id DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getArticlesCount(PDO $pdo): int {
    $stmt = $pdo->query("SELECT COUNT(*) FROM article");
    return (int)$stmt->fetchColumn();
}


function createArticle(PDO $pdo, string $titre, string $description, int $user_id, ?int $categorie_id): bool {

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM article WHERE titre = :titre");
    $stmt->execute(['titre' => $titre]);
    if ($stmt->fetchColumn() > 0) {
        return false; 
    }

    $stmt = $pdo->prepare("
        INSERT INTO article (titre, description, user_id, categorie_id) 
        VALUES (:titre, :description, :user_id, :categorie_id)
    ");
    return $stmt->execute([
        'titre'       => $titre,
        'description' => $description,
        'user_id'     => $user_id,
        'categorie_id'=> $categorie_id
    ]);
}


function getCategories(PDO $pdo): array {
    $stmt = $pdo->query("SELECT id, nom FROM categorie ORDER BY nom ASC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


