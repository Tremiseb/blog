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



function getArticlesByUserLimit(PDO $pdo, int $userId, int $limit = 5, int $offset = 0): array {
    $stmt = $pdo->prepare("
        SELECT 
            a.id, 
            a.titre, 
            a.description, 
            a.user_id, 
            u.username, 
            a.categorie_id, 
            c.nom AS categorie
        FROM article a
        LEFT JOIN users u ON a.user_id = u.id
        LEFT JOIN categorie c ON a.categorie_id = c.id
        WHERE a.user_id = :user_id
        ORDER BY a.id DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getArticlesFiltered(PDO $pdo, ?int $category = null, ?string $query = null, int $limit = 5, int $offset = 0): array {
    $sql = "SELECT a.*, c.nom AS categorie, u.username 
            FROM article a
            LEFT JOIN categorie c ON a.categorie_id = c.id
            LEFT JOIN user u ON a.user_id = u.id
            WHERE 1=1";

    $params = [];

    if ($category) {
        $sql .= " AND a.categorie_id = :category";
        $params[':category'] = $category;
    }

    if ($query) {
        $sql .= " AND a.titre LIKE :query";
        $params[':query'] = '%' . $query . '%';
    }

    $sql .= " ORDER BY a.created_at DESC
              LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

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

function supprimerArticle(PDO $pdo, int $articleId, int $userId): bool {
    $stmt = $pdo->prepare("
        DELETE FROM article 
        WHERE id = :id AND user_id = :user_id
    ");
    return $stmt->execute([
        'id' => $articleId,
        'user_id' => $userId
    ]);
}


