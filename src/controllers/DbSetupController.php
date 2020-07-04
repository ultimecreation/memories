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
                    reset_token VARCHAR(255) DEFAULT NULL COMMENT 'token de réinitialisation',
                    reset_token_requested_at DATETIME DEFAULT NULL COMMENT 'date de création du token de réinitialisation '
                )ENGINE=InnoDB;
            ");
            $res = $query->execute();

            $query = $bdd->query("
                CREATE TABLE IF NOT EXISTS roles(
                    id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL COMMENT 'nom du rôle',
                    created_at DATETIME DEFAULT NOW() COMMENT 'date de création du rôle'
                )ENGINE=InnoDB;
            ");
            $res = $query->execute();
            
            $query = $bdd->query("
                CREATE TABLE IF NOT EXISTS user_roles(
                    user_id INT(11) NOT NULL,
                    role_id INT(11) NOT NULL,
                    PRIMARY KEY(user_id,role_id),
                    CONSTRAINT fk1_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT fk2_role_id FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE ON UPDATE CASCADE
                )ENGINE=InnoDB;
            ");
            $res = $query->execute();
            
            $query = $bdd->query("
                CREATE TABLE IF NOT EXISTS categories(
                    id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    name VARCHAR(255) NOT NULL COMMENT 'nom de la catégorie',
                    created_at DATETIME DEFAULT NOW() COMMENT 'date de création de la catégorie'
                )ENGINE=InnoDB;
            ");
            $res = $query->execute();
            
            $query = $bdd->query("
                CREATE TABLE IF NOT EXISTS articles(
                    id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    author_id INT(11) NOT NULL,
                    category_id INT(11) NOT NULL,
                    title VARCHAR(255) NOT NULL COMMENT 'nom de la catégorie',
                    content TEXT NOT NULL COMMENT 'contenu de l\'article',
                    created_at DATETIME DEFAULT NOW() COMMENT 'date de création de l\'article',
                    CONSTRAINT fk1_author_id FOREIGN KEY (author_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
                    CONSTRAINT fk2_category_id FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE CASCADE 
                )ENGINE=InnoDB;
            ");
            $res = $query->execute();
           
            $query = $bdd->query("
            CREATE TABLE IF NOT EXISTS user_likes(
                user_id INT(11) NOT NULL,
                article_id INT(11) NOT NULL,
                created_at DATETIME DEFAULT NOW() COMMENT 'date de création du like',
                CONSTRAINT fk1_liked_by_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
                CONSTRAINT fk2_article_id FOREIGN KEY (article_id) REFERENCES articles(id) ON UPDATE CASCADE ON DELETE CASCADE,
                PRIMARY KEY(user_id,article_id)
            )ENGINE=InnoDB;
            ");
            $res = $query->execute();
            debug($res);
        }
    
    }
    
}
