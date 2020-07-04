<?php
class Model
{
    protected $host = DB_HOST;
    protected $dbname = DB_NAME;
    protected $user = DB_USER;
    protected $pass = DB_PASS;
    protected $error;
    protected $bdd;

    public function __construct()
    {
        try {
            $this->bdd = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=UTF8", $this->user, $this->pass);
            $this->bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }
}
