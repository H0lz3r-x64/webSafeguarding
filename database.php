<?php

class database
{
    private static $servername = "localhost";
    private static $username = "root";
    private static $password = "";
    private static $dbname = "sql_injection";
    private static $dbConnection = null;

    public static function dbConnection()
    {
        if (self::$dbConnection === null) {
            self::$dbConnection = new mysqli(self::$servername, self::$username, self::$password, self::$dbname);

        } elseif (!self::$dbConnection->ping()) {
            self::$dbConnection = new mysqli('localhost', 'username', 'password', 'database');
        }

        return self::$dbConnection;
    }
}
