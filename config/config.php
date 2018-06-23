<?php

$env = 'development';

$dbConfig = [
    'development' => [
        'server' => 'localhost',
        'username' => 'root',
        'password' => 'root',
        'database' => 'le_monde_scraper',
        'charset' => 'utf8'
    ],
    'production' => [
        'server' => 'XXXXXXXX.mysql.db',
        'username' => 'XXXXXXXX',
        'password' => 'XXXXXXXX',
        'database' => 'XXXXXXXX',
        'charset' => 'utf8',
    ]
];

$tableConfig = [
    'name' => 'le_monde_scraper',
    'engine' => 'innodb',
    'charset' => 'utf8',
    'fields' => [
        'id' => [
            'type' => 'mediumint',
            'unsigned' => true,
            'notNull' => true,
            'autoIncrement' => true,
            'primaryKey' => true
        ],
        'created_at' => [
            'type' => 'datetime',
            'notNull' => true
        ],
        'headline_id' => [
            'type' => 'char(32)',
            'notNull' => true
        ],
        'headline_title' => [
            'type' => 'varchar(255)',
            'notNull' => true
        ],
        'headline_img_src' => [
            'type' => 'varchar(255)',
            'null' => true
        ],
        'headline_img_legend' => [
            'type' => 'varchar(255)',
            'null' => true
        ],
        'headline_article_link' => [
            'type' => 'varchar(255)',
            'null' => true
        ]
    ]
];

$mailerConfig = [
    'development' => [
        'smtp' => 'SSL0.OVH.NET',
        'port' => 587,
        'ssl' => null,
        'username' => 'XXXXXXXX',
        'password' => 'XXXXXXXX'
    ],
    'production' => [
        'smtp' => 'SSL0.OVH.NET',
        'port' => 465,
        'ssl' => true,
        'username' => 'XXXXXXXX',
        'password' => 'XXXXXXXX'
    ]
];

$mailingList = [
    'XXXXXXXX@gmail.com' => 'XXXXXXXX',
    'XXXXXXXX@yahoo.fr' => 'XXXXXXXX'
];