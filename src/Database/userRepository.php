<?php

function login(PDO $pdo, string $email, string $password): ?array {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email AND password = :password");
    $stmt->execute([
        'email' => $email,
        'password' => md5($password)
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

function emailExists(PDO $pdo, string $email): bool {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn() > 0;
}


//Faut verifier que l'email et le pseudo ne soient pas déjà utilisés
function CreerUtilisateur(PDO $pdo, string $email, string $password, string $username, string $role): bool {
    $stmt = $pdo->prepare("INSERT INTO users (email, password, username, role) VALUES (:email, :password, :username, :role)");
    return $stmt->execute([
        'email' => $email,
        'password' => md5($password),
        'username' => $username,
        'role'     => $role
    ]);
}

function getRoles(PDO $pdo): array {
    $stmt = $pdo->query("SELECT DISTINCT role FROM users"); 
    return $stmt->fetchAll(PDO::FETCH_COLUMN); 
}
