<?php

class Database
{
    private $link;

    public function __construct()
    {
        $this->connect();
    }

    /**
     * @return $this
     */
    private function connect()
    {
        $config = require_once 'config_bd.php';
        $dsn = 'mysql:host=' . $config['host'] . ';dbname=' . $config['dbName'] . ';charset=' . $config['charset'] . ';';
        $this->link = new PDO($dsn, $config['userName'], $config['password']);

        return $this;
    }

    //============================= Select ================================//

    /***
     * @return array
     */
    public function GetLogs()
    {
        $logs = $this->link->query("SELECT * FROM `logs` LIMIT 30");

        while($log = $logs->fetch(PDO::FETCH_ASSOC))
        {
            $arrayLogs[] = $log;
        }

        return $arrayLogs;
    }
}
