<?php
class Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }    
    /**
     * setFlash
     *
     * @param  mixed $field
     * @param  mixed $class
     * @param  mixed $msg_id
     * @param  mixed $message
     * @return void
     */
    public function setFlash($field, $class, $msg_id, $message)
    {
        $_SESSION[$field][$class][$msg_id] = $message;
    }    
    /**
     * getModel
     *
     * @param  mixed $model
     * @return void
     */
    public function getModel($model)
    {
        require_once("../src/Models/{$model}.php");
        return new $model;
    }
       
    /**
     *  renderView
     *
     * @param  mixed $content
     * @param  mixed $data
     * @return void
     */
    public function renderView($content, $data=null)
    {
        // load content
        // extract($data);
        //load template
        if (empty($data['template'])) {
            if (file_exists("../src/views/templates/base_header.php")) {
                require_once("../src/views/templates/base_header.php");
            } else {
                echo "views/templates/base_header.php does not exist";
            }
            if (file_exists("../src/views/{$content}.php")) {
                require_once("../src/views/{$content}.php");
            } else {
                echo "views/views/{$content}.php does not exist";
            }
            if (file_exists("../src/views/templates/base_footer.php")) {
                require_once("../src/views/templates/base_footer.php");
            } else {
                echo "views/templates/base_footer.php does not exist";
            }
        } elseif (!empty($data['template'])) {
            if (file_exists("../src/views/templates/{$data['template']}_header.php")) {
                require_once("../src/views/templates/{$data['template']}_header.php");
            } else {
                echo "views/templates/{$data['template']}_header.php does not exist";
            }
            if (file_exists("../src/views/{$content}.php")) {
                require_once("../src/views/{$content}.php");
            } else {
                echo "views/views/{$content}.php does not exist";
            }
            if (file_exists("../src/views/templates/{$data['template']}_footer.php")) {
                require_once("../src/views/templates/{$data['template']}_footer.php");
            } else {
                echo "views/templates/{$data['template']}_footer.php does not exist";
            }
        } else {
            die('Template inexistant');
        }
    }
}
