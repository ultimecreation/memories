<?php
class DbSetupController extends Controller
{
    public function index()
    {
        $dbName = 'my_website';

        


        // create PDO connection
        function getConnection($dbName = null)
        {
            try {
                $bdd = new PDO("mysql:host=localhost;dbname=$dbName;charset=utf8", "root", "");
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
                return $bdd;
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }

        // CREATE DATABASE IF NOT EXISTS
        if ($dbName === '') {
            $bdd = getConnection();
            $query = $bdd->query("
                CREATE DATABASE IF NOT EXISTS my_website
                CHARACTER SET = 'utf8'
                COLLATE = 'utf8_general_ci';
        ");
            $res = $query->execute();
            debug($res);
            if ($res === 1) {
                $res->closeCursor();
                $bdd = null;
            }
        } 
        else {
        // CREATE TABLES IF NOT EXISTS
            $bdd = getConnection($dbName);

            // CREATE USERS TABLE
            $query = $bdd->query("
                CREATE TABLE IF NOT EXISTS users(
                    id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    first_name VARCHAR(255) NOT NULL COMMENT 'prénom',
                    last_name VARCHAR(255) NOT NULL COMMENT 'nom de famille',
                    email VARCHAR(255) NOT NULL COMMENT 'email utilisateur',
                    password VARCHAR(255) NOT NULL COMMENT 'mot de passe',
                    created_at DATETIME DEFAULT NOW() COMMENT 'date de création du compte',
                    confirmation_token VARCHAR(255) DEFAULT NULL COMMENT 'token de confirmation de création de compte',
                    consfirmation_token_requested_at VARCHAR(255) DEFAULT NULL COMMENT 'date de création du token de confirmation ',
                    reset_token VARCHAR(255) NOT NULL COMMENT 'token de réinitialisation',
                    reset_token_requested_at DATETIME DEFAULT NULL COMMENT 'date de création du token de réinitialisation '
                )ENGINE=InnoDB;
            ");
            $res = $query->execute();
            debug($res);
        }
    
    }
    
}
