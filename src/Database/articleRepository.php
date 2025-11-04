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
