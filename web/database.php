<?php


class Database
{

    private $host = "db.tecnico.ulisboa.pt";
    private $user = "ist425108";
    private $pass = "skqy1678";

    function __construct()
    {
        $this->dsn = "mysql:host=$this->host;dbname=$this->user";
    }

    function connect()
    {
        try {
            return new PDO($this->dsn, $this->user, $this->pass);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
            return null;
        }
    }


    function url()
    {
        //"http://$_SERVER[HTTP_HOST]/$user/SIBD/";
        return "http://$_SERVER[HTTP_HOST]/";
    }
};
