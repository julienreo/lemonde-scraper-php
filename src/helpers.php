<?php

/**
 * Generate SQL query
 *  
 * @param  array $config
 * @return void
 */
function generateSqlQuery(array $config) {
    $sqlQuery = [];

    foreach ($config as $field => $fieldConfig) {
        $sqlQuery[$field] = sprintf("%s %s ", $field, strtoupper($fieldConfig['type']));

        $flags = [
            'unsigned' => 'UNSIGNED',
            'notNull' => 'NOT NULL',
            'null' => 'NULL',
            'primaryKey' => 'PRIMARY KEY',
            'autoIncrement' => 'AUTO_INCREMENT'
        ];

        $sqlFlags = [];
        foreach($flags as $key => $value) {
            if(isset($fieldConfig[$key])) {
                $sqlFlags[] = $value;
            }
        }

        $sqlQuery[$field] .= implode(' ', $sqlFlags);
    }

    $sqlQuery = implode(',', $sqlQuery);

    return $sqlQuery;
}