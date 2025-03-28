<?php
global $G_sRacine;
require_once "$G_sRacine/model/inc.db.php";

abstract class CPDOModel {
    private static $G_cCnx;

    private static function F_vSetDB() 
    {
        try {
            self::$G_cCnx = new PDO("mysql:host=".HOST.";dbname=".DBNAME, USER, PASSWD, [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ]);
            // set the PDO error mode to exception
            self::$G_cCnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
        
    }

    protected function F_cGetDB() 
    {
        if (self::$G_cCnx === null) {
            self::F_vSetDB();
        }
        return self::$G_cCnx;
    }

    public static function F_cGetDBStatic() 
    {
        if (self::$G_cCnx === null) {
            self::F_vSetDB();
        }
        return self::$G_cCnx;
    }
}