<?php

    class SqlHelper
    { 
        private static $host = '192.168.1.245';
        private static $user = 'root';
        private static $pwd = 'root';
        private static $dbname = 'amazonEngine';

        public $db;

        public function __construct()
        {
            $this->db = new PDO('mysql:host='.self::$host.';dbname='.self::$dbname, self::$user, self::$pwd);

            $this->db->exec("SET NAMES utf8");
        }
        
        public function exe_dql($sql)
        {
            $res = $this->db->query($sql);

            $res->setFetchMode(PDO::FETCH_ASSOC);

            $result = $res->fetchAll();

            return $result;
        }

        public function exe_dml($sql)
        {
            $result = $this->db->exec($sql);

            return $result;
        }

    }
