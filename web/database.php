<?php


class Database
{

    private $host = "db.ist.utl.pt";
    private $user = "ist425108";
    private $pass = "skqy1678";
    private $name = "ist425108";

    function __construct()
    {
        //
    }

    function connect()
    {
        $conn = new mysqli($this->host, $this->user, $this->pass, $this->name);
        if (mysqli_connect_errno()) {
            printf("Connect failed: %s\n", mysqli_connect_error());
            exit();
        }
        return $conn;
    }


    function url()
    {
        //"http://$_SERVER[HTTP_HOST]/ist425108/SIBD/";
        return "http://$_SERVER[HTTP_HOST]/";
    }
};
