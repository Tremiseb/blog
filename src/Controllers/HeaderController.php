<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../Database/db.php';
require_once __DIR__ . '/../Database/userRepository.php';

$pdo = getPDO(DB_HOST, DB_NAME, DB_USER, DB_PASS);

$action = $_GET['action'] ?? null;

switch ($action) {

    case 'search':

        $query = $_GET['query'] ?? '';

        break;

    case 'filter':
        $categoryName = $_GET['category'] ?? '';
        if ($categoryName) {
            $articles = getArticlesLimitByCatName($pdo, $categoryName, $limit, $offset);
        } else {
            $articles = getArticlesLimit($pdo, $limit, $offset);
        }

        require __DIR__ . '/../Views/user/accueil.php';
        break;


    case 'create':
        
        break;
    }
