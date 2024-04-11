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

        }
        return self::$dbConnection;
    }
}
