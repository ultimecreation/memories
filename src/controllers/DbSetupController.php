<?php
class DbSetupController extends Controller
{
    public function index()
    {
        $dbName = 'acmeblog';




        // create PDO connection        
        /**
         * getConnection
         *
         * @param  string $dbName
         * @return object 
         */
        function getConnection(?string $dbName = null)
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
                CREATE DATABASE IF NOT EXISTS acmeblog
                CHARACTER SET = 'utf8'
                COLLATE = 'utf8_general_ci';
        ");
            $res = $query->execute();
            debug($res);
            if ((int) $res === 1) {
                $res->closeCursor();
                $bdd = null;
            }
        } else {
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
                    confirmation_token_requested_at VARCHAR(255) DEFAULT NULL COMMENT 'date de création du token de confirmation ',
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
                    img VARCHAR(255),
                    created_at DATETIME DEFAULT NOW() COMMENT 'date de création de l\'article',
                    FOREIGN KEY (author_id) REFERENCES users(id) ON UPDATE CASCADE ON DELETE CASCADE,
                    FOREIGN KEY (category_id) REFERENCES categories(id) ON UPDATE CASCADE ON DELETE CASCADE 
                )ENGINE=InnoDB;
            ");
            $res = $query->execute();

            // CREATE user_notes TABLE
            $req = $bdd->query("
                CREATE TABLE IF NOT EXISTS user_notes(
                    user_id INT(11) NOT NULL,
                    article_id INT(11) NOT NULL,
                    note TINYINT(1) NOT NULL,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    PRIMARY KEY(user_id,article_id)
                );
            ");
            $req->execute();

            // procedure to get last articles
            $req = $bdd->query("
                CREATE PROCEDURE IF NOT EXISTS getLastArticles(IN articleNumber INT)
                    BEGIN
                        SELECT 
                            id AS article_id,
                            title,
                            content,
                            img
                        FROM articles
                        WHERE published=true
                        ORDER BY id DESC
                        LIMIT articleNumber;
                    END;
            ");
            $req->execute();

            // alter users table and last_login_at and total_points
            $req = $bdd->query("
                ALTER TABLE users 
                ADD COLUMN IF NOT EXISTS last_login_at DATETIME DEFAULT NULL AFTER created_at;
            ");
            $req->execute();

            // create table user actions to know which action was done for how many points and when
            $req = $bdd->query("
                CREATE TABLE IF NOT EXISTS user_actions(
                    id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    user_id INT(11) NOT NULL,
                    article_id INT(11) DEFAULT NULL,
                    product_id INT(11) DEFAULT NULL,
                    action VARCHAR(255) NOT NULL,
                    points_gained INT(11) DEFAULT NULL,
                    completed_at DATETIME DEFAULT NOW(),
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE
                );
            ");
            $req->execute();

            // create user_posints table
            $req = $bdd->query("
                CREATE TABLE IF NOT EXISTS user_points(
                    user_id INT(11) NOT NULL PRIMARY KEY,
                    total_points INT(11) DEFAULT NULL,
                    last_updated_at DATETIME DEFAULT NOW(),
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
                );
            ");
            $req->execute();
            // create a trigger to add registration points on register user
            $req = $bdd->query("
                CREATE TRIGGER IF NOT EXISTS addPointsAndSaveActionOnRegistration
                AFTER INSERT ON users
                FOR EACH ROW  
                    BEGIN
                        INSERT INTO user_points
                        SET 
                            user_id=NEW.id,
                            total_points=10,
                            last_updated_at=NOW();
                        INSERT INTO user_actions 
                        SET 
                            user_id=NEW.id,
                            action='Bonus à l\'inscription',
                            points_gained=10,
                            completed_at=NOW();
                    END;
            ");
            $req->execute();

            $req = $bdd->query("
                CREATE TRIGGER IF NOT EXISTS addPointsAndSaveActionOnNoteSubmitted
                AFTER INSERT ON user_notes
                    FOR EACH ROW
                        BEGIN
                            UPDATE user_points
                            SET 
                                total_points=total_points+New.note,
                                last_updated_at=NOW()
                            WHERE user_id=NEW.user_id;
                            INSERT INTO user_actions
                                SET 
                                    user_id=NEW.user_id, 
                                    article_id=NEW.article_id,
                                    action='attribution d\'une note',
                                    points_gained=New.note,
                                    completed_at=NOW();
                        END;
            ");
            $req->execute();

            $req = $bdd->query("
                CREATE TRIGGER IF NOT EXISTS addActionOnSaveLastLoginAt
                AFTER UPDATE ON users
                    FOR EACH ROW
                        BEGIN
                            IF ISNULL(OLD.last_login_at) THEN 
                                UPDATE user_points
                                SET 
                                    total_points=total_points+10,
                                    last_updated_at=NOW()
                                WHERE user_id=NEW.id;

                                INSERT INTO user_actions
                                SET 
                                    user_id=NEW.id, 
                                    action='première connexion',
                                    points_gained=10,
                                    completed_at=NOW();
                            
                            ELSEIF TIMESTAMPDIFF(HOUR,OLD.last_login_at,NOW()) > 48 THEN
                                UPDATE user_points
                                SET 
                                    total_points=total_points+1,
                                    last_updated_at=NOW()
                                WHERE user_id=NEW.id;

                                INSERT INTO user_actions
                                SET 
                                    user_id=NEW.id, 
                                    action='Bonus Silver connexion',
                                    points_gained=1,
                                    completed_at=NOW();
                            
                            ELSEIF TIMESTAMPDIFF(HOUR,OLD.last_login_at,NOW()) BETWEEN 24 AND 48 THEN
                                UPDATE user_points
                                SET 
                                    total_points=total_points+5,
                                    last_updated_at=NOW()
                                WHERE user_id=NEW.id;

                                INSERT INTO user_actions
                                SET 
                                    user_id=NEW.id, 
                                    action='Bonus Golden connexion',
                                    points_gained=5,
                                    completed_at=NOW();
                            END IF;
                        END;
            ");
            $req->execute();

            $req = $bdd->query("
                CREATE PROCEDURE IF NOT EXISTS getUserActions(IN userId INT,IN max INT,IN offset INT)
                    BEGIN
                    
                        IF userId IS NOT NULL AND max IS NOT NULL THEN
                            SELECT 
                                user_id,
                                article_id,articles.title AS article_title,product_id,products.name AS product_name,
                                action,points_gained,
                                completed_at
                            FROM user_actions
                            LEFT JOIN articles ON user_actions.article_id=articles.id
                            LEFT JOIN products ON user_actions.product_id=products.id
                            WHERE user_actions.user_id=userId
                            ORDER BY user_actions.id DESC
                            LIMIT max OFFSET offset ;
                        
                        ELSEIF userId IS NOT NULL AND max IS NULL THEN
                            SELECT 
                                user_id,
                                article_id,articles.title AS article_title,product_id,products.name AS product_name,
                                action,points_gained,
                                completed_at
                            FROM user_actions
                            LEFT JOIN articles ON user_actions.article_id=articles.id
                            LEFT JOIN products ON user_actions.product_id=products.id
                            WHERE user_actions.user_id=userId
                            ORDER BY user_actions.id DESC;

                        ELSEIF userId IS NULL AND max IS NOT NULL THEN
                            SELECT 
                                user_id,
                                article_id,articles.title AS article_title,product_id,products.name AS product_name,
                                action,points_gained,
                                completed_at,
                                CONCAT(users.first_name,' ',users.last_name) AS username
                            FROM user_actions
                            LEFT JOIN articles ON user_actions.article_id=articles.id
                            LEFT JOIN products ON user_actions.product_id=products.id
                            JOIN users ON user_actions.user_id=users.id
                            ORDER BY user_actions.id DESC
                            LIMIT max  OFFSET offset;

                        ELSE
                            SELECT 
                                user_id,article_id,product_id,
                                action,points_gained,
                                completed_at,
                                CONCAT(users.first_name,' ',users.last_name) AS username
                            FROM user_actions
                            LEFT JOIN articles ON user_actions.article_id=articles.id
                            LEFT JOIN products ON user_actions.product_id=products.id
                            JOIN users ON user_actions.user_id=users.id
                            ORDER BY user_actions.id DESC;  
                        END IF;
                    END;
            ");

            $res = $req->execute();

            $req = $bdd->query("
            CREATE TABLE IF NOT EXISTS product_types(
                id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                created_at DATETIME DEFAULT NOW()
                );
            ");
            $res = $req->execute();

            $req = $bdd->query("
            CREATE TABLE IF NOT EXISTS products(
                id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                product_type_id INT(11),
                value INT(11),
                price DECIMAL(6,2),
                created_at DATETIME DEFAULT NOW(),
                FOREIGN KEY (product_type_id) REFERENCES product_types(id) ON DELETE RESTRICT ON UPDATE CASCADE
                );
            ");
            $res = $req->execute();

            $req = $bdd->query("
                CREATE TABLE IF NOT EXISTS user_purchases(
                    id INT(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                    user_id INT(11) NOT NULL,
                    product_id INT(11) NOT NULL,
                    transaction_id VARCHAR(255) NOT NULL,
                    transaction_status VARCHAR(255) NOT NULL,
                    amount DECIMAL(6,2) NOT NULL,
                    created_at DATETIME,
                    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE RESTRICT ON UPDATE CASCADE,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE
                );
            ");
            $res = $req->execute();

            $req = $bdd->query("
                CREATE TRIGGER IF NOT EXISTS addPointsPurchased
                    AFTER INSERT ON user_purchases
                        FOR EACH ROW
                            BEGIN
                                SET @pointsToAdd = (SELECT value FROM products
                                WHERE id=NEW.product_id);

                                UPDATE user_points 
                                SET 
                                    total_points=total_points+@pointsToAdd,
                                    last_updated_at=NOW()
                                WHERE user_id=NEW.user_id;

                                INSERT INTO user_actions
                                SET 
                                    user_id=NEW.user_id,
                                    product_id=NEW.product_id,
                                    action='achat',
                                    points_gained=@pointsToAdd,
                                    completed_at=NOW();
                            END
            ");
            $req->execute();

            $req = $bdd->query("
                ALTER TABLE articles
                ADD COLUMN IF NOT EXISTS published BOOLEAN DEFAULT FALSE COMMENT 'article publié ou non' AFTER created_at  ;
            ");
            $res = $req->execute();



            debug($res);
        }
    }
}
