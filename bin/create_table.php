<?php

require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../src/Database.php';

try {
    $database = new Database($dbConfig[$env]);
    $database->createTable($tableConfig);
}
catch(Exception $e) {
    echo $e->getMessage() . PHP_EOL;
    die;
}

echo sprintf('%s table has been successfully created or already exists', $tableConfig['name']) . PHP_EOL;