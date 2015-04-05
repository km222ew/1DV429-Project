<?php

class Repository
{
    protected $dbConnectionUser;
    protected $dbUsername = 'root';
    protected $dbPassword = '';
    protected $dbConnstringUser = 'mysql:host=127.0.0.1;dbname=userdb';

    public function connectionUser()
    {
        if ($this->dbConnectionUser == NULL)
        {
            $this->dbConnectionUser = new \PDO($this->dbConnstringUser, $this->dbUsername, $this->dbPassword);
        }

        $this->dbConnectionUser->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this->dbConnectionUser;
    } 
}