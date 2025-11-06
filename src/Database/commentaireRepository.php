<?php

function addCommentaire(PDO $pdo, int $articleId, int $userId, string $description): bool {
    $sql = "INSERT INTO commentaire (description, article_id, user_id) 
            VALUES (:description, :article_id, :user_id)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':description' => $description,
        ':article_id'  => $articleId,
        ':user_id'     => $userId
    ]);
}

function getCommentairesByArticle(PDO $pdo, int $articleId): array {
    $sql = "SELECT c.*, u.username
            FROM commentaire c
            JOIN users u ON c.user_id = u.id
            WHERE c.article_id = :article_id
            ORDER BY c.id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':article_id' => $articleId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function supprimerCommentaire(PDO $pdo, int $commentaireId, int $userId): bool {
    $sql = "DELETE FROM commentaire WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        ':id'      => $commentaireId,
        ':user_id' => $userId
    ]);
}

function getCommentairesByArticleLimit(PDO $pdo, int $articleId, ?int $limit = null): array {
    $sql = "SELECT c.*, u.username
            FROM commentaire c
            JOIN users u ON c.user_id = u.id
            WHERE c.article_id = :article_id
            ORDER BY c.id ASC";
    
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':article_id', $articleId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
