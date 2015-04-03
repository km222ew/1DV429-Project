<?php

class Repository
{
    protected $dbConnectionQuestion;
    protected $dbConnectionUser;
    protected $dbUsername = 'root';
    protected $dbPassword = '';
    protected $dbConnstringUser = 'mysql:host=127.0.0.1;dbname=userdb';
    protected $dbConnstringQuestion = 'mysql:host=127.0.0.1;dbname=questiondb';

    public function connectionUser()
    {
        if ($this->dbConnectionUser == NULL)
        {
            $this->dbConnectionUser = new \PDO($this->dbConnstringUser, $this->dbUsername, $this->dbPassword);
        }

        $this->dbConnectionUser->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this->dbConnectionUser;
    }

    public function connectionQuestion()
    {
        if ($this->dbConnectionQuestion == NULL)
        {
            $this->dbConnectionQuestion = new \PDO($this->dbConnstringQuestion, $this->dbUsername, $this->dbPassword);
        }

        $this->dbConnectionQuestion->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $this->dbConnectionQuestion;
    }
}