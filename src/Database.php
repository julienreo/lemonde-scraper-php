<?php

class Database {

    /**
     * Database configuration
     * 
     * @var array
     */
    private $dbConfig;

    /**
     * PDO instance
     * 
     * @var PDO
     */
    private $dbh;

    /**
     * @param array $dbConfig
     */
    public function __construct(array $dbConfig) {
        $this->dbConfig = $dbConfig;

        try {   
            $dbh = new PDO(
                sprintf('mysql:host=%s;charset=%s', $dbConfig['server'], $dbConfig['charset']),
                $dbConfig['username'],
                $dbConfig['password'],
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->dbh = $dbh;
        } 
        catch(PDOException $e) {
            error_log($e->getMessage());
            die;
        }

        $this->createDatabase();
    }

    /**
     * Create database
     * 
     * @return void
     */
    private function createDatabase() {
        $dbh = $this->dbh;
        $dbConfig = $this->dbConfig;

        try {
            $dbh->exec('CREATE DATABASE IF NOT EXISTS '. $dbConfig['database']);
            $dbh->exec('USE '. $dbConfig['database']);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Create table
     *
     * @param  array $tableConfig
     * @return void
     */
    public function createTable(array $tableConfig) {
        $dbh = $this->dbh;
        $dbConfig = $this->dbConfig;

        $sqlQuery = generateSqlQuery($tableConfig['fields']);

        try {
            $dbh->exec('USE '. $dbConfig['database']);

            $query = sprintf(
                'CREATE TABLE IF NOT EXISTS %s (%s) ENGINE=%s DEFAULT CHARSET=%s',
                $tableConfig['name'], 
                $sqlQuery,
                strtoupper($tableConfig['engine']),
                $tableConfig['charset']
            );

            $dbh->exec($query);           
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Retrieve last entry
     * 
     * @param  array $table
     * @return array
     */
    public function getLastEntry(array $table) {
        $dbh = $this->dbh;
        $dbConfig = $this->dbConfig;

        $query = sprintf('SELECT * FROM %s ORDER BY id DESC LIMIT 1', $table['name']);

        try {
            $dbh->exec('USE '. $dbConfig['database']);
            $lastEntry = $dbh->query($query)->fetch(PDO::FETCH_ASSOC);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }

        return $lastEntry;
    }

    /**
     * Insert data
     * 
     * @param  array $tableConfig
     * @param  array $data
     * @return void
     */
    public function insert(array $data, array $tableConfig) {
        $dbh = $this->dbh;
        $dbConfig = $this->dbConfig;

        $preparedFields = implode(',', array_keys($data));

        $preparedValues = array_map(function($value) {
            return ":{$value}";
        }, array_keys($data));
        $preparedValues = implode(',', $preparedValues);

        $preparedData = [];
        foreach ($data as $field => $value) {
            $key = ":{$field}";
            $preparedData[$key] = $value;
        }

        try {
            $dbh->exec('USE '. $dbConfig['database']);

            $query = sprintf(
                'INSERT INTO %s (%s) VALUES (%s)', 
                $tableConfig['name'],
                $preparedFields,
                $preparedValues
            );

            $stmt = $dbh->prepare($query);
            $stmt->execute($preparedData);
        }
        catch(PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
}