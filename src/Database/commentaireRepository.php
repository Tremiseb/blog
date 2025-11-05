<?php

class CommentaireRepository {

    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function addCommentaire(int $articleId, int $userId, string $description): bool {
        $sql = "INSERT INTO commentaire (description, article_id, user_id) VALUES (:description, :article_id, :user_id)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':description' => $description,
            ':article_id' => $articleId,
            ':user_id' => $userId
        ]);
    }

    public function getCommentairesByArticle(int $articleId): array {
        $sql = "SELECT c.*, u.username
                FROM commentaire c
                JOIN users u ON c.user_id = u.id
                WHERE c.article_id = :article_id
                ORDER BY c.id ASC";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':article_id' => $articleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function supprimerCommentaire(int $commentaireId, int $userId): bool {
        $sql = "DELETE FROM commentaire WHERE id = :id AND user_id = :user_id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            ':id' => $commentaireId,
            ':user_id' => $userId
        ]);
    }
}
