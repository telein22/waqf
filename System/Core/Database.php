<?php

namespace System\Core;

use PDO;
use PDOStatement;
use System\Core\Config;
use System\Core\Exceptions\SystemError;

class Database
{
    private static $_instance;

    public static function get()
    {
        if ( self::$_instance === null )
        {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    private $_connection;

    private $_affected;

    private function __construct()
    {
        $configs = $this->_getConfig();

        $this->_connection = new PDO(
            $configs['dns'],
            $configs['user'],
            $configs['password'],
            $configs['options']
        );
        $this->_connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    private function _getConfig()
    {
        $config = Config::get('Database');

        $host = $config->host;
        $user = $config->user;
        $password = $config->password;
        $database = $config->database;
        $port = $config->database;
        $options = $config->options;

        return [
            'dns' => 'mysql:host=' . $host . ';dbname=' . $database . ';port=' . $port,
            'user' => $user,
            'password' => $password,
            'options' => $options
        ];
    }

    private function _execute($sql, array $param = null)
    {
        if ( !is_string($sql) ) throw new SystemError("SQL should be a valid string");

        $stmt = $this->_connection->prepare($sql);
        $pParam = array();

        if ( $param != null )
        {
            $i = 0;
            foreach ( $param as $key => $value )
            {
                $paramType = PDO::PARAM_STR;
                if (is_int($value) )  $paramType = PDO::PARAM_INT;
                if (is_bool($value) )  $paramType = PDO::PARAM_BOOL;                
                if ( !$this->_checkColon($key) && is_string($key) ) $key = ':' . $key;
                
                $pParam[$key] = $value;
                $stmt->bindValue(is_int($key) ? $key + 1 : $key, $value, $paramType);
                $i++;
            }

        }
        
        $result = $stmt->execute();
        $this->_affected = $stmt->rowCount();
        return $stmt;
    }

    
    private function _wrapColumn( $columns )
    {
        $output = [];
        foreach ( $columns as $column )
        {
            $output[] = "`{$column}`";
        }

        return $output;
    }

    private function _checkColon( $string )
    {
        $string = substr($string, 0, 1);
        return $string == ':';
    }
    

    public function query( $sql, array $params = null, $fetch = null )
    {
        $stmt = $this->_execute($sql, $params);

        return $stmt ? new class($stmt) {
            
            /**
             * @var \PDOStatement
             */
            private $_stmt;

            public function __construct( $stmt )
            {
                $this->_stmt = $stmt;
            }

            public function get()
            {
                return $this->_stmt->fetch(PDO::FETCH_NAMED);
            }

            public function getAll()
            {
                return $this->_stmt->fetchAll(PDO::FETCH_NAMED);
            }

            public function rowCount()
            {
                return $this->_stmt->rowCount();
            }

        } : false;
    }

    public function insert( $table, $data, $replace = false )
    {
        $columns = array_keys($data);
        $columns = $this->_wrapColumn($columns);
        $placeHolders = array_fill(0, count($columns), '?');
        $columns = implode(",", $columns);
        $placeHolders = implode(",", $placeHolders);
        $values = array_values($data);

        $SQL = $replace ? "REPLACE INTO " : "INSERT INTO ";
        $SQL .= " `{$table}` ({$columns}) VALUES ({$placeHolders})";

        $result = $this->query($SQL, $values);
        return $result ? $this->lastInsertId() : false;
    }

    public function update( $table, $id, array $data )
    {
        $sets = [];
        foreach ( $data as $key => $value )
        {
            $sets[] = "`{$key}` = ?";
        }        
        $sets = implode(",", $sets);
        $dbValues = array_values($data);
        $dbValues[] = $id;
        $SQL = "UPDATE `{$table}` SET $sets WHERE `id` = ?";

        $result = $this->query($SQL, $dbValues);
        return (bool) $result;
    }

    public function lastInsertId()
    {
        return $this->_connection->lastInsertId();
    }


}