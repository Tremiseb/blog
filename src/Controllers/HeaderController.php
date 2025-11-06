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

        $categoryId = $_GET['category'] ?? null;

        break;


    case 'create':
        
        break;
    }
